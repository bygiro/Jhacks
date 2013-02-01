<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V1.5.2   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		0.4.0
* @package		Jhacks
* @subpackage	Operations
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

			<th>
				<?php echo JText::_("ID"); ?>
			</th>

			<?php if ($this->access->get('core.edit.own') || $this->access->get('core.edit')): ?>
			<th style="text-align:center">
				<?php echo JText::_("JTOOLBAR_EDIT"); ?>
			</th>
			<?php endif; ?>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_NAME", 'a.op_name', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th style="text-align:left">
				<?php echo JText::_("JHACKS_FIELD_DESCRIPTION"); ?>
			</th>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_TYPE", 'a.type', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_REGEX", 'a.regex', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_ACCESS", 'a.access', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<?php if ($this->access->get('core.edit') || $this->access->get('core.edit.state')): ?>
			<th class="order">
				<?php echo JHTML::_('grid.sort',  'Order', 'a.ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				<?php echo JDom::_('html.grid.header.saveorder', array('list' => $this->items));?>
			</th>
			<?php endif; ?>

			<?php if ($this->access->get('core.edit.state') || $this->access->get('core.view.own')): ?>
			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_PUBLISH", 'a.publish', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>
			<?php endif; ?>

			<th style="text-align:center">
				<?php echo JText::_("JHACKS_FIELD_TOTAL_PROCESSES"); ?>
			</th>

			<?php if ($this->access->get('core.delete.own') || $this->access->get('core.delete')): ?>
			<th style="text-align:center">
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

$tooltip = '<table class="tooltip_process">
			<tr>
				<td class="key">'. JText::_("JHACKS_FIELD_HACK_ID") .':</td>
				<td class="value">'. $row->hack_id .'</td>
			</tr>
			<tr>
				<td class="key">'. JText::_("JHACKS_FIELD_HACK_NAME") .':</td>
				<td class="value">'. $row->_hack_id_name .'</td>
			</tr>
			<tr>
				<td class="key">'. JText::_("JHACKS_FIELD_HACK_DESCRIPTION") .':</td>
				<td class="value">'. $row->_hack_id_description .'</td>
			</tr>
		</table>' ;

		?>

		<tr class="<?php echo "row$k"; ?> hasTip" title='<?php echo str_replace('\'', '\\',str_replace('\\', '\\\\',$tooltip)); ?>'>

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
				<?php echo $row->id; ?>
			</td>

			<?php if ($this->access->get('core.edit.own') || $this->access->get('core.edit')): ?>
            <td style="text-align:center">
				<?php if ($row->params->get('access-edit')): ?>
					<?php echo JDom::_('html.grid.task', array(
											'num' => $i,
											'task' => "edit",
											'label' => "JTOOLBAR_EDIT",
											'view'	=> "icon"
												));
					?>
				<?php endif; ?>
			</td>
			<?php endif; ?>

            <td style="text-align:center">
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'op_name',
												'dataObject' => $row
												));
				?>
			</td>

            <td style="text-align:left">
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'op_description',
												'dataObject' => $row
												));
				?>
			</td>

            <td style="text-align:center">
				<?php echo JDom::_('html.fly.enum', array(
												'dataKey' => 'type',
												'dataObject' => $row,
												'list' => $this->lists['enum']['operations.type'],
												'listKey' => 'value',
												'labelKey' => 'text'
												));
				?>
			</td>

            <td style="text-align:center">
				<?php echo JDom::_('html.grid.bool', array(
										'dataKey' => 'regex',
										'dataObject' => $row,
										'num' => $i
											));
				?>
			</td>

            <td style="text-align:center">
				<?php echo JDom::_('html.grid.accesslevel', array(
										'dataKey' => 'access',
										'dataObject' => $row,
										'num' => $i,
										'list' => $this->lists['viewlevels']
											));
				?>
			</td>

			<?php if ($this->access->get('core.edit') || $this->access->get('core.edit.state')): ?>
            <td class="order">
				<?php echo JDom::_('html.grid.ordering', array(
										'dataKey' => 'ordering',
										'dataObject' => $row,
										'num' => $i,
										'ordering' => $this->state->get('list.ordering'),
										'direction' => $this->state->get('list.direction'),
										'list' => $this->items,
										'ctrl' => 'operations',
										'pagination' => $this->pagination
											));
				?>
			</td>
			<?php endif; ?>

			<?php if ($this->access->get('core.edit.state') || $this->access->get('core.view.own')): ?>
            <td style="text-align:center">
			<div class="tog_pub">
				<?php echo JDom::_('html.grid.publish', array(
										'dataKey' => 'publish',
										'dataObject' => $row,
										'num' => $i
											));
				?>
			</div>
			</td>
			<?php endif; ?>

            <td style="text-align:center">
		<?php echo $row->total_processes?>
	    </td>

			<?php if ($this->access->get('core.delete.own') || $this->access->get('core.delete')): ?>
            <td style="text-align:center">
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


