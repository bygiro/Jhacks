<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V1.5.2   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		0.4.0
* @package		Jhacks
* @subpackage	Processes
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
	<table id='grid' class='adminlist jhacks' cellpadding="0" cellspacing="0">
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

			<th width="30" style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "ID", 'a.id', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<?php if ($this->access->get('core.edit.own') || $this->access->get('core.edit')): ?>
			<th width="20" style="text-align:center">
				<?php echo JText::_("JTOOLBAR_EDIT"); ?>
			</th>
			<?php endif; ?>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_TARGET", 'a.target_folder', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th width="30" style="text-align:center">
				<?php echo JText::_("JHACKS_FIELD_OCCURRENCES"); ?>
			</th>

			<th style="text-align:left">
				<?php echo JText::_("JHACKS_FIELD_CONFLICTS"); ?>
			</th>

			<th style="text-align:center">
				<?php echo JText::_("JHACKS_LIVE"); ?>
			</th>

			<th style="text-align:center">
				<?php echo JText::_("JHACKS_BACKUP"); ?>
			</th>

			<?php if ($this->access->get('core.edit.state') || $this->access->get('core.view.own')): ?>
			<th width="30" style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "JHACKS_FIELD_PROCESS_PUBLISH", 'a.pro_publish', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>
			<?php endif; ?>

			<?php if ($this->access->get('core.edit') || $this->access->get('core.edit.state')): ?>
			<th class="order" style="text-align:center">
				<?php echo JHTML::_('grid.sort',  'Order', 'a.ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				<?php echo JDom::_('html.grid.header.saveorder', array('list' => $this->items));?>
			</th>
			<?php endif; ?>

			<?php if ($this->access->get('core.delete.own') || $this->access->get('core.delete')): ?>
			<th width="30" style="text-align:center">
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
				<td class="key">'. JText::_("JHACKS_FIELD_OPERATION_ID") .':</td>
				<td class="value">'. $row->pro_operation_id .'</td>
			</tr>
			<tr>
				<td class="key">'. JText::_("JHACKS_FIELD_OPERATION_NAME") .':</td>
				<td class="value">'. $row->_pro_operation_id_op_name .'</td>
			</tr>
			<tr>
				<td class="key">'. JText::_("JHACKS_FIELD_OPERATION_DESCRIPTION") .':</td>
				<td class="value">'. $row->_pro_operation_id_op_description .'</td>
			</tr>
			<tr>
				<td class="key">'. JText::_("JHACKS_FIELD_TYPE") .':</td>
				<td class="value">'. $row->_pro_operation_id_type .'</td>
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

            <td style="text-align:center">
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
			<a id="target_<?php echo $row->id ?>" href="#" data-reveal-id="sourcecode_<?php echo $row->id ?>" onclick="loadFile(<?php echo $row->id ?>);">
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'pro_target',
												'dataObject' => $row
												));
				?>
			</a>	
	<?php  ?>
		<div class="reveal-modal targetcode" id="sourcecode_<?php echo $row->id ?>">
			<div class="fileselected_container">
				<?php echo JText::_( "JHACKS_FILENAME" ); ?>:<span class="itemselected" id="file_name_<?php echo $row->id ?>"></span>
			</div>
			<div style="font-size: 14px; padding-top:0; overflow:auto; height:500px;" class="m">
				<div id="filecontent_<?php echo $row->id ?>"></div>
			</div>
			<a class="close-reveal-modal"><span style="font-size: 25px; color: #000000;">&#215;</span></a>
		</div>
	<?php  ?>
			</td>

            <td style="text-align:center">
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'occurrences',
												'dataObject' => $row
												));
				?>
			</td>
			<?php 
			$con_class = '';
			if ($row->conflicts){$con_class = 'class="jhacks_conflicts"';}?>
            <td <?php echo $con_class; ?> style="text-align:left">
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'conflicts',
												'dataObject' => $row
												));
				?>
			</td>

	<?php 
	switch ($row->verify->live){		
		case 1:
			$live = "<span class=\"verify_error\">missing!</span>";
		break;
		
		case 2:
			$live = "<span class=\"verify_ok\">OK</span>";
		break;
		
		case 3:
			$live = "<span class=\"verify_error\">modified</span>";
		break;
		
		case 4:
			// $live = "<span class=\"verify_error\">modified</span><br /><a onclick=\"restore(); listItemTask('cb". $i ."', 'unpublish'); return false; \" href=\"#\">restore it</a>";
			$live = "<span class=\"verify_error\">modified</span><br /><a onclick=\"restore('cb". $i ."'); return false;\" href=\"#\">restore it</a>";
		break;
		
		default :
			$live = '';
		break;
	}
	
	switch ($row->verify->backup){		
		case 1:
			$backup = "<span class=\"verify_error\">missing!</span>";
		break;
		
		case 2:
			$backup = "<span class=\"verify_ok\">OK</span>";
		break;
		
		case 3:
			$backup = "<span class=\"verify_error\">modified</span>";
		break;
		
		default :
			$backup = '';
		break;
	}
	
	?>
            <td width="60">
				<?php echo $live; ?>
			</td>
			<td width="60">	
				<?php echo $backup; ?>
			</td>
	<?php  ?>
	
			<?php if ($this->access->get('core.edit.state') || $this->access->get('core.view.own')): ?>
            <td style="text-align:center">
				<?php echo JDom::_('html.grid.publish', array(
										'dataKey' => 'pro_publish',
										'dataObject' => $row,
										'num' => $i
											));
				?>
			</td>
			<?php endif; ?>

			<?php if ($this->access->get('core.edit') || $this->access->get('core.edit.state')): ?>
            <td class="order" style="text-align:center">
				<?php echo JDom::_('html.grid.ordering', array(
										'dataKey' => 'ordering',
										'dataObject' => $row,
										'num' => $i,
										'ordering' => $this->state->get('list.ordering'),
										'direction' => $this->state->get('list.direction'),
										'list' => $this->items,
										'ctrl' => 'processes',
										'pagination' => $this->pagination
											));
				?>
			</td>
			<?php endif; ?>

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


