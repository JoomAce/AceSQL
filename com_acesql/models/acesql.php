<?php
/**
* @version		1.0.0
* @package		AceSQL
* @subpackage	AceSQL
* @copyright	2009-2012 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*
* Based on EasySQL Component
* @copyright (C) 2008 - 2011 Serebro All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.lurm.net
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );
if (version_compare(JVERSION, '3.0', 'ge'))
{

	if (!class_exists('JaModel')) {
		if (interface_exists('JModel')) {
			abstract class JaModel extends JModelLegacy {}
		}
	}
}
else{

	class JaModel extends JModel {}

}

class AcesqlModelAcesql extends JaModel {

	public $_task = null;
	public $_table = null;
	public $_query = null;

	public function __construct()	{
		parent::__construct();
		
		$this->_task = JRequest::getCmd('task');
		
		$this->_table = AcesqlHelper::getVar('tbl');
        $this->_query = AcesqlHelper::getVar('qry');
	}

    public function getData() {
        $db = JFactory::getDbo();

		$html = '';
		if (empty($this->_query)) {
			return $html;
		}
		
		if (preg_match('/REPLACE PREFIX (.*) TO (.*)/', $this->_query)) {
			self::_replacePrefix($db, $db, $this->_query);
		}
		else {
			$query_arr = self::_splitSQL($this->_query);
			
			for ($i = 0; $i <= (count($query_arr) -1 ); $i++) {
				if (empty($query_arr[$i])) {
					continue;
				}
				
				$html .= self::_getHtmlTable($query_arr[$i], $i, $db);
			}
		}
		
		return $html;
    }
	
	public function getTables() {
		$table_list = '';
		
		// Get table list
		$this->_db->setQuery('SHOW TABLES');
		$tables = $this->_db->loadAssocList();

		if (empty($tables)) {
			return $table_list;
		}
		
		$config = JFactory::getConfig();
		if (version_compare(JVERSION, '1.6.0', 'ge')) {
    	    $database = $config->get('db');
        }
        else {
            $database = $config->getValue('config.db');
        }
		
		$key = 'Tables_in_'.$database;
		
		foreach ($tables as $table) {
			$_sel = '';
			if ($table[$key] == $this->_table) {
				$_sel = 'selected';
			}
			
			$table_list .= '<option '.$_sel.' value="'.$table[$key].'">'.$table[$key].'</option>';
		}
		
		return $table_list;
	}
	
	public function getPrefix() {
		return $this->_db->getPrefix();
	}
	
    public function delete($table) {
        $sql = AcesqlHelper::getVar('qry');
        $key = JRequest::getString('key', null, 'get');

        if (!is_null($sql) && !is_null($key)) {
            $id = JRequest::getCmd('id', null, 'get');

            $this->_db->setQuery("DELETE FROM {$table} WHERE $key = '$id'");
            $this->_db->query();

            if (!empty($this->_db->_errorMsg)) {
                echo '<small style="color:red;">'.$this->_db->_errorMsg.'</small><br/>';
                return false;
            }
            else {
                return true;
            }
        }
    }
	
    public function _replacePrefix($query) {
        $mainframe = JFactory::getApplication();

        $msg = '';

        $config_file = JPATH_CONFIGURATION.'/configuration.php';

        list($prefix, $new_prefix) = sscanf(str_replace(array('`', '"', "'"),'',strtolower(trim($query))), "replace prefix %s to %s");

        if (!is_writable($config_file)) {
            echo '<h2 style="color: red;">'.sprintf(JText::_('COM_ACESQL_CONFIG_NOT_WRITABLE'), $config_fname).'</h2>';
            return $msg;
        }

        $this->_db->setQuery("SHOW TABLES LIKE '".$prefix."%'");
        $tables = $this->_db->loadResultArray();

        foreach($tables as $tbl) {
            $new_tbl = str_replace($prefix, $new_prefix, $tbl);
            $this->_db->setQuery( 'ALTER TABLE `'.$tbl.'` RENAME `'.$new_tbl.'`' );
            $this->_db->query();

    		if (!empty($this->_db->_errorMsg)) {
                echo '<small style="color:red;">'.$this->_db->_errorMsg.'</small><br/>';
            }
        }

    	$config =& JFactory::getConfig();
        if (version_compare(JVERSION, '1.6.0', 'ge')) {
    	    $config->set('dbprefix', $new_prefix);
        }
        else {
            $config->setValue('config.dbprefix', $new_prefix);
        }

    	/*jimport('joomla.filesystem.path');
    	if (!$ftp['enabled'] && JPath::isOwner($config_fname) && !JPath::setPermissions($config_fname, '0644')) {
    		JError::raiseNotice('SOME_ERROR_CODE', 'Could not make configuration.php writable');
    	}*/

    	jimport('joomla.filesystem.file');

        if (version_compare(JVERSION,'1.6.0','ge')) {
            if (!JFile::write($config_file, $config->toString('PHP', array('class' => 'JConfig', 'closingtag' => false))) ) {
                $msg = JText::_('COM_ACESQL_DONE');
            }
            else {
                $msg = JText::_('ERRORCONFIGFILE');
            }
        }
        else {
            if (JFile::write($config_file, $config->toString('PHP', 'config', array('class' => 'JConfig')))) {
                $msg = JText::_('COM_ACESQL_DONE');
            }
            else {
                $msg = JText::_('ERRORCONFIGFILE');
            }
        }

    	return $msg;
    }

    public function exportToCsv($query) {
        $csv_save = '';

        $this->_db->setQuery($query);
        $rows = $this->_db->loadAssocList();

        if (!empty($rows)) {
            $comma = JText::_('COM_ACESQL_CSV_DELIMITER');
            $CR = "\r";

            // Make csv rows for field name
            $i = 0;
            $fields = $rows[0];
            $cnt_fields = count($fields);
            $csv_fields = '';
            foreach($fields as $name => $val) {
                $i++;

                if ($cnt_fields <= $i) {
                    $comma = '';
                }

                $csv_fields .= $name.$comma;
            }

            // Make csv rows for data
            $csv_values = '';
            foreach($rows as $row) {
                $i = 0;
                $comma = JText::_('COM_ACESQL_CSV_DELIMITER');

                foreach($row as $name=>$val) {
                    $i++;
                    if ($cnt_fields <= $i) {
                        $comma = '';
                    }

                    $csv_values .= $val.$comma;
                }

                $csv_values .= $CR;
            }

            $csv_save = $csv_fields.$CR.$csv_values;
        }

        return $csv_save;
    }
	
	public function _getHtmlTable($query, $num, $db) {
       // trim long query for output
       $show_query = (strlen(trim($query)) > 100) ? substr($query, 0, 50).'...' : $query;
       
	   // run query
       $db->setQuery($query);
       $rows = $db->loadAssocList();
       $aff_rows = $db->getAffectedRows();
	   
       $num++;
       $body = "<br> $num. [ ".$show_query." ], ";
       $body .= 'rows: '.$aff_rows;
       $body .= '<br />';
	   
		$table = self::_getTableFromSQL($query); // get table name from query string
		$_sel = (substr(strtolower($query), 0, 6) == 'select' && !strpos(strtolower($query), 'procedure analyse'));
		
		// If return rows then display table
		if (!empty($rows)) {
			// Begin form and table
			$body .= '<br />';
			$body .= '<div style="overflow: auto;">';
			$body .= '<table class="adminlist table">';
			$body .= "<thead>";
			$body .= "<tr>";
			
			// Display table header
			if ($_sel) {
				$body .= '<th>'.JText::_('COM_ACESQL_ACTION').'</th>';
			}
			
			$k_arr = $rows[0];
			$f = 1;
			$key = '';
			foreach($k_arr as $var => $val) {
				if ($f) {
					$f = 0;
					$key = $var;
				}
				
				if (preg_match("/[a-zA-Z]+/", $var, $array)) {
					$body .= '<th>'.$var."</th>";
				}
			}
			
			$body .= "</tr>";
			$body .= "</thead>";
			
			// Get unique field of table
			$uniq_fld = (self::_isTable($table)) ? self::_getUniqFld($table) : '';
			$key = empty($uniq_fld) ? $key : $uniq_fld;
			
			// Display table rows
			$k = 0;
			$i = 0;
			foreach($rows as $row) {
				$body .= '<tbody>';
				$body .= '<tr valign=top class="row'.$k.'">';
			   
				if ($_sel) {
					$body .= '<td align=center nowrap>';
						$body .= '<a href="index.php?option=com_acesql&task=edit&ja_tbl_g='.base64_encode($table).'&ja_qry_g='.base64_encode($query).'&key='.$key.'&id='.$row[$key].'">';
							$body .= '<img border="0" src="components/com_acesql/assets/images/icon-16-edit.png" alt="'.JText::_('COM_ACESQL_EDIT').'" title="'.JText::_('COM_ACESQL_EDIT').'" />';
						$body .= '</a>';
						$body .= '&nbsp;';
						$body .= '<a href="#" onclick="if (confirm(\'Are you sure you want to delete this record?\')) {this.href=\'index.php?option=com_acesql&controller=acesql&task=delete&ja_tbl_g='.base64_encode($table).'&ja_qry_g='.base64_encode($query).'&key='.$key.'&id='.$row[$key].'\'};">';
							$body .= '<img border="0" src="components/com_acesql/assets/images/icon-16-delete.png" alt="'.JText::_('COM_ACESQL_DELETE').'" title="'.JText::_('COM_ACESQL_DELETE').'" />';
						$body .= '</a>';
					$body .= '</td>';
				}
				
				foreach ($row as $var => $val) {
					if (preg_match("/[a-zA-Z]+/", $var, $array)) {
						$body .= '<td>&nbsp;'.htmlspecialchars(substr($val, 0, 100))."&nbsp;</td>\n";
					}
				}
				
				$body .= "</tbody>";
				$body .= "</tr>";
				$k = 1 - $k;
				$i++;
			}
			
			// End table and form
			$body .= '</table>';
			$body .= '<br />';
			$body .= '</div>';
			$body .= '<input type="hidden" name="key" value="'.$key.'">';
		}
		else {
			// Display DB errors
			$body .= '<small style="color:red;">'.$db->_errorMsg.'</small><br/>';
		}
	   
       return $body.'<br />';
	}
	
	public function _getTableFromSQL($sql) {
		$in = strpos(strtolower($sql), 'from ')+5;
		$end = strpos($sql, ' ', $in);
		$end = empty($end) ? strlen($sql) : $end;  // If table name in query end
		
		return substr($sql, $in, $end-$in);
	}
	
	public function _splitSQL($sql) {
		$sql = trim($sql);
		$sql = preg_replace("/\n#[^\n]*\n/", "\n", $sql);
		
		$buffer = array();
		$ret = array();
		$in_string = false;
		
		for($i = 0; $i < strlen($sql) - 1; $i++) {
			if ($sql[$i] == ";" && !$in_string) {
				$ret[] = substr($sql, 0, $i);
				$sql = substr($sql, $i + 1);
				$i = 0;
			}
		   
			if ($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\") {
				$in_string = false;
			}
			elseif(!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")) {
				$in_string = $sql[$i];
			}
		   
			if (isset($buffer[1])) {
				$buffer[0] = $buffer[1];
			}
			
			$buffer[1] = $sql[$i];
		}
	   
		if (!empty($sql)) {
            $ret[] = $sql;
		}
		
		return($ret);
	}
	
	public function _isTable($table) {
		$tables = $this->_db->getTableList();
		$table = str_replace("#__", $this->_db->getPrefix(), $table);
		
		return (strpos(implode(";", $tables),$table) > 0);
	}
	
	public function _getUniqFld($table) {
		$this->_db->setQuery('SHOW KEYS FROM '.$table);
		$indexes = $this->_db->loadAssocList();

		$uniq_fld = '';
		if (empty($indexes)) {
			return $uniq_fld;
		}
		
		foreach($indexes as $index) {
			if ($index['Non_unique'] == 0) {
				$uniq_fld = $index['Column_name'];
				break;
			}
		}
		
		return $uniq_fld;
	}
}