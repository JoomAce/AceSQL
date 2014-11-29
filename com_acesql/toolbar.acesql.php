<?php
/**
* @version		1.5.0
* @package		AceSEF
* @subpackage	AceSEF
* @copyright	2009-2010 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.switcher');

JSubMenuHelper::addEntry(JText::_('COM_ACESQL_RUN_QUERY'), 'index.php?option=com_acesql', false);
JSubMenuHelper::addEntry(JText::_('COM_ACESQL_SAVED_QUERIES'), 'index.php?option=com_acesql&controller=queries', false);