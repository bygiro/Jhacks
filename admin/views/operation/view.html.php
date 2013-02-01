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
class JhacksViewOperation extends JView
{
	function display($tpl = null)
	{
		$layout = $this->getLayout();
		switch($layout)
		{
			case 'operation':

				$fct = "display_" . $layout;
				$this->$fct($tpl);
				break;
		}

	}
	function display_operation($tpl = null)
	{
		$app = JFactory::getApplication();
		$option	= JRequest::getCmd('option');

		$user 	= JFactory::getUser();

		$access = JhacksHelper::getACL();

		$model	= $this->getModel();
		$model->activeAll();
		$model->active('predefined', 'operation');

		$document	= &JFactory::getDocument();
		$document->title = $document->titlePrefix . JText::_("JHACKS_LAYOUT_OPERATION") . $document->titleSuffix;




		$lists = array();

		//get the operation
		$operation	= $model->getItem();
		$isNew		= ($operation->id < 1);

		
		if ($operation->data_file){$operation->data_file = JhacksHelper::getRealFilename($operation->data_file);}
		JhacksHelper::operationStatus($operation);
		$operation->search_key = base64_decode($operation->search_key);
		$operation->replacement = base64_decode($operation->replacement);
		

		//For security, execute here a redirection if not authorized to enter a form
		if (($isNew && !$access->get('core.create'))
		|| (!$isNew && !$operation->params->get('access-edit')))
		{
				JError::raiseWarning(403, JText::sprintf( "JERROR_ALERTNOAUTHOR") );
				JhacksHelper::redirectBack();
		}


		$lists['enum']['operations.type'] = JhacksHelper::enumList('operations', 'type');

		$lists['enum']['operations.replace_type'] = JhacksHelper::enumList('operations', 'replace_type');
		
		//Type
		$lists['select']['type'] = new stdClass();
		$lists['select']['type']->list = $lists['enum']['operations.type'];
		array_unshift($lists['select']['type']->list, array("value"=>"", "text" => JText::_("JHACKS_FIELD_NULL_TYPE")));
		$lists['select']['type']->value = $operation->type;

		//Replace type
		$lists['select']['replace_type'] = new stdClass();
		$lists['select']['replace_type']->list = $lists['enum']['operations.replace_type'];
		array_unshift($lists['select']['replace_type']->list, array("value"=>"", "text" => JText::_("JHACKS_FIELD_NULL_REPLACE_TYPE")));
		$lists['select']['replace_type']->value = $operation->replace_type;



		//Ordering
		$orderModel = JModel::getInstance('Operations', 'JhacksModel');
		$lists["ordering"] = $orderModel->getItems();

		// Toolbar
		jimport('joomla.html.toolbar');
		$bar = & JToolBar::getInstance('toolbar');
		if ($access->get('core.edit') || $access->get('core.edit.own'))
			$bar->appendButton( 'Standard', "apply", "JTOOLBAR_APPLY", "apply", false);
		if ($access->get('core.edit') || ($isNew && $access->get('core.create') || $access->get('core.edit.own')))
			$bar->appendButton( 'Standard', "save", "JTOOLBAR_SAVE", "save", false);
		if (!$isNew && ($access->get('core.delete') || $operation->params->get('access-delete')))
			$bar->appendButton( 'Standard', "delete", "JTOOLBAR_DELETE", "delete", false);
		$bar->appendButton( 'Standard', "cancel", "JTOOLBAR_CANCEL", "cancel", false, false );




		$config	= JComponentHelper::getParams( 'com_jhacks' );

		JRequest::setVar( 'hidemainmenu', true );

		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('access',		$access);
		$this->assignRef('lists',		$lists);
		$this->assignRef('operation',		$operation);
		$this->assignRef('config',		$config);
		$this->assignRef('isNew',		$isNew);

		parent::display($tpl);
	}




}