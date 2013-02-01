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



// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Jhacks component
 *
 * @static
 * @package		Joomla
 * @subpackage	Hacks
 *
 */
class JhacksViewHacks extends JView
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
			case 'hacks':
			case 'default':
			case 'ajax':

				$fct = "display_" . $layout;
				$this->$fct($tpl);
				break;
		}

	}
	function display_hacks($tpl = null)
	{
		$app = JFactory::getApplication();
		$option	= JRequest::getCmd('option');

		$user 	= JFactory::getUser();

		$access = JhacksHelper::getACL();
		$state		= $this->get('State');

		$document	= &JFactory::getDocument();
		$document->title = $document->titlePrefix . JText::_("JHACKS_LAYOUT_HACKS") . $document->titleSuffix;

		// Get data from the model
		$model 		= $this->getModel();
		$model->activeAll();
		$model->active('predefined', 'hacks');
		$model->active("access", false);
		$model->active("publish", false);

		$model->addJoin		("LEFT JOIN #__viewlevels as `_access_` ON _access_.id = a.access");
		$model->addSelect	("_access_.title AS `_access_title`");


		$items		= $model->getItems();
		
		
		JhacksHelper::hackStatus($items);
		
		
		$total		= $this->get( 'Total');
		$pagination = $this->get( 'Pagination' );

		// table ordering
		$lists['order'] = $model->getState('list.ordering');
		$lists['order_Dir'] = $model->getState('list.direction');

		$lists['enum']['hacks.joomla_version'] = JhacksHelper::enumList('hacks', 'joomla_version');

		$lists['enum']['hacks.jks_version'] = JhacksHelper::enumList('hacks', 'jks_version');

		$lists['enum']['hacks.type'] = JhacksHelper::enumList('hacks', 'type');

		$lists['enum']['hacks.client'] = JhacksHelper::enumList('hacks', 'client');

		//View levels (access on item)
		$lists['viewlevels'] = JhacksViewLevels::getList();

		// Toolbar
		jimport('joomla.html.toolbar');
		$bar = & JToolBar::getInstance('toolbar');

		$bar->appendButton( 'link', 'controlpanel', JText::_('JHACKS_VIEW_CONTROL_PANEL'), 'index.php?option=com_jhacks&view=hacks&layout=default', false);
		if ($access->get('core.edit.state'))
			$bar->appendButton( 'Standard', "import", "JTOOLBAR_IMPORT", "import", false);
			JToolBarHelper::divider();

		if ($access->get('core.edit.state'))
			$bar->appendButton( 'Standard', "publish", "JTOOLBAR_PUBLISH", "publish", true);
		if ($access->get('core.edit.state'))
			$bar->appendButton( 'Standard', "unpublish", "JTOOLBAR_UNPUBLISH", "unpublish", true);
		if ($access->get('core.delete') || $access->get('core.delete.own'))
			$bar->appendButton( 'Standard', "delete", "JTOOLBAR_DELETE", "delete", true);
		if ($access->get('core.admin'))
			$bar->appendButton( 'Popup', 'options', JText::_('JTOOLBAR_OPTIONS'), 'index.php?option=com_config&view=component&component=' . $option . '&path=&tmpl=component');



		//Filters
		//Publish
		$this->filters['publish'] = new stdClass();
		$this->filters['publish']->value = $model->getState("filter.publish");

		//Type
		$this->filters['type'] = new stdClass();
		$this->filters['type']->list = $lists['enum']['hacks.type'];
		array_unshift($this->filters['type']->list, array("value"=>"", "text" => JText::_("JHACKS_FILTER_NULL_TYPE")));
		$this->filters['type']->value = $model->getState("filter.type");

		//Client
		$this->filters['client'] = new stdClass();
		$this->filters['client']->list = $lists['enum']['hacks.client'];
		array_unshift($this->filters['client']->list, array("value"=>"", "text" => JText::_("JHACKS_FILTER_NULL_CLIENT")));
		$this->filters['client']->value = $model->getState("filter.client");

		//Joomla Version
		$this->filters['joomla_version'] = new stdClass();
		$this->filters['joomla_version']->list = $lists['enum']['hacks.joomla_version'];
		array_unshift($this->filters['joomla_version']->list, array("value"=>"", "text" => JText::_("JHACKS_FILTER_NULL_JOOMLA_VERSION")));
		$this->filters['joomla_version']->value = $model->getState("filter.joomla_version");

		//Jhacks Version
		$this->filters['jks_version'] = new stdClass();
		$this->filters['jks_version']->list = $lists['enum']['hacks.jks_version'];
		array_unshift($this->filters['jks_version']->list, array("value"=>"", "text" => JText::_("JHACKS_FILTER_NULL_JHACKS_VERSION")));
		$this->filters['jks_version']->value = $model->getState("filter.jks_version");

		//search : search on Name + Description + Warnings +  + Element + Author + 
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


	function display_default($tpl = null)
	{
		$app = JFactory::getApplication();
		$option	= JRequest::getCmd('option');

		$user 	= JFactory::getUser();

		$access = JhacksHelper::getACL();
		$state		= $this->get('State');

		$document	= &JFactory::getDocument();
		$document->title = $document->titlePrefix . JText::_("JHACKS_LAYOUT_CONTROL_PANEL") . $document->titleSuffix;

		// Get data from the model
		$model 		= $this->getModel();
		$model->activeAll();
		$model->active('predefined', 'default');
		$model->active("access", false);
		$model->active("publish", false);

		$model->addJoin		("LEFT JOIN #__viewlevels as `_access_` ON _access_.id = a.access");
		$model->addSelect	("_access_.title AS `_access_title`");


		$items		= $model->getItems();

		$total		= $this->get( 'Total');
		$pagination = $this->get( 'Pagination' );

		// table ordering
		$lists['order'] = $model->getState('list.ordering');
		$lists['order_Dir'] = $model->getState('list.direction');

		$lists['enum']['hacks.joomla_version'] = JhacksHelper::enumList('hacks', 'joomla_version');

		$lists['enum']['hacks.jks_version'] = JhacksHelper::enumList('hacks', 'jks_version');

		$lists['enum']['hacks.type'] = JhacksHelper::enumList('hacks', 'type');

		$lists['enum']['hacks.client'] = JhacksHelper::enumList('hacks', 'client');

		// Toolbar
		jimport('joomla.html.toolbar');
		$bar = & JToolBar::getInstance('toolbar');
		if ($access->get('core.admin'))
			$bar->appendButton( 'Popup', 'options', JText::_('JTOOLBAR_OPTIONS'), 'index.php?option=com_config&view=component&component=' . $option . '&path=&tmpl=component');




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
			case 'select1':
				/* Ajax List : Hacks
				 * Called from: view:operation, layout:operation
				 */
				//Init or override the list of joined values for entry point
				if (is_array($values) && isset($values[0]) && $values[0])   //First value available
				{
					$model_item = JModel::getInstance('hack', 'JhacksModel');

					$model_item->setId($values[0]);				//Ground value
					$selectedItem = $model_item->getItem();

					//Redefine the ajax chain key values
					if ($model_item->getId() > 0)
					{

					}

				}

				$selected = (is_array($values))?$values[count($values)-1]:null;


				$event = 'jQuery("#hack_id").val(this.value);';
				echo "<div class='ajaxchain-filter ajaxchain-filter-hz'>";
				echo JDom::_('html.form.input.select', array(
					'dataKey' => '__ajx_hack_id',
					'dataValue' => $selected,
					'list' => $items,
					'listKey' => 'id',
					'labelKey' => 'name',
					'nullLabel' => "JHACKS_JSEARCH_SELECT_HACK",

					'selectors' => array(
										'onchange' => $event
									)
					));
				echo "</div>";



				break;

			case 'select3':
				/* Ajax List : Hacks
				 * Called from: view:templateoverride, layout:templateoverride
				 */
				//Init or override the list of joined values for entry point
				if (is_array($values) && isset($values[0]) && $values[0])   //First value available
				{
					$model_item = JModel::getInstance('hack', 'JhacksModel');

					$model_item->setId($values[0]);				//Ground value
					$selectedItem = $model_item->getItem();

					//Redefine the ajax chain key values
					if ($model_item->getId() > 0)
					{

					}

				}

				$selected = (is_array($values))?$values[count($values)-1]:null;


				$event = 'jQuery("#ov_hack_id").val(this.value);';
				echo "<div class='ajaxchain-filter ajaxchain-filter-hz'>";
				echo JDom::_('html.form.input.select', array(
					'dataKey' => '__ajx_ov_hack_id',
					'dataValue' => $selected,
					'list' => $items,
					'listKey' => 'id',
					'labelKey' => 'name',
					'nullLabel' => "JHACKS_JSEARCH_SELECT_HACK",

					'selectors' => array(
										'onchange' => $event
									)
					));
				echo "</div>";



				break;

			case 'select4':
				/* Ajax List : Hacks
				 * Called from: view:languageoverride, layout:languageoverride
				 */
				//Init or override the list of joined values for entry point
				if (is_array($values) && isset($values[0]) && $values[0])   //First value available
				{
					$model_item = JModel::getInstance('hack', 'JhacksModel');

					$model_item->setId($values[0]);				//Ground value
					$selectedItem = $model_item->getItem();

					//Redefine the ajax chain key values
					if ($model_item->getId() > 0)
					{

					}

				}

				$selected = (is_array($values))?$values[count($values)-1]:null;


				$event = 'jQuery("#lang_hack_id").val(this.value);';
				echo "<div class='ajaxchain-filter ajaxchain-filter-hz'>";
				echo JDom::_('html.form.input.select', array(
					'dataKey' => '__ajx_lang_hack_id',
					'dataValue' => $selected,
					'list' => $items,
					'listKey' => 'id',
					'labelKey' => 'name',
					'nullLabel' => "JHACKS_JSEARCH_SELECT_HACK",

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