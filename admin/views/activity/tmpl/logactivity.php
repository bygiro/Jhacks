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

defined('_JEXEC') or die('Restricted access'); ?>

<?php JhacksHelper::headerDeclarations(); ?>





<?php	JToolBarHelper::title(JText::_("JHACKS_LAYOUT_LOG_ACTIVITY"), 'jhacks_logs' );?>

<script language="javascript" type="text/javascript">
jQuery(document).ready(function(){
	jQuery("#adminForm").validationEngine();
});

Joomla.submitform = function(pressbutton)
{
	//Unlock the page
	holdForm = false;

	var parts = pressbutton.split('.');

	jQuery("#task").val(pressbutton);
	switch(parts[parts.length-1])
	{
		case 'delete':
		case 'cancel':
			jQuery("#adminForm").validationEngine('detach');
			break;
	}

	jQuery("#adminForm").submit();
}

//Secure the user navigation on the page, in order preserve datas.
var holdForm = true;
window.onbeforeunload = function closeIt(){	if (holdForm) return false;};
</script>

<form action="<?php echo(JRoute::_("index.php")); ?>" method="post" name="adminForm" id="adminForm">




	<div>
		<?php echo $this->loadTemplate('activity'); ?>
	</div>










	<?php echo JDom::_('html.form.footer', array(
		'dataObject' => $this->activity,
		'values' => array(
				'option' => "com_jhacks",
				'view' => "activity",
				'layout' => "logactivity"
				)));
	?>

</form>