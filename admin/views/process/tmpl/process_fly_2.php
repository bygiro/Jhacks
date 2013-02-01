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

$visibility = "display: none;"; // controllare 
if ($this->process->pro_operation_id > 0) {
	$visibility = "display: block;";
}
?>

<fieldset class="fieldsfly" style="<?php echo $visibility; ?>">
	<table class="admintable">

		<tr>
			<td align="right" class="key">
				<label for="pro_operation_id">
					<?php echo JText::_( "JHACKS_FIELD_OPERATION_ID" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'pro_operation_id',
												'dataObject' => $this->process
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="_pro_operation_id_ordering">
					<?php echo JText::_( "JHACKS_FIELD_ORDERING" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly', array(
												'dataKey' => '_pro_operation_id_ordering',
												'dataObject' => $this->process
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="_pro_operation_id_access">
					<?php echo JText::_( "JHACKS_FIELD_ACCESS_1" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly.accesslevel', array(
												'dataKey' => '_pro_operation_id_access',
												'dataObject' => $this->process,
												'aclAccess' => 'core.edit.state'
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="_pro_operation_id_publish">
					<?php echo JText::_( "JHACKS_FIELD_PUBLISH" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly.bool', array(
												'dataKey' => '_pro_operation_id_publish',
												'dataObject' => $this->process
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="_pro_operation_id_op_name">
					<?php echo JText::_( "JHACKS_FIELD_NAME" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly', array(
												'dataKey' => '_pro_operation_id_op_name',
												'dataObject' => $this->process,
												'route' => array('view' => 'operation','layout' => 'operation','cid[]' => $this->item->pro_operation_id)
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="_pro_operation_id_hack_id_name">
					<?php echo JText::_( "JHACKS_FIELD_HACK_NAME_1" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly', array(
												'dataKey' => '_pro_operation_id_hack_id_name',
												'dataObject' => $this->process
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="_pro_operation_id_op_description">
					<?php echo JText::_( "JHACKS_FIELD_DESCRIPTION" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly', array(
												'dataKey' => '_pro_operation_id_op_description',
												'dataObject' => $this->process
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="_pro_operation_id_type">
					<?php echo JText::_( "JHACKS_FIELD_TYPE" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly.enum', array(
												'dataKey' => '_pro_operation_id_type',
												'dataObject' => $this->process,
												'list' => $this->lists['enum']['operations.type'],
												'listKey' => 'value',
												'labelKey' => 'text'
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="_pro_operation_id_replace_type">
					<?php echo JText::_( "JHACKS_FIELD_REPLACE_TYPE" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly.enum', array(
												'dataKey' => '_pro_operation_id_replace_type',
												'dataObject' => $this->process,
												'list' => $this->lists['enum']['operations.replace_type'],
												'listKey' => 'value',
												'labelKey' => 'text'
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="_pro_operation_id_regex">
					<?php echo JText::_( "JHACKS_FIELD_REGEX" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly.bool', array(
												'dataKey' => '_pro_operation_id_regex',
												'dataObject' => $this->process
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="_pro_operation_id_search_key">
					<?php echo JText::_( "JHACKS_FIELD_SEARCH_KEY_1" ); ?> :
				</label>
			</td>
			<td>
<textarea class="fly_code" disabled="disabled">			
<?php echo JDom::_('html.fly', array(
												'dataKey' => '_pro_operation_id_search_key',
												'dataObject' => $this->process
												));
				?></textarea>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="_pro_operation_id_replacement">
					<?php echo JText::_( "JHACKS_FIELD_REPLACEMENT" ); ?> :
				</label>
			</td>
			<td>
<textarea class="fly_code" disabled="disabled"><?php echo JDom::_('html.fly', array(
												'dataKey' => '_pro_operation_id_replacement',
												'dataObject' => $this->process
												));
				?></textarea>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="_pro_operation_id_data_file">
					<?php echo JText::_( "JHACKS_FIELD_DATA_FILE_1" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly.file.default', array(
												'dataKey' => '_pro_operation_id_data_file',
												'dataObject' => $this->process,
												'width' => 80,
												'height' => 'auto',
												'attrs' => array('fit'),
												'indirect' => true,
												'root' => '[DIR_OPERATIONS_DATA_FILE]'
												));
				?>
			</td>
		</tr>


	</table>
</fieldset>