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


class AcesqlViewAcesql extends JaView {

	public function display($tpl = null){
		$document =& JFactory::getDocument();
		$document->addStyleSheet('components/com_acesql/assets/css/acesql.css');
		
		// Toolbar
		JToolBarHelper::title(JText::_('AceSQL').' - '.JText::_('COM_ACESQL_RUN_QUERY'), 'acesql');
		JToolBarHelper::custom('run', 'run.png', 'run.png', JText::_('COM_ACESQL_RUN_QUERY'), false);
		//JToolBarHelper::divider();
		//JToolBarHelper::custom('add', 'add.png', 'add.png', JText::_('COM_ACESQL_NEW_RECORD'), false);
		JToolBarHelper::divider();
		JToolBarHelper::custom('savequery', 'savequery.png', 'savequery.png', JText::_('COM_ACESQL_SAVE_QUERY'), false);
		JToolBarHelper::divider();
		JToolBarHelper::custom('csv', 'csv.png', 'csv.png', JText::_('COM_ACESQL_EXPORT_CSV'), false);
		// ACL
		if (version_compare(JVERSION,'1.6.0','ge') && JFactory::getUser()->authorise('core.admin', 'com_acesql')) {
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_acesql', '550');
		}
		
		$this->assignRef('data', $this->get('Data'));
		$this->assignRef('tables', $this->get('Tables'));
		$this->assignRef('prefix', $this->get('Prefix'));
		
		parent::display($tpl);
	}
}