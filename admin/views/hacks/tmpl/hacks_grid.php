<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V1.5.2   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		0.4.0
* @package		Jhacks
* @subpackage	Hacks
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
<div id="file_import" class="reveal-modal">
<p style="text-align: center; margin-top: 15px;">
	<input size="40" type="file" name="file"><br /><br />
	<a id="import_button" href="#" class="jhacks_button green"><?php echo JText::_("JTOOLBAR_IMPORT"); ?></a>
</p>
	<a class="close-reveal-modal"><span style="font-size: 25px; color: #000000;">&#215;</span></a>
</div>
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
			<th>
				<?php echo JText::_("JTOOLBAR_EDIT"); ?>
			</th>
			<?php endif; ?>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_NAME", 'a.name', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_JOOMLA_VERSION", 'a.joomla_version', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_JHACKS_VERSION", 'a.jks_version', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_HACK_VERSION", 'a.hack_version', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_ELEMENT", 'a.element', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_TYPE", 'a.type', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_CLIENT", 'a.client', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_ELEMENT_VERSION", 'a.element_version', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_CONTACTS", 'a.author', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_UPDATES", 'a.updates', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th style="text-align:center">
				<?php echo JText::_("JHACKS_FIELD_TOTAL_ITEMS"); ?>
			</th>
			
			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_ACCESS", 'a.access', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<?php if ($this->access->get('core.edit') || $this->access->get('core.edit.state')): ?>
			<th class="order" style="text-align:center">
				<?php echo JHTML::_('grid.sort',  'Order', 'a.ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				<?php echo JDom::_('html.grid.header.saveorder', array('list' => $this->items));?>
			</th>
			<?php endif; ?>

			<?php if ($this->access->get('core.edit.state') || $this->access->get('core.view.own')): ?>
			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_PUBLISH", 'a.publish', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>
			<?php endif; ?>

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

$tooltip = '<table class="tooltip_process">
			<tr>
				<td class="key">'. JText::_("JHACKS_FIELD_DESCRIPTION") .':</td>
				<td class="value">'. $row->description .'</td>
			</tr>
			<tr>
				<td class="key">'. JText::_("JHACKS_FIELD_WARNINGS") .':</td>
				<td class="value">'. $row->warnings .'</td>
			</tr>
			<tr>
				<td class="key">'. JText::_("JHACKS_FIELD_COPYRIGHT") .':</td>
				<td class="value">'. $row->copyright .'</td>
			</tr>
			<tr>
				<td class="key">'. JText::_("JHACKS_FIELD_LICENSE") .':</td>
				<td class="value">'. $row->license .'</td>
			</tr>
			<tr>
				<td class="key">'. JText::_("JHACKS_FIELD_DATE_ADDED") .':</td>
				<td class="value">'. date ("d M Y", $row->date_added) .'</td>
			</tr>
			<tr>
				<td class="key">'. JText::_("JHACKS_FIELD_LAST_UPDATE") .':</td>
				<td class="value">'. date ("d M Y", $row->last_updates) .'</td>
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
            <td>
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
												'dataKey' => 'name',
												'dataObject' => $row
												));
				?>
			</td>

            <td style="text-align:left">
				<div class="hack_desc">
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'description',
												'dataObject' => $row
												));
				?>
				</div>
			</td>

            <td style="text-align:center">
				<?php echo JDom::_('html.fly.enum', array(
												'dataKey' => 'joomla_version',
												'dataObject' => $row,
												'list' => $this->lists['enum']['hacks.joomla_version'],
												'listKey' => 'value',
												'labelKey' => 'text'
												));
				?>
			</td>

            <td style="text-align:center">
				<?php echo JDom::_('html.fly.enum', array(
												'dataKey' => 'jks_version',
												'dataObject' => $row,
												'list' => $this->lists['enum']['hacks.jks_version'],
												'listKey' => 'value',
												'labelKey' => 'text'
												));
				?>
			</td>

            <td style="text-align:center">
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'hack_version',
												'dataObject' => $row
												));
				?>
			</td>

            <td style="text-align:center">
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'element',
												'dataObject' => $row
												));
				?>
			</td>

            <td style="text-align:center">
				<?php echo JDom::_('html.fly.enum', array(
												'dataKey' => 'type',
												'dataObject' => $row,
												'list' => $this->lists['enum']['hacks.type'],
												'listKey' => 'value',
												'labelKey' => 'text'
												));
				?>
			</td>

            <td style="text-align:center">
				<?php echo JDom::_('html.fly.enum', array(
												'dataKey' => 'client',
												'dataObject' => $row,
												'list' => $this->lists['enum']['hacks.client'],
												'listKey' => 'value',
												'labelKey' => 'text'
												));
				?>
			</td>

            <td style="text-align:center">
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'element_version',
												'dataObject' => $row
												));
				?>
			</td>

            <td style="text-align:center">
				<?php echo $row->author;?>:<br />
				<?php echo $row->author_website;?><br />
				<?php echo $row->author_email;?>
				
			</td>

            <td style="text-align:center">
				<table style="width: 100%;">
					<tr>
						<td><a href="index.php?option=com_jhacks&view=operations"><?php echo JText::_("JHACKS_LAYOUT_OPERATIONS"); ?></a>:</td>
						<td><?php echo $row->total_operations; ?></td>
					</tr>
				</table>
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
            <td class="order" style="text-align:center">
				<?php echo JDom::_('html.grid.ordering', array(
										'dataKey' => 'ordering',
										'dataObject' => $row,
										'num' => $i,
										'ordering' => $this->state->get('list.ordering'),
										'direction' => $this->state->get('list.direction'),
										'list' => $this->items,
										'ctrl' => 'hacks',
										'pagination' => $this->pagination
											));
				?>
			</td>
			<?php endif; ?>

			<?php if ($this->access->get('core.edit.state') || $this->access->get('core.view.own')): ?>
            <td style="text-align:center">
				<?php echo JDom::_('html.grid.publish', array(
										'dataKey' => 'publish',
										'dataObject' => $row,
										'num' => $i
											));
				?>
			</td>
			<?php endif; ?>

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


