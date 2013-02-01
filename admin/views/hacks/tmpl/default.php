<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V1.5.2   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		0.4.0
* @package		Jhacks
* @subpackage	Hacks
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


<?php JHTML::_('behavior.tooltip');?>
<?php JHTML::_('behavior.calendar');?>


<?php
	JToolBarHelper::title(JText::_("JHACKS_LAYOUT_CONTROL_PANEL"), 'jhacks_hacks' );
	$this->token = JUtility::getToken();
?>

<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton)
	{

		return Joomla.submitform(pressbutton);
	}
	function syncType(){
		what = $j("#sync_what").val();
		
		switch(what){
			case 'tmpl_ov':
				$j('#tmpl_form').fadeIn();
				$j('#lang_form').fadeOut();
				
			break;
			
			case 'lang_ov':
				$j('#tmpl_form').fadeOut();
				$j('#lang_form').fadeIn();			
			break;
			
			default:
				$j('#tmpl_form').fadeOut();
				$j('#lang_form').fadeOut();			
			break;
				
		}
	}
	
$j(document).ready( function() {
	$j("#sync_what").change(syncType);
	$j('div#toolbar-box div.m div.icon-48-jhacks_hacks').removeClass('icon-48-jhacks_hacks').addClass('icon-48-jhacks_controlpanel');
});	
</script>

<form action="<?php echo(JRoute::_("index.php")); ?>" method="post" name="adminForm" id="adminForm" class="" enctype="multipart/form-data">
<div id="file_import" class="reveal-modal">
<p style="text-align: center; margin-top: 15px;">
	<input size="40" type="file" name="file"><br /><br />
	<a id="import_button" href="#" class="jhacks_button green" onclick="Joomla.submitbutton('import')"><?php echo JText::_("JTOOLBAR_IMPORT"); ?></a>
</p>
	<a class="close-reveal-modal"><span style="font-size: 25px; color: #000000;">&#215;</span></a>
</div>
<table style="width: 100%;">
<tr>
 <td width = "50%">
    <div id = "cpanel">
<?php echo JDom::_("html.link.menu.cpanel.button", array(
										'href' => 'index.php?option=com_jhacks&view=hacks&layout=hacks',
										'link_title' => JText::_("JHACKS_VIEW_HACKS"),
										'img' => 'icon-48-jhacks_hacks.png'
										)); ?>
<?php echo JDom::_("html.link.menu.cpanel.button", array(
										'href' => 'index.php?option=com_jhacks&view=operations',
										'link_title' => JText::_("JHACKS_VIEW_OPERATIONS"),
										'img' => 'icon-48-jhacks_operations.png'
										)); ?>
<?php echo JDom::_("html.link.menu.cpanel.button", array(
										'href' => 'index.php?option=com_jhacks&view=processes',
										'link_title' => JText::_("JHACKS_VIEW_PROCESSES"),
										'img' => 'icon-48-jhacks_processes.png'
										)); ?>
<?php echo JDom::_("html.link.menu.cpanel.button", array(
										'href' => 'index.php?option=com_jhacks&view=logs',
										'link_title' => JText::_("JHACKS_VIEW_LOGS"),
										'img' => 'icon-48-jhacks_logs.png'
										)); ?>
<?php echo JDom::_("html.link.menu.cpanel.button", array(
										'href' => '#',
										'link_title' => JText::_("JHACKS_IMPORT_HACKS"),
										'img' => 'icon-48-jhacks_import.png',
										'extra' => 'data-reveal-id="file_import"'
										)); ?>
										
    </div>
 </td>
 <td><img src="components/com_jhacks/images/jhacks.png" align="left" border="0"></td>
 <td style = "vertical-align:top;  font-size: 14px;">
	<div><?php echo JText::_("JHACKS_ABOUT");?></div>
	<table width="100%">
		<tr>
			<td>
				<div>
					<a href="http://www.bygiro.com/" target="_blank">
						<img src="components/com_jhacks/images/bygiro.png" border="0">
					</a>
				</div>
				<div><a href="http://www.bygiro.com/" target="_blank">www.bygiro.com</a></div>
				<div><a href="mailto:girotomaselli@gmail.com">girotomaselli@gmail.com</a></div>
			</td>
			<td align="center">
<p>
<a class="jhacks_button green" onclick="document.donate.submit(); return false;" alt="PayPal - The safer, easier way to pay online!" href="#">
Make a Donation
</a></p>
<p>Help us to improve Jhacks and develop many more great joomla extensions!<br /> thank you.</p>
<a onclick="document.donate.submit(); return false;" alt="PayPal - The safer, easier way to pay online!" href="#">
<img src="components/com_jhacks/images/paypal.png" border="0">
</a>

			
			</td>
		</tr>
	</table>
 </td>
</tr>
</table>

	<?php echo JDom::_('html.form.footer', array(
		'values' => array(
				'option' => "com_jhacks",
				'view' => "hacks",
				'layout' => "default",
				'boxchecked' => "0",
				'filter_order' => $this->lists['order'],
				'filter_order_Dir' => $this->lists['order_Dir']
			)));
	?>

</form>

<form name="donate" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="cmd" value="_donations">
<input type="hidden" name="business" value="girotomaselli@hotmail.it">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="item_name" value="Jhacks joomla component">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="currency_code" value="EUR">
<input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_LG.gif:NonHostedGuest">

<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
