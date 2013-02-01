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

<fieldset class="fieldsfly">
	<table class="admintable">

		<tr>
			<td align="right" class="key">
				<label for="pro_publish">
					<?php echo JText::_( "JHACKS_FIELD_PROCESS_PUBLISH" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly.bool', array(
												'dataKey' => 'pro_publish',
												'dataObject' => $this->process
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="id">
					<?php echo JText::_( "JHACKS_FIELD_ID" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'id',
												'dataObject' => $this->process
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="_pro_operation_id_op_name">
					<?php echo JText::_( "JHACKS_FIELD_OPERATION_NAME_1" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly', array(
												'dataKey' => '_pro_operation_id_op_name',
												'dataObject' => $this->process
												));
				?>
			</td>
		</tr>
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
				<label for="target_folder">
					<?php echo JText::_( "JHACKS_FIELD_TARGET_FOLDER" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'target_folder',
												'dataObject' => $this->process
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="target">
					<?php echo JText::_( "JHACKS_FIELD_TARGET" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'target',
												'dataObject' => $this->process
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="occurrences">
					<?php echo JText::_( "JHACKS_FIELD_OCCURRENCES" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'occurrences',
												'dataObject' => $this->process
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="conflicts">
					<?php echo JText::_( "JHACKS_FIELD_CONFLICTS" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'conflicts',
												'dataObject' => $this->process
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="original_md">
					<?php echo JText::_( "JHACKS_FIELD_ORIGINAL_MD5" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'original_md',
												'dataObject' => $this->process
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="modified_md">
					<?php echo JText::_( "JHACKS_FIELD_MODIFIED_MD5" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'modified_md',
												'dataObject' => $this->process
												));
				?>
			</td>
		</tr>


	</table>
</fieldset>