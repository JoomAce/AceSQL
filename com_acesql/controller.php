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

jimport('joomla.application.component.controller');

if (version_compare(JVERSION, '3.0', 'ge'))
{

	if (!class_exists('JaController')) {
		if (interface_exists('JController')) {
			abstract class JaController extends JControllerLegacy {}
		}
	}
}
else{

	class JaController extends JController {}

}


class AcesqlController extends JaController {

    public $_db = null;
    public $_query = null;
    public $_table = null;
    public $_model = null;

    public function __construct($default = array()) {
		parent::__construct($default);

        $this->_db = JFactory::getDBO();

        $this->_table = AcesqlHelper::getVar('tbl');
        $this->_query = AcesqlHelper::getVar('qry');

		$this->registerTask('add', 'edit');
		$this->registerTask('new', 'edit');
	}

    public function display() {
		$controller = JRequest::getWord('controller', 'acesql');
		JRequest::setVar('view', $controller);

		parent::display();
	}
	
    public function edit() {
        JRequest::setVar('hidemainmenu', 1);
		JRequest::setVar('view', 'edit');
		JRequest::setVar('edit', true);

		parent::display();
	}
}