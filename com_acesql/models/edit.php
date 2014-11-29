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
if (version_compare(JVERSION, '3.0', 'ge')){

	if (!class_exists('JaModel')) {
		if (interface_exists('JModel')) {
			abstract class JaModel extends JModelLegacy {}
		}	
	}
}
else{
	
	class JaModel extends JModel {}

}


class AcesqlModelEdit extends JaModel {

	public function __construct()	{
		parent::__construct();
	}
	
    public function getData() {
		$task = JRequest::getCmd('task');
		$query = AcesqlHelper::getVar('qry');
		$key = JRequest::getCmd('key', JRequest::getCmd('key', null, 'post'), 'get');
	
		if (!is_null($query) && !is_null($key)) {
			$this->_db->setQuery($query);
			$rows = $this->_db->loadAssocList();
			
			$last_key_vol = $rows[count($rows) -1][$key];
			
			if ($task == 'edit') {
				foreach ($rows as $row) {
					$rows[$row[$key]] = $row;
				}
			}
			else {
				$rows[0] = array();
			}
		}
		else {
			$rows[0] = array();
			$last_key_vol = '';
		}
		
		return array($rows, $last_key_vol);
	}
	
    public function getFields() {
		$table = AcesqlHelper::getVar('tbl');
		
		$fields = $this->_db->getTableColumns($table);
		
		return $fields;
	}

    public function add($table, $sql) {
        $fields = JRequest::getVar('fields', array(), 'post', 'array');

        $query = "";

        if ((!is_null($table)) && !is_null($sql) && !is_null($fields)) {
           $i = 0;
           $comma = ', ';
           $cnt = count($fields);
           $sql_fields = '';
           $sql_values = '';

			foreach($fields as $name => $val) {
				$i++;
				if ($cnt <= $i) {
					$comma = '';
				}

				$sql_fields .= "`$name`".$comma;
				$sql_values .= "'$val'".$comma;
			}

			$query = "INSERT INTO $table ($sql_fields) VALUES($sql_values)";
        }

        $this->_db->setQuery($query);
        $this->_db->query();

        if (!empty($this->_db->_errorMsg)) {
            echo '<small style="color:red;">'.$this->_db->_errorMsg.'</small><br/>';
            return false;
        }
        else {
            return true;
        }
    }
	
    public function save($table, $query) {
        $key = JRequest::getCmd('key', null, 'post');
        $fields = JRequest::getVar('fields', array(), 'post', 'array');

        if ((!is_null($table)) && !is_null($query) && !empty($fields)) {
            $sql_save = "UPDATE {$table} SET ";

            $i = 0;
            $comma = ', ';
            $cnt = count($fields);

            foreach ($fields as $name => $val) {
				$i++;
				if ($cnt <= $i) {
                   $comma = '';
				}

				$sql_save .= "`{$name}`='".htmlspecialchars($val, ENT_QUOTES)."'".$comma;
            }

            $sql_save .= " WHERE `{$key}`='".$fields[$key]."'";
        }

        $this->_db->setQuery($sql_save);
        $this->_db->loadAssocList();

        if (!empty($this->_db->_errorMsg)) {
           echo '<small style="color:red;">'.$this->_db->_errorMsg.'</small><br/>';
           return false;
        }
        else {
           return true;
        }
    }
}