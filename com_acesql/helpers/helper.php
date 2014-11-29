<?php
/**
* @version		1.0.0
* @package		AceSQL
* @subpackage	AceSQL
* @copyright	2009-2012 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

abstract class AcesqlHelper {

    public static function getVar($v = 'tbl') {
		static $vars = array();
		
		if (!isset($vars[$v])) {
			$vars[$v] = JRequest::getString('ja_'.$v.'_p', '', 'post');
			
			if (empty($vars[$v])) {
				$_var = base64_decode(JRequest::getString('ja_'.$v.'_g', '', 'get'));
				$vars[$v] = stripslashes($_var);
			}
		}

   		return $vars[$v];
   	}

    public static function renderHtml($name, $type, $value) {
		$type = trim(preg_replace('/unsigned/i', '', $type));

		switch (strtolower($type)) {
			case 'hidden':
				$ret = '<input type="hidden" name="fields['.$name.']" value="'.$value.'">';
				break;
			case 'disabled':
				$ret = '<input type="text" name="fields['.$name.']" value="'.$value.'" disabled="disabled">';
				break;
			case 'char':
			case 'nchar':
				$ret = '<input type="text" name="fields['.$name.']"  style="width:7%;" value="'.$value.'">';
				break;
			case 'varchar':
			case 'nvarchar':
				$ret = '<input type="text" name="fields['.$name.']" style="width:40%;" value="'.$value.'">';
				break;
			case 'tinyblob':
			case 'tinytext':
			case 'blob':
			case 'text':
				$ret = '<textarea name="fields['.$name.']" style="width:70%;">'.$value.'</textarea>';
				break;
			case 'mediumblob':
			case 'mediumtext':
			case 'longblob':
			case 'longtext':
				$ret = '<textarea name="fields['.$name.']" style="width:70%; height:150px;">'.$value.'</textarea>';
				break;
			//int
			case 'bit':
			case 'bool':
				$ret = '<input type="checkbox" name="fields['.$name.']">';
				break;
			case 'tinyint':
			case 'smallint':
			case 'mediumint':
			case 'integer':
			case 'int':
			case 'bigint':
			case 'datetime':
			case 'time':
				$ret = '<input type="text" name="fields['.$name.']" style="width:15%;" value="'.$value.'">';
				break;
			//real
			case 'real':
			case 'float':
			case 'decimal':
			case 'numeric':
			case 'double':
			case 'double precesion':
				$ret = '<input type="text" name="fields['.$name.']" style="width:15%;" value="'.$value.'">';
				break;
			default:
				$ret = '';
		}

		return $ret;
    }
}