<?php
/**
* @version		1.0.0
* @package		AceSQL
* @subpackage	AceSQL
* @copyright	2009-2012 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php?option=com_acesql&amp;controller=queries" method="post" name="adminForm" id="adminForm">
	<table>
		<tr>
			<td align="left" width="100%">
				<?php echo JText::_('Filter'); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
				<button onclick="document.getElementById('search').value='';value='';this.form.submit();"><?php echo JText::_('Reset'); ?></button>
			</td>
		</tr>
	</table>
	
	<table class="adminlist table">
		<thead>
			<tr>
				<th width="5">
					<?php echo JText::_('#'); ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
				</th>
                <th width="1%" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', JText::_('ID'), 'id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
                </th>
                <th width="1%" nowrap="nowrap">
                    <?php echo JText::_('COM_ACESQL_RUN'); ?>
                </th>
				<th width="15%">
					<?php echo JHTML::_('grid.sort', JText::_('COM_ACESQL_TITLE'), 'title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th class="title">
					<?php echo JText::_('COM_ACESQL_QUERY'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$k = 0;
		$n=count($this->items);
		
		for ($i=0; $i < $n; $i++) {
			$row = &$this->items[$i];

			$edit_link = JRoute::_('index.php?option=com_acesql&controller=queries&task=edit&cid[]='.$row->id);
            $run_link = JRoute::_('index.php?option=com_acesql&k='.$i);
            JFactory::getSession()->set('acesql.query'.$i, $row->query);

            $checked = JHTML::_('grid.id', $i, $row->id);
		?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $this->pagination->getRowOffset($i); ?>
				</td>
				<td>
					<?php echo $checked; ?>
				</td>
                <td align="center">
                    <?php echo $row->id; ?>
                </td>
                <td align="center">
                    <a href="<?php echo $run_link; ?>"><img src="components/com_acesql/assets/images/icon-16-run.png" width="16px" height="16px" style="vertical-align:middle;" alt="<?php echo JText::_('COM_ACESQL_RUN_QUERY'); ?>" title="<?php echo JText::_('COM_ACESQL_RUN_QUERY'); ?>" /> </a>
                </td>
                <td>
					<a href="<?php echo $edit_link; ?>"><?php echo $row->title; ?></a>
				</td>
				<td>
					<?php echo base64_decode($row->query);?>
				</td>
			</tr>
			<?php
				$k = 1 - $k;
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="6">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>

	<input type="hidden" name="option" value="com_acesql" />
	<input type="hidden" name="controller" value="queries" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />

	<?php echo JHTML::_('form.token'); ?>
</form>