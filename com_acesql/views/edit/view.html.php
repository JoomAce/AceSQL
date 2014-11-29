<?php
/**
* @version		1.0.0
* @package		AceSQL
* @subpackage	AceSQL
* @copyright	2009-2012 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

//No Permision
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

if (version_compare(JVERSION, '3.0', 'ge')){

	if (!class_exists('JaView')) {
		if (interface_exists('JView')) {
			abstract class JaView extends JViewLegacy {}
		}	
	}
}
else{
	
	class JaView extends JView {}

}


class AcesqlViewEdit extends JaView {

	public function display($tpl = null) {
		$db = JFactory::getDbo();
		
		$task = JRequest::getCmd('task');
		$table = AcesqlHelper::getVar('tbl');
        $query = AcesqlHelper::getVar('qry');
		$id = JRequest::getInt('id', JRequest::getInt('id', null, 'post'), 'get');
		$key = JRequest::getCmd('key', JRequest::getCmd('key', null, 'post'), 'get');
		
		$document =& JFactory::getDocument();
		$document->addStyleSheet('components/com_acesql/assets/css/acesql.css');
		
		// Toolbar
		JToolBarHelper::title(JText::_('AceSQL') .': <small><small> '. $table.' [ '.$key.' = '.$id.' ]' .' </small></small>', 'acesql');
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::divider();
		JToolBarHelper::cancel();
       
		if ($task == 'edit') {
			$fld_value = '$value = $this->rows[$this->id][$field];';
		}
		else {
			$fld_value = '$value = "";';
		}
		
		list($rows, $last_key_vol) = $this->get('Data');
		
		$this->assignRef('task', 			$task);
		$this->assignRef('id', 				$id);
		$this->assignRef('key', 			$key);
		$this->assignRef('table', 			$table);
		$this->assignRef('query', 			$query);
		$this->assignRef('fld_value', 		$fld_value);
		$this->assignRef('last_key_vol', 	$last_key_vol);
		$this->assignRef('rows', 			$rows);
		$this->assignRef('fields', 			$this->get('Fields'));
		
		parent::display($tpl);
	}
}