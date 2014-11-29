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
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class AcesqlControllerAcesql extends AcesqlController {

    public function __construct($default = array()) {
		parent::__construct($default);

        $this->_model = $this->getModel('acesql');
	}

    public function run() {
   		// Check for request forgeries
   		//JRequest::checkToken() or jexit('Invalid Token');

		JRequest::setVar('view', 'acesql');

		parent::display();
   	}
	
    public function csv() {
        ob_end_clean();

        $file_name = 'export_'.$this->_table.'.csv';

        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Accept-Ranges: bytes');
        header('Content-Disposition: attachment; filename='.basename($file_name).';');
        header('Content-Type: text/plain; '._ISO);
        header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Pragma: no-cache');

        echo $this->_model->exportToCsv($this->_query);

        jexit();
    }

    public function delete() {
   		// Check for request forgeries
   		//JRequest::checkToken() or jexit('Invalid Token');

        if ($this->_model->delete($this->_table)) {
            $msg = JText::_('COM_ACESQL_DELETE_TRUE');
        }
        else {
            $msg = JText::_('COM_ACESQL_DELETE_FALSE');
        }
		
		$vars = 'ja_tbl_g='.base64_encode($this->_table).'&ja_qry_g='.base64_encode($this->_query);

   		$this->setRedirect('index.php?option=com_acesql&'.$vars, $msg);
   	}

    public function saveQuery() {
   		// Check for request forgeries
   		//JRequest::checkToken() or jexit('Invalid Token');
        JFactory::getSession()->set('acesql.query', base64_encode($this->_query));
   		$this->setRedirect('index.php?option=com_acesql&controller=queries&task=edit');
   	}
}