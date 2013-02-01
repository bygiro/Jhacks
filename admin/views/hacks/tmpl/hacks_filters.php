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

<script language="javascript" type="text/javascript">
<!--


function resetFilters()
{
	if (typeof(jQuery) != 'undefined')
	{
		jQuery('.filters :input').val('');

	/* TODO : Uncomment this if you want that the reset action proccess also on sorting values
		jQuery('#filter_order').val('');
		jQuery('#filter_orderDir').val('');
	*/
		document.adminForm.submit();
		return;
	}

//Deprecated
	if ($('filter_publish') != null)
	    $('filter_publish').value='';
	if ($('filter_type') != null)
	    $('filter_type').value='';
	if ($('filter_client') != null)
	    $('filter_client').value='';
	if ($('filter_joomla_version') != null)
	    $('filter_joomla_version').value='';
	if ($('filter_jks_version') != null)
	    $('filter_jks_version').value='';
	if ($('filter_search') != null)
	    $('filter_search').value='';


/* TODO : Uncomment this if you want that the reset action proccess also on sorting values
	if ($('filter_order') != null)
	    $('filter_order').value='';
	if ($('filter_orderDir') != null)
	    $('filter_orderDir').value='';
*/

	document.adminForm.submit();
}

-->
</script>


<fieldset id="filters" class="filters">
	<legend><?php echo JText::_( "JSEARCH_FILTER_LABEL" ); ?></legend>



	<div style="float:right;">
		<div style="float:left">
			<!-- SEARCH : filter_search : search on Name + Description + Warnings +  + Element + Author +   -->

				<div class='search filter filter_search'>

					<?php echo JDom::_('html.form.input.search', array(
											'domID' => 'filter_search',
											'dataKey' => 'filter_search',
											'dataValue' => $this->filters['search']->value
												));


						?>
				</div>


		</div>
		<div style="float:left">
				<div class="filter filter_buttons">
					<button onclick="this.form.submit();"><?php echo(JText::_("JSEARCH_FILTER_SUBMIT")); ?></button>
					<button onclick="resetFilters()"><?php echo(JText::_("JSEARCH_FILTER_CLEAR")); ?></button>
				</div>
		</div>
	</div>

	<div>
		<div style="float:left">
			<!-- SELECT : Publish  -->

					<div class='filter filter_publish'>
			
						<?php
						$choices = array();
						$choices[] = array("value" => null, 'text'=>JText::_( "JHACKS_FILTER_NULL_PUBLISH" ));
						$choices[] = array("value" => '0', 'text'=>JText::_( "JNO" ));
						$choices[] = array("value" => '1', 'text'=>JText::_( "JYES" ));

						echo JDom::_('html.form.input.select', array(
											'dataKey' => 'filter_publish',
											'dataValue' => $this->filters['publish']->value,
											'list' => $choices,
											'listKey' => 'value',
											'labelKey' => 'text',
											'submitEventName' => 'onchange'
												));

						?>
					</div>


		</div>
		<div style="float:left">
			<!-- SELECT : Type  -->

					<div class='filter filter_type'>
			
						<?php echo JDom::_('html.form.input.select', array(
											'dataKey' => 'filter_type',
											'dataValue' => $this->filters['type']->value,
											'list' => $this->filters['type']->list,
											'listKey' => 'value',
											'labelKey' => 'text',
											'submitEventName' => 'onchange'
												));

						?>

					</div>


		</div>
		<div style="float:left">
			<!-- SELECT : Client  -->

					<div class='filter filter_client'>
			
						<?php echo JDom::_('html.form.input.select', array(
											'dataKey' => 'filter_client',
											'dataValue' => $this->filters['client']->value,
											'list' => $this->filters['client']->list,
											'listKey' => 'value',
											'labelKey' => 'text',
											'submitEventName' => 'onchange'
												));

						?>

					</div>


		</div>
		<div style="float:left">
			<!-- SELECT : Joomla Version  -->

					<div class='filter filter_joomla_version'>
			
						<?php echo JDom::_('html.form.input.select', array(
											'dataKey' => 'filter_joomla_version',
											'dataValue' => $this->filters['joomla_version']->value,
											'list' => $this->filters['joomla_version']->list,
											'listKey' => 'value',
											'labelKey' => 'text',
											'submitEventName' => 'onchange'
												));

						?>

					</div>


		</div>
		<div style="float:left">
			<!-- SELECT : Jhacks Version  -->

					<div class='filter filter_jks_version'>
			
						<?php echo JDom::_('html.form.input.select', array(
											'dataKey' => 'filter_jks_version',
											'dataValue' => $this->filters['jks_version']->value,
											'list' => $this->filters['jks_version']->list,
											'listKey' => 'value',
											'labelKey' => 'text',
											'submitEventName' => 'onchange'
												));

						?>

					</div>


		</div>
	</div>




	<div clear='all'></div>





</fieldset>
