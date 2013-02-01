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

<fieldset class="fieldsfly act_fly">
	<table class="admintable">

		<tr>
			<td align="right" class="key">
				<label for="id">
					<?php echo JText::_( "JHACKS_FIELD_ID" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'id',
												'dataObject' => $this->activity
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="type_item">
					<?php echo JText::_( "JHACKS_FIELD_TYPE_ITEM" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly.enum', array(
												'dataKey' => 'type_item',
												'dataObject' => $this->activity,
												'list' => $this->lists['enum']['logs.type_item'],
												'listKey' => 'value',
												'labelKey' => 'text'
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="type_task">
					<?php echo JText::_( "JHACKS_FIELD_TYPE_TASK" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly.enum', array(
												'dataKey' => 'type_task',
												'dataObject' => $this->activity,
												'list' => $this->lists['enum']['logs.type_task'],
												'listKey' => 'value',
												'labelKey' => 'text'
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="lg_creation_date">
					<?php echo JText::_( "JHACKS_FIELD_CREATION_DATE" ); ?> :
				</label>
			</td>
			<td>
				<?php echo date ("d M Y - H:i:s", $row->lg_creation_date); ?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="result">
					<?php echo JText::_( "JHACKS_FIELD_RESULT" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly.enum', array(
												'dataKey' => 'result',
												'dataObject' => $this->activity,
												'list' => $this->lists['enum']['logs.result'],
												'listKey' => 'value',
												'labelKey' => 'text'
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="details">
					<?php echo JText::_( "JHACKS_FIELD_DETAILS" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'details',
												'dataObject' => $this->activity
												));
				?>
			</td>
		</tr>


	</table>
</fieldset>