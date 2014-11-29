<?php
/**
* @version		1.0.0
* @package		AceSQL
* @subpackage	AceSQL
* @copyright	2009-2012 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

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


class AcesqlViewQueries extends JaView {

	function display($tpl = null) {
        $mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');
		$document =& JFactory::getDocument();
  		$document->addStyleSheet('components/com_acesql/assets/css/acesql.css');
		
		JToolBarHelper::title(JText::_('AceSQL').' - '.JText::_('COM_ACESQL_SAVED_QUERIES'), 'acesql');
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();
        // ACL
        if (version_compare(JVERSION,'1.6.0','ge') && JFactory::getUser()->authorise('core.admin', 'com_acesql')) {
            JToolBarHelper::divider();
            JToolBarHelper::preferences('com_acesql', '550');
        }
	
		$this->mainframe = JFactory::getApplication();
		$this->option = JRequest::getWord('option');

		$filter_order		= $mainframe->getUserStateFromRequest($option.'.queries.filter_order',		'filter_order',		'title',	'string');
		$filter_order_Dir	= $mainframe->getUserStateFromRequest($option.'.queries.filter_order_Dir',	'filter_order_Dir',	'',			'word');
		$search				= $mainframe->getUserStateFromRequest($option.'.queries.search',			'search',			'',			'string');

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		// search filter
		$lists['search']= $search;

		$this->assignRef('lists',		$lists);
		$this->assignRef('items',		$this->get('Data'));
		$this->assignRef('pagination',	$this->get('Pagination'));

		parent::display($tpl);
	}
}