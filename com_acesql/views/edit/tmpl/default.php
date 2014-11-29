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

//No Permision
defined('_JEXEC') or die('Restricted access');

?>

<form id="adminForm" name="adminForm" action="index.php?option=com_acesql" method="post">
	<table class="adminlist table">
		<?php
		$k = 0;
		foreach ($this->fields as $field => $type) {
		?>
		<tr valign="top" class="row<?php echo $k;?>">
			<td width="20%" class="key">
				<?php echo $field;?>: <?php echo $this->key == $field ? "<strong>[PK]</strong>" : ""; ?>
			</td>
			<td width="80%">
				<?php
				if (($this->key == $field) && ($this->task == 'edit')) {
					echo $this->id . AcesqlHelper::renderHtml($field, 'hidden', $this->id).' [ '.$type.' ]';
				}
				else {
					if (($this->key == $field) && ($this->task == 'new')) {
						if (is_numeric($this->last_key_vol)) {
							$value = $this->last_key_vol + 1;
						}
						else {
							$value = $this->last_key_vol.'_1';
						}
					}
					else {
						eval($this->fld_value);
					}
					
					echo AcesqlHelper::renderHtml($field, $type, $value).' [ '.$type.' ]';
				}
				?>
			</td>
		</tr>
		<?php
			$k = 1 - $k;
		}
		?>
	</table>
	
	<input type="hidden" name="option" value="com_acesql" />
	<input type="hidden" name="controller" value="edit" />
	<input type="hidden" name="task" value="">
	<input type="hidden" name="id" value="<?php echo $this->id; ?>">
	<input type="hidden" name="key" value="<?php echo $this->key; ?>">
	<input type="hidden" name="ja_qry_p" value="<?php echo $this->query; ?>">
	<input type="hidden" name="ja_tbl_p" value="<?php echo $this->table; ?>">
	
	<?php echo JHTML::_('form.token'); ?>
</form>