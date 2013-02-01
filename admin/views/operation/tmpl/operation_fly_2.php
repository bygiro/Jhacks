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

<fieldset class="fieldsfly">
	<table class="admintable">

		<tr>
			<td align="right" class="key">
				<label for="publish">
					<?php echo JText::_( "JHACKS_FIELD_PUBLISH" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly.bool', array(
												'dataKey' => 'publish',
												'dataObject' => $this->operation
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="access">
					<?php echo JText::_( "JHACKS_FIELD_ACCESS" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly.accesslevel', array(
												'dataKey' => 'access',
												'dataObject' => $this->operation,
												'aclAccess' => 'core.edit.state'
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="ordering">
					<?php echo JText::_( "JHACKS_FIELD_ORDERING" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'ordering',
												'dataObject' => $this->operation
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
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'op_description',
												'dataObject' => $this->operation
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="type">
					<?php echo JText::_( "JHACKS_FIELD_TYPE" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly.enum', array(
												'dataKey' => 'type',
												'dataObject' => $this->operation,
												'list' => $this->lists['enum']['operations.type'],
												'listKey' => 'value',
												'labelKey' => 'text'
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="replace_type">
					<?php echo JText::_( "JHACKS_FIELD_REPLACE_TYPE" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly.enum', array(
												'dataKey' => 'replace_type',
												'dataObject' => $this->operation,
												'list' => $this->lists['enum']['operations.replace_type'],
												'listKey' => 'value',
												'labelKey' => 'text'
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="regex">
					<?php echo JText::_( "JHACKS_FIELD_REGEX" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly.bool', array(
												'dataKey' => 'regex',
												'dataObject' => $this->operation
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="search_key">
					<?php echo JText::_( "JHACKS_FIELD_SEARCH_KEY" ); ?> :
				</label>
			</td>
			<td>
<textarea class="fly_code" disabled="disabled"><?php echo $this->operation->search_key;?></textarea>		
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="replacement">
					<?php echo JText::_( "JHACKS_FIELD_REPLACEMENT" ); ?> :
				</label>
			</td>
			<td>
<textarea class="fly_code" disabled="disabled"><?php echo $this->operation->replacement;?></textarea>				
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="data_file">
					<?php echo JText::_( "JHACKS_FIELD_DATA_FILE" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly.file.default', array(
												'dataKey' => 'data_file',
												'dataObject' => $this->operation,
												'width' => 'auto',
												'height' => 'auto',
												'attrs' => array('format:png'),
												'indirect' => true,
												'root' => '[DIR_OPERATIONS_DATA_FILE]'
												));
				?>
			</td>
		</tr>


	</table>
</fieldset>