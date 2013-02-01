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



// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Jhacks component
 *
 * @static
 * @package		Joomla
 * @subpackage	Processes
 *
 */
class JhacksViewProcesses extends JView
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
		$document->title = $document->titlePrefix . JText::_("JHACKS_LAYOUT_PROCESSES") . $document->titleSuffix;

		// Get data from the model
		$model 		= $this->getModel();
		$model->activeAll();
		$model->active('predefined', 'default');
		$model->active("publish", false);

		$items = $model->getItems();		
		
		foreach ($items as $item){
			$item->pro_target = JPath::clean($item->target_folder . DS . $item->target);
			
			if ($item->pro_publish == 1 AND $item->target_folder != '' AND $item->target != ''){
				$item->verify = JhacksHelper::verify($item);
			}
		}
				
		
		$model_pro_operation_id = JModel::getInstance('operations', 'JhacksModel');
		$model_pro_operation_id->addGroupBy("a.ordering");
		$lists['fk']['pro_operation_id'] = $model_pro_operation_id->getItems();		
		
		
		$total		= $this->get( 'Total');
		$pagination = $this->get( 'Pagination' );

		// table ordering
		$lists['order'] = $model->getState('list.ordering');
		$lists['order_Dir'] = $model->getState('list.direction');

		$lists['enum']['operations.type'] = JhacksHelper::enumList('operations', 'type');

		$lists['enum']['operations.replace_type'] = JhacksHelper::enumList('operations', 'replace_type');

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
			$bar->appendButton( 'Standard', "publish", "JTOOLBAR_PUBLISH", "publish", false); // hack
		if ($access->get('core.edit.state'))
			$bar->appendButton( 'Standard', "unpublish", "JTOOLBAR_UNPUBLISH", "unpublish", false); // hack
		if ($access->get('core.delete') || $access->get('core.delete.own'))
			$bar->appendButton( 'Standard', "delete", "JTOOLBAR_DELETE", "delete", true);
		if ($access->get('core.admin'))
			$bar->appendButton( 'Popup', 'options', JText::_('JTOOLBAR_OPTIONS'), 'index.php?option=com_config&view=component&component=' . $option . '&path=&tmpl=component');



		//Filters
		//Process Publish
		$this->filters['pro_publish'] = new stdClass();
		$this->filters['pro_publish']->value = $model->getState("filter.pro_publish");

		//search : search on Operation ID > Name + Target Folder + Target
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