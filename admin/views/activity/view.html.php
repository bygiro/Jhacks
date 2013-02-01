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
class JhacksViewActivity extends JView
{
	function display($tpl = null)
	{
		$layout = $this->getLayout();
		switch($layout)
		{
			case 'logactivity':

				$fct = "display_" . $layout;
				$this->$fct($tpl);
				break;
		}

	}
	function display_logactivity($tpl = null)
	{
		$app = JFactory::getApplication();
		$option	= JRequest::getCmd('option');

		$user 	= JFactory::getUser();

		$access = JhacksHelper::getACL();

		$model	= $this->getModel();
		$model->activeAll();
		$model->active('predefined', 'logactivity');

		$document	= &JFactory::getDocument();
		$document->title = $document->titlePrefix . JText::_("JHACKS_LAYOUT_LOG_ACTIVITY") . $document->titleSuffix;




		$lists = array();

		//get the activity
		$activity	= $model->getItem();
		$isNew		= ($activity->id < 1);

		//For security, execute here a redirection if not authorized to view
		if (!$activity->params->get('access-view'))
		{
				JError::raiseWarning(403, JText::sprintf( "JERROR_ALERTNOAUTHOR") );
				JhacksHelper::redirectBack();
		}


		$lists['enum']['logs.type_item'] = JhacksHelper::enumList('logs', 'type_item');

		$lists['enum']['logs.type_task'] = JhacksHelper::enumList('logs', 'type_task');

		$lists['enum']['logs.result'] = JhacksHelper::enumList('logs', 'result');

		// Toolbar
		jimport('joomla.html.toolbar');
		$bar = & JToolBar::getInstance('toolbar');
		if (!$isNew && ($access->get('core.delete') || $activity->params->get('access-delete')))
			$bar->appendButton( 'Standard', "delete", "JTOOLBAR_DELETE", "delete", false);
		$bar->appendButton( 'Standard', "cancel", "JTOOLBAR_CANCEL", "cancel", false, false );




		$config	= JComponentHelper::getParams( 'com_jhacks' );

		JRequest::setVar( 'hidemainmenu', true );

		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('access',		$access);
		$this->assignRef('lists',		$lists);
		$this->assignRef('activity',		$activity);
		$this->assignRef('config',		$config);
		$this->assignRef('isNew',		$isNew);

		parent::display($tpl);
	}




}