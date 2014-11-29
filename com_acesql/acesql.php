<?php
/**
* @version		1.0.0
* @package		AceSQL
* @subpackage	AceSQL
* @copyright	2009-2012 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

if (version_compare(JVERSION,'1.6.0','ge')) {
	if (!JFactory::getUser()->authorise('core.manage', 'com_acesql')) {
		return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
	}
}

require_once(JPATH_COMPONENT.'/controller.php');
require_once(JPATH_COMPONENT.'/helpers/helper.php');
JTable::addIncludePath(JPATH_COMPONENT.'/tables');

// Require specific controller if requested
if ($controller = JRequest::getWord('controller')) {
	$path = JPATH_COMPONENT.'/controllers/'.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	}
	else {
		$controller = '';
	}
}

$classname = 'AcesqlController'.ucfirst($controller);

// Create the controller
$controller = new $classname();

$controller->execute(JRequest::getCmd('task'));
$controller->redirect();

echo '<div style="margin: 10px; text-align: center;"><a href="http://www.joomace.net/joomla-extensions/acesql" target="_blank">AceSQL | Copyright &copy; 2009-2012 JoomAce LLC</a></div>';
