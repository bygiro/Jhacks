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
				<label for="hack_id">
					<?php echo JText::_( "JHACKS_FIELD_HACK_ID" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'hack_id',
												'dataObject' => $this->operation
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="_hack_id_name">
					<?php echo JText::_( "JHACKS_FIELD_NAME" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly', array(
												'dataKey' => '_hack_id_name',
												'dataObject' => $this->operation,
												'route' => array('view' => 'hack','layout' => 'hack','cid[]' => $this->item->hack_id)
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="_hack_id_description">
					<?php echo JText::_( "JHACKS_FIELD_DESCRIPTION" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.fly', array(
												'dataKey' => '_hack_id_description',
												'dataObject' => $this->operation
												));
				?>
			</td>
		</tr>


	</table>
</fieldset>