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

class AcesqlControllerQueries extends AcesqlController {

    public function __construct($default = array()) {
		parent::__construct($default);

        $this->_model = $this->getModel('Queries');
	}
	
    /*public function view() {
   		$view = $this->getView(ucfirst('Queries'), 'html');
		$view->setModel($this->_model, true);
		$view->view();
   	}*/

    public function edit() {
        JRequest::setVar('hidemainmenu', 1);

        $view = $this->getView(ucfirst('Queries'), 'edit');
        $view->setModel($this->_model, true);
        $view->display('edit');
   	}

    public function save() {
   		// Check for request forgeries
   		JRequest::checkToken() or jexit('Invalid Token');

        $post = JRequest::get('post');
        $post['query'] = base64_encode(JRequest::getVar('ja_query', '', 'post', 'string', JREQUEST_ALLOWRAW));
        unset($post['ja_query']);

        if ($this->_model->saveQuery($post)) {
            $msg = JText::_('COM_ACESQL_SAVE_TRUE');
        }
        else {
            $msg = JText::_('COM_ACESQL_SAVE_FALSE');
        }

   		$this->setRedirect('index.php?option=com_acesql&controller=queries', $msg);
   	}

    public function cancel() {
   		// Check for request forgeries
   		JRequest::checkToken() or jexit('Invalid Token');

   		$this->setRedirect('index.php?option=com_acesql&controller=queries');
   	}

    public function remove() {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        $cid = JRequest::getVar('cid', array(), '', 'array');

        JArrayHelper::toInteger($cid);
        $msg = '';

        for ($i=0, $n=count($cid); $i < $n; $i++) {
            $query =& JTable::getInstance('Query', 'Table');

            if (!$query->delete($cid[$i])) {
                $msg .= $query->getError();
                $tom = "error";
            }
            else {
                $msg = JTEXT::_('COM_ACESQL_QUERY_DELETED');
                $tom = "";
            }
        }

        $this->setRedirect('index.php?option=com_acesql&controller=queries', $msg, $tom);
    }
}