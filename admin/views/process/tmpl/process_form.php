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



$isNew		= ($this->process->id < 1);
$actionText = $isNew ? JText::_( "JHACKS_NEW" ) : JText::_( "JHACKS_EDIT" );
?>

<fieldset class="fieldsform">
	<legend><?php echo $actionText;?></legend>

	<table class="admintable">

		<?php if ($this->access->get('core.edit') || $this->access->get('core.edit.state')): ?>
		<tr>
			<td align="right" class="key">
				<label for="ordering">
					<?php echo JText::_( "JHACKS_FIELD_ORDERING" ); ?> :
				</label>
			</td>
			<td colspan="2">
				<?php echo JDom::_('html.form.input.ordering', array(
												'dataKey' => 'ordering',
												'dataObject' => $this->process,
												'items' => $this->lists["ordering"],
												'labelKey' => 'pro_operation_id',
												'aclAccess' => '',
												'domClass' => "validate[custom[numeric]]",
												'validatorHandler' => "numeric"
												)); ?>
			</td>
		</tr>

		<?php endif; ?>
		<tr>
			<td align="right" class="key">
				<label for="pro_operation_id">
					<?php echo JText::_( "JHACKS_FIELD_OPERATION_NAME" ); ?> :
				</label>
			</td>
			<td colspan="2">
				<?php echo JDom::_('html.form.input.ajax', array(
												'dataKey' => 'pro_operation_id',
												'dataObject' => $this->process,
												'ajaxContext' => 'jhacks.operations.ajax.select2',
												'domClass' => "validate[required]",
												'required' => true,
												'ajaxVars' => array('values' => array(
													$this->process->pro_operation_id
														))
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
				<a class="jhacks_button blue" href="#" data-reveal-id="browsebox"><?php echo JText::_( "JHACKS_UPLOAD_PLEASE_BROWSE_A_FILE" ); ?></a>
				<div id="browsebox" class="reveal-modal">
					<div class="fileselected_container"><?php echo JText::_( "JHACKS_FIELD_TARGET" ); ?>: <span class="itemselected" id="fileselected"></span></div><br />
					<div style="margin-left:20px; overflow:auto; height:500px;" id="filenameTree"></div>
					<a class="close-reveal-modal"><span style="font-size: 25px; color: #000000;">&#215;</span></a>
				</div>			
			</td>			
			<td>
				<?php echo JDom::_('html.form.input.text', array(
												'dataKey' => 'target',
												'dataObject' => $this->process,
												'size' => "80",
												'domClass' => "validate[required]",
												'required' => true
												));
				?>
			</td>
		</tr>


	</table>
</fieldset>
