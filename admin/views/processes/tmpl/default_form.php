<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		Jhacks
* @subpackage	Processes
* @copyright	2012 - Girolamo Tomaselli
* @author		Girolamo Tomaselli - http://bygiro.com - girotomaselli@gmail.com
* @license		GNU/GPL
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
<fieldset class="fieldsform">
<table class="admintable jhacks">
		<tr>
			<td align="right" class="key">
				<label for="op_name">
					<?php echo JText::_( "JHACKS_FIELD_NAME" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.select', array(
												'dataKey' => 'operation_id',
												'dataObject' => $this->process,
												'list' => $this->lists['fk']['pro_operation_id'],
												'listKey' => 'id',
												'labelKey' => 'op_name',
												'nullLabel' => "JHACKS_JSEARCH_SELECT_OPERATION_ID",
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
			<input id="target" class="inputbox" type="text" size="100" value="" name="target"><a class="jhacks_button blue" href="javascript: browse('0');"><?php echo JText::_( "JHACKS_UPLOAD_PLEASE_BROWSE_A_FILE" ); ?></a>	
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
				<label for="all_subfolders">
					<?php echo JText::_( "JHACKS_FIELD_ALL_SUBFOLDERS" ); ?> :
				</label>
			</div>
		</td>
		<td>
			<fieldset id="all_subfolders" class="radio radio_wrapper inputbox " style="float:left;">
				<input id="all_subfolders_0" checked="checked" type="radio" value="0" name="all_subfolders"><label for="all_subfolders_0">No</label>
				<input id="all_subfolders_1" type="radio" value="1" name="all_subfolders"><label for="all_subfolders_1">Yes</label>
			</fieldset>			
			<?php echo JText::_( "JHACKS_FIELD_ALL_SUBFOLDERS_INFO" ); ?>
		</td>
	</tr>
<?php /*
	<tr>
		<td align="right" class="key">
				<label for="ext_files">
					<?php echo JText::_( "JHACKS_FIELD_TARGET_EXTENSIONS_FILES" ); ?> :
				</label>
		</td>
		<td>
			<input id="ext_files" class="inputbox " type="text" size="100" value="" name="ext_files"><?php echo JText::_( "JHACKS_FIELD_TARGET_EXTENSIONS_FILES_INFO" ); ?>
		</td>
	</tr>
*/ ?>
</table>
<p style="text-align: center; margin: 20px 0;">
	<a class="jhacks_button orange" onclick="Joomla.submitbutton('makeList')" href="#"><?php echo JText::_( "JHACKS_START_CREATION" ); ?></a>
</p>
</fieldset>
