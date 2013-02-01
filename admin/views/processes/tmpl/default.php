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

defined('_JEXEC') or die('Restricted access'); ?>

<?php JhacksHelper::headerDeclarations(); ?>


<?php JHTML::_('behavior.tooltip');?>
<?php JHTML::_('behavior.calendar');?>


<?php
	JToolBarHelper::title(JText::_("JHACKS_LAYOUT_PROCESSES"), 'jhacks_processes' );
	$this->token = JUtility::getToken();
?>

<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton)
	{
		switch(pressbutton)
		{
			case 'delete':

				var deleteConfirmMessage;
				if (document.adminForm.boxchecked.value > 1)
					deleteConfirmMessage = "<?php echo(addslashes(JText::_("JDOM_ALERT_ASK_BEFORE_REMOVE_MULTIPLE"))); ?>";
				else
					deleteConfirmMessage = "<?php echo(addslashes(JText::_("JDOM_ALERT_ASK_BEFORE_REMOVE"))); ?>";

				if (window.confirm(deleteConfirmMessage))
					return Joomla.submitform(pressbutton);
				else
					return;
				break;

		}

		return Joomla.submitform(pressbutton);
	}
	
function browse(inverse){
	if (inverse == 0){
		$j('#creator_browser').show();
		$j('#creator_form').hide();
	} else {
		$j('#creator_browser').hide();
		$j('#creator_form').show();		
	}
}

function restore(cb){
	$j('#restore').val(1);
	listItemTask(cb, 'unpublish');
}

function loadFile(id){
	var filepath = $j('#target_'+id).html();
	var loadingimg = '<p style="text-align: center; margin-top: 50px;"><?php echo JText::_( "JHACKS_PLEASE_WAIT" ); ?><img src="components/com_jhacks/images/spinner.gif" /></p>';
	$j("#filecontent_"+id).html(loadingimg);
	$j("#file_name_"+id).html(filepath);
	
    $j.ajax({
      type: "POST",
      url: "index.php?option=com_jhacks&task=loadfile",
      data: {file: filepath},
      dataType: "html",
      success: function(content)
      {
        $j("#filecontent_"+id).html(content);
      },
      error: function()
      {
        alert("<?php echo JText::_( "JHACKS_ERROR_LOADING_SOURCE_CODE" ); ?>");
      }
    });	
}

	var root_web = "<?php echo addslashes(JPATH_SITE . DS) ?>";
$j(document).ready( function() {
		$j('#filenameTree').fileTree({ root: '', script: 'index.php?option=com_jhacks&task=browse', folderEvent: 'click', expandSpeed: 750, collapseSpeed: 750, expandEasing: 'easeOutBounce', collapseEasing: 'easeOutBounce', loadMessage: 'Wait...', multiFolder: false }, function(file) { 
			var relat_path = file.replace(root_web,"");
			
			$j('#target').val(relat_path);
			$j('#fileselected').html(relat_path);
		});
});

</script>

<form action="<?php echo(JRoute::_("index.php")); ?>" method="post" name="adminForm" id="adminForm" class="">
	<div>
		<?php echo $this->loadTemplate('filters'); ?>
		<?php echo $this->loadTemplate('grid'); ?>
	</div>











	<?php echo JDom::_('html.form.footer', array(
		'values' => array(
				'option' => "com_jhacks",
				'view' => "processes",
				'layout' => "default",
				'boxchecked' => "0",
				'filter_order' => $this->lists['order'],
				'filter_order_Dir' => $this->lists['order_Dir']
			)));
	?>

</form>