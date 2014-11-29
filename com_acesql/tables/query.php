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

class TableQuery extends JTable {

	public $id					= 0;
	public $title				= '';
	public $query				= '';

	public function __construct(&$db) {
		parent::__construct('#__acesql_queries', 'id', $db);
	}
}