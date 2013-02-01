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



// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Jhacks component
 *
 * @static
 * @package		Joomla
 * @subpackage	Logs
 *
 */
class JhacksViewLogs extends JView
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
		$document->title = $document->titlePrefix . JText::_("JHACKS_LAYOUT_LOGS") . $document->titleSuffix;

		// Get data from the model
		$model 		= $this->getModel();
		$model->addOrder('a.lg_creation_date DESC'); // hack
		$model->activeAll();
		$model->active('predefined', 'default');





		$items		= $model->getItems();
		foreach($items as $it){
			$it->details = base64_decode($it->details);
		}
		
		$total		= $this->get( 'Total');
		$pagination = $this->get( 'Pagination' );

		// table ordering
		$lists['order'] = $model->getState('list.ordering');
		$lists['order_Dir'] = $model->getState('list.direction');

		$lists['enum']['logs.type_item'] = JhacksHelper::enumList('logs', 'type_item');

		$lists['enum']['logs.type_task'] = JhacksHelper::enumList('logs', 'type_task');

		$lists['enum']['logs.result'] = JhacksHelper::enumList('logs', 'result');

		// Toolbar
		jimport('joomla.html.toolbar');
		$bar = & JToolBar::getInstance('toolbar');

		$bar->appendButton( 'link', 'controlpanel', JText::_('JHACKS_VIEW_CONTROL_PANEL'), 'index.php?option=com_jhacks&view=hacks&layout=default', false);
		
		if ($access->get('core.delete') || $access->get('core.delete.own'))
			$bar->appendButton( 'Standard', "delete", "JTOOLBAR_DELETE", "delete", true);
		if ($access->get('core.admin'))
			$bar->appendButton( 'Popup', 'options', JText::_('JTOOLBAR_OPTIONS'), 'index.php?option=com_config&view=component&component=' . $option . '&path=&tmpl=component');



		//Filters
		//Type item
		$this->filters['type_item'] = new stdClass();
		$this->filters['type_item']->list = $lists['enum']['logs.type_item'];
		array_unshift($this->filters['type_item']->list, array("value"=>"", "text" => JText::_("JHACKS_FILTER_NULL_TYPE_ITEM")));
		$this->filters['type_item']->value = $model->getState("filter.type_item");

		//Type task
		$this->filters['type_task'] = new stdClass();
		$this->filters['type_task']->list = $lists['enum']['logs.type_task'];
		array_unshift($this->filters['type_task']->list, array("value"=>"", "text" => JText::_("JHACKS_FILTER_NULL_TYPE_TASK")));
		$this->filters['type_task']->value = $model->getState("filter.type_task");

		//Result
		$this->filters['result'] = new stdClass();
		$this->filters['result']->list = $lists['enum']['logs.result'];
		array_unshift($this->filters['result']->list, array("value"=>"", "text" => JText::_("JHACKS_FILTER_NULL_RESULT")));
		$this->filters['result']->value = $model->getState("filter.result");

		//search : search on Details
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





}