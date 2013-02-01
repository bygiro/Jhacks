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

require_once(JPATH_ADMIN_JHACKS .DS. "classes" .DS. "upload.php");


$isNew		= ($this->operation->id < 1);
$actionText = $isNew ? JText::_( "JHACKS_NEW" ) : JText::_( "JHACKS_EDIT" );
?>
	<?php  ?>
		<div id="browsebox" class="reveal-modal">
			<div id="filetreebrowser">
				<div class="fileselected_container"><?php echo JText::_( "JHACKS_FIELD_TARGET" ); ?>: <span class="itemselected" id="fileselected"></span><a id="button_searchkey" class="jhacks_button green" href="#" onclick="loadFile();"><?php echo JText::_( "JHACKS_SELECT_CODE_FROM_FILE" ); ?></a></div><br />
				<div style="margin-left:20px; overflow:auto; height:500px;" id="filenameTree"></div>
			</div>
			<div id="sourcecode">
				<div id="keyselected" class="fileselected_container">
					<span id="selectinfo"><?php echo JText::_( "JHACKS_SELECT_KEY" ); ?><br /><br /><?php echo JText::_( "JHACKS_FILENAME" ); ?>:<span class="itemselected" id="file_name"></span></span>
					<span id="copypasteinfo"><a class="jhacks_button green close-reveal-modal"><?php echo JText::_( "JHACKS_COPY_DETECTED" ); ?></a>
					</span>
					<a class="jhacks_button blue" href="javascript: browse();"><?php echo JText::_( "JHACKS_UPLOAD_PLEASE_BROWSE_A_FILE" ); ?></a>
				</div>
				<div style="font-size: 14px; padding-top:0; overflow:auto; height:500px;" class="m">
					<div id="filecontent"></div>
				</div>									
			</div>
			<a class="close-reveal-modal"><span style="font-size: 25px; color: #000000;">&#215;</span></a>
		</div>	
	<?php  ?>
<fieldset class="fieldsform">
	<legend><?php echo $actionText;?></legend>

	<table class="admintable jhacks">

		<?php if ($this->access->get('core.edit')): ?>
		<tr>
			<td align="right" class="key">
				<label for="access">
					<?php echo JText::_( "JHACKS_FIELD_ACCESS" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.accesslevel', array(
												'dataKey' => 'access',
												'dataObject' => $this->operation,
												'aclAccess' => '',
												'domClass' => "validate[custom[numeric]]",
												'validatorHandler' => "numeric"
												));
				?>
			</td>
		</tr>

		<?php endif; ?>
		<?php if ($this->access->get('core.edit') || $this->access->get('core.edit.state')): ?>
		<tr>
			<td align="right" class="key">
				<label for="ordering">
					<?php echo JText::_( "JHACKS_FIELD_ORDERING" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.ordering', array(
												'dataKey' => 'ordering',
												'dataObject' => $this->operation,
												'items' => $this->lists["ordering"],
												'labelKey' => 'hack_id',
												'aclAccess' => '',
												'domClass' => "validate[custom[numeric]]",
												'validatorHandler' => "numeric"
												)); ?>
			</td>
		</tr>

		<?php endif; ?>
		<tr>
			<td align="right" class="key">
				<label for="hack_id">
					<?php echo JText::_( "JHACKS_FIELD_HACK_NAME" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.ajax', array(
												'dataKey' => 'hack_id',
												'dataObject' => $this->operation,
												'ajaxContext' => 'jhacks.hacks.ajax.select1',
												'ajaxVars' => array('values' => array(
													$this->operation->hack_id
														))
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="op_name">
					<?php echo JText::_( "JHACKS_FIELD_NAME" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.text', array(
												'dataKey' => 'op_name',
												'dataObject' => $this->operation,
												'size' => "32",
												'domClass' => "validate[required]",
												'required' => true
												));

				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="op_description">
					<?php echo JText::_( "JHACKS_FIELD_DESCRIPTION" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.text', array(
												'dataKey' => 'op_description',
												'dataObject' => $this->operation,
												'size' => "80"
												));
				?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table>
					<tr>
						<td align="right" class="key">
							<label for="type">
								<?php echo JText::_( "JHACKS_FIELD_TYPE" ); ?> :
							</label>
						</td>
						<td>
							<?php echo JDom::_('html.form.input.select', array(
												'dataKey' => 'type',
												'dataObject' => $this->operation,
												'list' => $this->lists['select']['type']->list,
												'listKey' => 'value',
												'labelKey' => 'text',
												'nullLabel' => "",
												'domClass' => "validate[required]",
												'required' => true
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<div class="replace_type_row">
					<label for="replace_type">
						<?php echo JText::_( "JHACKS_FIELD_REPLACE_TYPE" ); ?> :
					</label>
				</div>
			</td>
			<td>
				<div class="replace_type_row">
				<?php echo JDom::_('html.form.input.select', array(
												'dataKey' => 'replace_type',
												'dataObject' => $this->operation,
												'list' => $this->lists['select']['replace_type']->list,
												'listKey' => 'value',
												'labelKey' => 'text',
												'nullLabel' => ""
												));
				?>
				</div>
			</td>
			<td align="right" class="key">
				<div class="regex_row">
					<label for="regex">
						<?php echo JText::_( "JHACKS_FIELD_REGEX" ); ?> :
					</label>
				</div>
			</td>
			<td>
						<div class="regex_row">
				<?php echo JDom::_('html.form.input.bool', array(
												'dataKey' => 'regex',
												'dataObject' => $this->operation,
												'domClass' => "validate[required]",
												'required' => true
												));
				?>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tbody class="search_key_row">
		<tr>
			<td align="right" class="key">
				<label for="search_key">
				<a class="jhacks_button blue" href="#" onclick="browse();" data-reveal-id="browsebox"><?php echo JText::_( "JHACKS_UPLOAD_PLEASE_BROWSE_A_FILE" ); ?></a>			
					<?php echo JText::_( "JHACKS_FIELD_SEARCH_KEY" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.textarea', array(
												'dataKey' => 'search_key',
												'dataObject' => $this->operation,
												'domClass' => "textcontent",
												'cols' => "80",
												'rows' => "10"
												));
				?>
			</td>
		</tr>
		</tbody>
		<tbody class="replacement_row">
		<tr>
			<td align="right" class="key">
					<label id="rep_lab" for="replacement">
					<?php echo JText::_( "JHACKS_FIELD_REPLACEMENT" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.textarea', array(
												'dataKey' => 'replacement',
												'dataObject' => $this->operation,
												'domClass' => "textcontent",
												'cols' => "80",
												'rows' => "10"
												));
				?>
			</td>
		</tr>
		</tbody>
		<tbody class="data_file_row">
		<tr>
			<td align="right" class="key">
				<label for="data_file">
					<?php echo JText::_( "JHACKS_FIELD_DATA_FILE" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.file', array(
												'dataKey' => 'data_file',
												'dataObject' => $this->operation,
												'size' => "",
												'uploadMaxSize' => JhacksUpload::getMaxSize(true)
												));
				?>
			</td>
		</tr>
		</tbody>
		<tbody class="sql_row">
		<tr>
			<td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px; color: green;">
			coming soon!
			</td>
		</tr>
		</tbody>
	</table>
</fieldset>
