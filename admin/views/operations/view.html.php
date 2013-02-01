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



// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Jhacks component
 *
 * @static
 * @package		Joomla
 * @subpackage	Operations
 *
 */
class JhacksViewOperations extends JView
{
	/*
	 * Define here the default list limit
	 */
	protected $_default_limit = null;

	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$config = JFactory::getConfig();

		$option	= JRequest::getCmd('option');
		$view	= JRequest::getCmd('view');
		$layout = $this->getLayout();



		switch($layout)
		{
			case 'default':
			case 'ajax':

				$fct = "display_" . $layout;
				$this->$fct($tpl);
				break;
		}

	}
	function display_default($tpl = null)
	{
		$app = JFactory::getApplication();
		$option	= JRequest::getCmd('option');

		$user 	= JFactory::getUser();

		$access = JhacksHelper::getACL();
		$state		= $this->get('State');

		$document	= &JFactory::getDocument();
		$document->title = $document->titlePrefix . JText::_("JHACKS_LAYOUT_OPERATIONS") . $document->titleSuffix;

		// Get data from the model
		$model 		= $this->getModel();
		$model->activeAll();
		$model->active('predefined', 'default');
		$model->active("publish", false);
		$model->active("access", false);

		$model->addJoin		("LEFT JOIN #__viewlevels as `_access_` ON _access_.id = a.access");
		$model->addSelect	("_access_.title AS `_access_title`");


		$items		= $model->getItems();
		
		
		JhacksHelper::operationStatus($items);
		
		
		$total		= $this->get( 'Total');
		$pagination = $this->get( 'Pagination' );

		// table ordering
		$lists['order'] = $model->getState('list.ordering');
		$lists['order_Dir'] = $model->getState('list.direction');

		$lists['enum']['operations.type'] = JhacksHelper::enumList('operations', 'type');

		$lists['enum']['operations.replace_type'] = JhacksHelper::enumList('operations', 'replace_type');

		//View levels (access on item)
		$lists['viewlevels'] = JhacksViewLevels::getList();

		// Toolbar
		jimport('joomla.html.toolbar');
		$bar = & JToolBar::getInstance('toolbar');
		
		$bar->appendButton( 'link', 'controlpanel', JText::_('JHACKS_VIEW_CONTROL_PANEL'), 'index.php?option=com_jhacks&view=hacks&layout=default', false);
		
		if ($access->get('core.create'))
			$bar->appendButton( 'Standard', "copy", "JTOOLBAR_CLONEITEMS", "cloneItems", true);
		
		if ($access->get('core.create'))
			$bar->appendButton( 'Standard', "new", "JTOOLBAR_NEW", "new", false);
		if ($access->get('core.edit') || $access->get('core.edit.own'))
			$bar->appendButton( 'Standard', "edit", "JTOOLBAR_EDIT", "edit", true);
		if ($access->get('core.edit.state'))
			$bar->appendButton( 'Standard', "publish", "JTOOLBAR_PUBLISH", "publish", true);
		if ($access->get('core.edit.state'))
			$bar->appendButton( 'Standard', "unpublish", "JTOOLBAR_UNPUBLISH", "unpublish", true);
		if ($access->get('core.delete') || $access->get('core.delete.own'))
			$bar->appendButton( 'Standard', "delete", "JTOOLBAR_DELETE", "delete", true);
		if ($access->get('core.admin'))
			$bar->appendButton( 'Popup', 'options', JText::_('JTOOLBAR_OPTIONS'), 'index.php?option=com_config&view=component&component=' . $option . '&path=&tmpl=component');



		//Filters
		//Hack > Name
		$model_hack_id = JModel::getInstance('hacks', 'JhacksModel');
		$this->filters['hack_id'] = new stdClass();
		$this->filters['hack_id']->list = $model_hack_id->getItems();
		$this->filters['hack_id']->value = $model->getState("filter.hack_id");

		//Type
		$this->filters['type'] = new stdClass();
		$this->filters['type']->list = $lists['enum']['operations.type'];
		array_unshift($this->filters['type']->list, array("value"=>"", "text" => JText::_("JHACKS_FILTER_NULL_TYPE")));
		$this->filters['type']->value = $model->getState("filter.type");

		//Regex
		$this->filters['regex'] = new stdClass();
		$this->filters['regex']->value = $model->getState("filter.regex");

		//Publish
		$this->filters['publish'] = new stdClass();
		$this->filters['publish']->value = $model->getState("filter.publish");

		//search : search on Name + Description
		$this->filters['search'] = new stdClass();
		$this->filters['search']->value = $model->getState("search.search");



		$config	= JComponentHelper::getParams( 'com_jhacks' );

		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('access',		$access);
		$this->assignRef('state',		$state);
		$this->assignRef('lists',		$lists);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('config',		$config);

		parent::display($tpl);
	}


	function display_ajax($tpl = null)
	{
		$render	= JRequest::getVar('render');
		$token	= JRequest::getVar('token');
		$values	= JRequest::getVar('values');


		$model = $this->getModel();
		$model->activeAll();
		$items = $model->getItems();


		switch($render)
		{
			case 'select2':
				/* Ajax List : Operations
				 * Called from: view:process, layout:process
				 */
				//Init or override the list of joined values for entry point
				if (is_array($values) && isset($values[0]) && $values[0])   //First value available
				{
					$model_item = JModel::getInstance('operation', 'JhacksModel');

					$model_item->setId($values[0]);				//Ground value
					$selectedItem = $model_item->getItem();

					//Redefine the ajax chain key values
					if ($model_item->getId() > 0)
					{

					}

				}

				$selected = (is_array($values))?$values[count($values)-1]:null;


				$event = 'jQuery("#pro_operation_id").val(this.value);';
				echo "<div class='ajaxchain-filter ajaxchain-filter-hz'>";
				echo JDom::_('html.form.input.select', array(
					'dataKey' => '__ajx_pro_operation_id',
					'dataValue' => $selected,
					'list' => $items,
					'listKey' => 'id',
					'labelKey' => 'op_name',
					'nullLabel' => "JHACKS_JSEARCH_SELECT_OPERATION_ID",

					'selectors' => array(
										'onchange' => $event
									)
					));
				echo "</div>";



				break;


		}

		jexit();
	}





}