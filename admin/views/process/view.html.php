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
class JhacksViewProcess extends JView
{
	function display($tpl = null)
	{
		$layout = $this->getLayout();
		switch($layout)
		{
			case 'process':

				$fct = "display_" . $layout;
				$this->$fct($tpl);
				break;
		}

	}
	function display_process($tpl = null)
	{
		$app = JFactory::getApplication();
		$option	= JRequest::getCmd('option');

		$user 	= JFactory::getUser();

		$access = JhacksHelper::getACL();

		$model	= $this->getModel();
		$model->activeAll();
		$model->active('predefined', 'process');

		$document	= &JFactory::getDocument();
		$document->title = $document->titlePrefix . JText::_("JHACKS_LAYOUT_PROCESS") . $document->titleSuffix;




		$lists = array();

		//get the process
		$process	= $model->getItem();
		$isNew		= ($process->id < 1);
		
		
		if (!$isNew){
			$process->target = JPath::clean($process->target_folder .'/'. $process->target);
			$process->_pro_operation_id_search_key = base64_decode($process->_pro_operation_id_search_key);
			$process->_pro_operation_id_replacement = base64_decode($process->_pro_operation_id_replacement);			
		}
		

		//For security, execute here a redirection if not authorized to enter a form
		if (($isNew && !$access->get('core.create'))
		|| (!$isNew && !$process->params->get('access-edit')))
		{
				JError::raiseWarning(403, JText::sprintf( "JERROR_ALERTNOAUTHOR") );
				JhacksHelper::redirectBack();
		}


		$lists['enum']['operations.type'] = JhacksHelper::enumList('operations', 'type');

		$lists['enum']['operations.replace_type'] = JhacksHelper::enumList('operations', 'replace_type');

		//Ordering
		$orderModel = JModel::getInstance('Processes', 'JhacksModel');
		$lists["ordering"] = $orderModel->getItems();

		// Toolbar
		jimport('joomla.html.toolbar');
		$bar = & JToolBar::getInstance('toolbar');
		if ($access->get('core.edit') || $access->get('core.edit.own'))
			$bar->appendButton( 'Standard', "apply", "JTOOLBAR_APPLY", "apply", false);
		if ($access->get('core.edit') || ($isNew && $access->get('core.create') || $access->get('core.edit.own')))
			$bar->appendButton( 'Standard', "save", "JTOOLBAR_SAVE", "save", false);
		if (!$isNew && ($access->get('core.delete') || $process->params->get('access-delete')))
			$bar->appendButton( 'Standard', "delete", "JTOOLBAR_DELETE", "delete", false);
		$bar->appendButton( 'Standard', "cancel", "JTOOLBAR_CANCEL", "cancel", false, false );




		$config	= JComponentHelper::getParams( 'com_jhacks' );

		JRequest::setVar( 'hidemainmenu', true );

		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('access',		$access);
		$this->assignRef('lists',		$lists);
		$this->assignRef('process',		$process);
		$this->assignRef('config',		$config);
		$this->assignRef('isNew',		$isNew);

		parent::display($tpl);
	}




}