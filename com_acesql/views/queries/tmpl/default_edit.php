<?php
/**
* @version		1.0.0
* @package		AceSQL
* @subpackage	AceSQL
* @copyright	2009-2012 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted access');
?>

<form action="index.php?option=com_acesql&amp;controller=queries&amp;task=save&cid[]=<?php echo $this->row->id; ?>" method="post" name="adminForm" id="adminForm">
    <fieldset class="adminform">
        <legend><?php echo JText::_('COM_ACESQL_QUERY_DETAILS'); ?></legend>
        <table class="admintable">
            <tr>
                <td width="20%" class="key">
                    <label for="name">
                        <?php echo JText::_('COM_ACESQL_TITLE'); ?>
                    </label>
                </td>
                <td width="80%">
                    <input class="inputbox" type="text" id="title" name="title" size="50" value="<?php echo $this->row->title; ?>" />
                </td>
            </tr>
            <tr>
                <td width="20%" class="key">
                    <label for="name">
                        <?php echo JText::_('COM_ACESQL_QUERY'); ?>
                    </label>
                </td>
                <td width="80%">
                    <textarea class="text_area" id="ja_query" name="ja_query" style="width:100%;height:70px;"><?php echo $this->row->query; ?></textarea>
                </td>
            </tr>
            </tr>
        </table>
    </fieldset>
    <input type="hidden" name="option" value="com_acesql" />
    <input type="hidden" name="controller" value="queries" />
    <input type="hidden" name="task" value="save" />
    <input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
    <?php echo JHTML::_('form.token'); ?>
</form>