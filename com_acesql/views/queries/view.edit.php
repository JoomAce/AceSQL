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


class AcesqlViewQueries extends JaView {

	public function display($tpl = null) {
		$document =& JFactory::getDocument();
		$document->addStyleSheet('components/com_acesql/assets/css/acesql.css');
		
		// Toolbar
		JToolBarHelper::title(JText::_('AceSQL'), 'acesql');
		JToolBarHelper::save();
		JToolBarHelper::cancel();

		$this->assignRef('row', $this->get('QueryData'));
		
		parent::display($tpl);
	}
}