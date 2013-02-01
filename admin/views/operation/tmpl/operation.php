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

defined('_JEXEC') or die('Restricted access'); ?>

<?php JhacksHelper::headerDeclarations(); ?>





<?php	JToolBarHelper::title(JText::_("JHACKS_LAYOUT_OPERATION"), 'jhacks_operations' );?>

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

function opType(){
	var value = $j('#type').val();
	switch (value) {
		case 'replacepattern':	/* replace pattern */
				closeAll();

				/* open */
				$j('.regex_row').fadeIn();
				$j('.search_key_row').fadeIn();
				$j('.replace_type_row').fadeIn();
				$j('#rep_lab').html('<?php echo JText::_( "JHACKS_FIELD_REPLACEMENT" ); ?> :');
				$j('.replacement_row').fadeIn();
		
		break;
		
		case 'replacefile': /* replace file */
			closeAll();

			/* open */
			$j('.data_file_row').fadeIn();
		break;
		
		case 'sql': /* replace file */
			closeAll();

			/* open */
			$j('.sql_row').fadeIn();
		break;
		
		case 'createfolder': /* create folder */
				closeAll();

				/* change label name */
				$j('#rep_lab').html('<?php echo JText::_( "JHACKS_FIELD_NAMEFOLDER" ); ?> :');
				
				/* open */
				$j('.replacement_row').fadeIn();
		break;
		
		default: /* all closed */
			closeAll();
		break;	
	}
}

function closeAll(){
	$j('.regex_row').fadeOut();
	$j('.search_key_row').fadeOut();
	$j('.replace_type_row').fadeOut();
	$j('.replacement_row').fadeOut();
	$j('.data_file_row').fadeOut();
	$j('.sql_row').fadeOut();
}

	var root_web = "<?php echo addslashes(JPATH_SITE . DS) ?>";
$j(document).ready( function() {
		$j('#filenameTree').fileTree({ root: '', script: 'index.php?option=com_jhacks&task=browse', folderEvent: 'click', expandSpeed: 750, collapseSpeed: 750, expandEasing: 'easeOutBounce', collapseEasing: 'easeOutBounce', loadMessage: 'Wait...', multiFolder: false }, function(file) { 
			var relat_path = file.replace(root_web,"");
			
			$j('#fileselected').html(relat_path);
		});
		
	$j("#search_key").attr('onfocus','this.select()');
	$j("#replacement").attr('onfocus','this.select()');
	
	$j("#type").change(opType);
	opType();

	$j("#filecontent").bind({
		copy : function(){
			$j('#copypasteinfo').show();
			$j('#selectinfo').hide();
		}
	});

});

function browse(){
	$j('#filetreebrowser').show();
	$j('#sourcecode').hide();
}

function loadFile(){
	var filepath = $j('#fileselected').html();
	var loadingimg = '<p style="text-align: center; margin-top: 50px;"><?php echo JText::_( "JHACKS_PLEASE_WAIT" ); ?><img src="components/com_jhacks/images/spinner.gif" /></p>';
	$j("#filecontent").html(loadingimg);
	$j("#file_name").html(filepath);
	$j('#copypasteinfo').hide();
	$j('#selectinfo').show();

	$j('#filetreebrowser').hide();
	$j('#sourcecode').show();
	
    $j.ajax({
      type: "POST",
      url: "index.php?option=com_jhacks&task=loadfile",
      data: {file: filepath},
      dataType: "html",
      success: function(content)
      {
        $j("#filecontent").html(content);
      },
      error: function()
      {
        alert("<?php echo JText::_( "JHACKS_ERROR_LOADING_SOURCE_CODE" ); ?>");
      }
    });	
}
</script>

<form action="<?php echo(JRoute::_("index.php")); ?>" method="post" name="adminForm" id="adminForm" class='form-validate' autocomplete='off' enctype='multipart/form-data'> <?php  ?>




	<div>
	<?php if ($this->operation->publish){?>
	<?php echo '<p class="info">'. JText::_( "JHACKS_UNPUBLISH_BEFORE_EDIT" ).'</p>';?>
		<?php echo $this->loadTemplate('fly_2'); ?>
	<?php } else {
			if ($this->operation->hack_id > 0 AND $this->operation->id > 0) { echo $this->loadTemplate('fly_1'); } 
			echo $this->loadTemplate('form'); 
		  } ?>
	
	</div>










	<?php echo JDom::_('html.form.footer', array(
		'dataObject' => $this->operation,
		'values' => array(
				'option' => "com_jhacks",
				'view' => "operation",
				'layout' => "operation"
				)));
	?>

</form>