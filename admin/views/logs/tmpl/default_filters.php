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
	if ($('filter_type_item') != null)
	    $('filter_type_item').value='';
	if ($('filter_type_task') != null)
	    $('filter_type_task').value='';
	if ($('filter_result') != null)
	    $('filter_result').value='';
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
			<!-- SEARCH : filter_search : search on Details  -->

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
			<!-- SELECT : Type item  -->

					<div class='filter filter_type_item'>
			
						<?php echo JDom::_('html.form.input.select', array(
											'dataKey' => 'filter_type_item',
											'dataValue' => $this->filters['type_item']->value,
											'list' => $this->filters['type_item']->list,
											'listKey' => 'value',
											'labelKey' => 'text',
											'submitEventName' => 'onchange'
												));

						?>

					</div>


		</div>
		<div style="float:left">
			<!-- SELECT : Type task  -->

					<div class='filter filter_type_task'>
			
						<?php echo JDom::_('html.form.input.select', array(
											'dataKey' => 'filter_type_task',
											'dataValue' => $this->filters['type_task']->value,
											'list' => $this->filters['type_task']->list,
											'listKey' => 'value',
											'labelKey' => 'text',
											'submitEventName' => 'onchange'
												));

						?>

					</div>


		</div>
		<div style="float:left">
			<!-- SELECT : Result  -->

					<div class='filter filter_result'>
			
						<?php echo JDom::_('html.form.input.select', array(
											'dataKey' => 'filter_result',
											'dataValue' => $this->filters['result']->value,
											'list' => $this->filters['result']->list,
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
