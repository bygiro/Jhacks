<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V1.5.2   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		0.4.0
* @package		Jhacks
* @subpackage	Logs
* @copyright	2012 - Girolamo Tomaselli
* @author		Girolamo Tomaselli - http://bygiro.com - girotomaselli@gmail.com
* @license		GPLv2 or later
*
* /!\  Joomla! is free software.
* This version may have been modified pursuant to the GNU General Public License,
* and as distributed it includes or is derivative of works licensed under the
* GNU General Public License or other free or open source software licenses.
*
*             .oooO  Oooo.     See COPYRIGHT.php for copyright notices and details.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

defined('_JEXEC') or die('Restricted access');


?>


<div class="grid_wrapper">
	<table id='grid' class='adminlist' cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'NUM' ); ?>
			</th>

			<?php if ($this->access->get('core.edit.own') || $this->access->get('core.edit')): ?>
            <th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>
			<?php endif; ?>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_TYPE_ITEM", 'a.type_item', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_TYPE_TASK", 'a.type_task', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_RESULT", 'a.result', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th style="text-align:left">
				<?php echo JText::_("JHACKS_FIELD_DETAILS"); ?>
			</th>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_CREATION_DATE", 'a.lg_creation_date', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<?php if ($this->access->get('core.delete.own') || $this->access->get('core.delete')): ?>
			<th>
				<?php echo JText::_("JTOOLBAR_DELETE"); ?>
			</th>
			<?php endif; ?>



		</tr>
	</thead>

	<tbody>
	<?php
	$k = 0;

	for ($i=0, $n=count( $this->items ); $i < $n; $i++):

		$row = &$this->items[$i];



		?>

		<tr class="<?php echo "row$k"; ?>">

			<td class='row_id'>
				<?php echo $this->pagination->getRowOffset( $i ); ?>
            </td>

			<?php if ($this->access->get('core.edit.own') || $this->access->get('core.edit')): ?>
			<td>
				<?php echo JDom::_('html.grid.checkedout', array(
											'dataObject' => $row,
											'num' => $i
												));
				?>

			</td>
			<?php endif; ?>

            <td>
				<?php echo JDom::_('html.fly.enum', array(
												'dataKey' => 'type_item',
												'dataObject' => $row,
												'list' => $this->lists['enum']['logs.type_item'],
												'listKey' => 'value',
												'labelKey' => 'text'
												));
				?>
			</td>

            <td style="text-align:center">
				<?php echo JDom::_('html.fly.enum', array(
												'dataKey' => 'type_task',
												'dataObject' => $row,
												'list' => $this->lists['enum']['logs.type_task'],
												'listKey' => 'value',
												'labelKey' => 'text',
												'route' => array('view' => 'activity','layout' => 'logactivity','cid[]' => $row->id)
												));
				?>
			</td>

            <td style="text-align:center">
				<?php echo JDom::_('html.fly.enum', array(
												'dataKey' => 'result',
												'dataObject' => $row,
												'list' => $this->lists['enum']['logs.result'],
												'listKey' => 'value',
												'labelKey' => 'text'
												));
				?>
			</td>

            <td style="text-align:left">
				<div class="hack_desc">
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'details',
												'dataObject' => $row
												));
				?>
				</div>
			</td>

            <td style="text-align:center">
				<?php echo date ("d M Y - H:i:s", $row->lg_creation_date); ?>
			</td>

			<?php if ($this->access->get('core.delete.own') || $this->access->get('core.delete')): ?>
            <td>
				<?php if ($row->params->get('access-delete')): ?>
					<?php echo JDom::_('html.grid.task', array(
											'num' => $i,
											'task' => "delete",
											'label' => "JTOOLBAR_DELETE",
											'view'	=> "icon"
												));
					?>
				<?php endif; ?>
			</td>
			<?php endif; ?>



		</tr>
		<?php
		$k = 1 - $k;

	endfor;
	?>
	</tbody>
	</table>




</div>

<?php echo JDom::_('html.pagination', null, $this->pagination);?>


