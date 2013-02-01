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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


/**
 * Jhacks Processes Controller
 *
 * @package		Joomla
 * @subpackage	Jhacks
 *
 */
class JhacksControllerProcesses extends JhacksController
{
	var $ctrl = 'processes';
	var $singular = 'process';

	function __construct($config = array())
	{

		parent::__construct($config);

		$layout = JRequest::getCmd('layout');
		$render	= JRequest::getCmd('render');

		$this->context = strtolower('com_' . $this->getName() . '.' . $this->ctrl
					. ($layout?'.' . $layout:'')
					. ($render?'.' . $render:'')
					);

		$app = JFactory::getApplication();
		$this->registerTask( 'new',  'new_' );
		$this->registerTask( 'unpublish',  'unpublish' );
		$this->registerTask( 'apply',  'apply' );






	}

	function display( )
	{



		parent::display();

		if (!JRequest::getCmd('option',null, 'get'))
		{
			//Kill the post and rebuild the url
			$this->setRedirect(JhacksHelper::urlRequest());
			return;
		}

	}

	function new_()
	{
		if (!$this->can('core.create', JText::_("JTOOLBAR_NEW")))
			return;

		$vars = array();
		//Predefine fields depending on filters values
		$app = JFactory::getApplication();
		//Process Publish
		$filter_pro_publish = $app->getUserState( $this->context . ".filter.pro_publish");
		if ($filter_pro_publish) $vars["filter_pro_publish"] = $filter_pro_publish;

		//Operation ID > Name
		$filter_pro_operation_id = $app->getUserState( $this->context . ".filter.pro_operation_id");
		if ($filter_pro_operation_id) $vars["filter_pro_operation_id"] = $filter_pro_operation_id;



		JRequest::setVar( 'cid', 0 );
		JRequest::setVar( 'view'  , 'process');
		JRequest::setVar( 'layout', 'process' );

		$this->setRedirect(JhacksHelper::urlRequest($vars));
	}

	function edit()
	{
		//Check Component ACL
		if (!$this->can(array('core.edit', 'core.edit.own'), JText::_("JTOOLBAR_EDIT")))
			return;

		$model = $this->getModel('process');
		$item = $model->getItem();

		//Check Item ACL
		if (!$this->can('access-edit', JText::_("JTOOLBAR_EDIT"), $item->params))
			return;

		$vars = array();
		JRequest::setVar( 'view'  , 'process');
		JRequest::setVar( 'layout', 'process' );

		$this->setRedirect(JhacksHelper::urlRequest($vars));
	}

	function publish()
	{
		$app = JFactory::getApplication();
		if (!$this->can('core.edit.state', JText::_("JTOOLBAR_PUBLISH")))
			return;

		// Check for request forgeries
		JRequest::checkToken() or JRequest::checkToken('get') or jexit( 'Invalid Token' );

        $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
        if (empty($cid))
			$cid = JRequest::getVar( 'cid', array(), 'get', 'array' );

        JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseWarning(500, JText::sprintf( "JHACKS_ALERT_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST_TO", strtolower(JText::_("PUBLISH")) ) );
		}
		else
		{
			// publish
			$model = $this->getModel('process');
			$msg = $model->publish($cid, 1);
		}

		$app->enqueueMessage($msg->logErrors . $msg->logData);
		
		$vars = array();
		JRequest::setVar( 'view'  , 'processes');
		JRequest::setVar( 'layout', 'default' );
		JRequest::setVar( 'cid', null );

		$this->setRedirect(JhacksHelper::urlRequest($vars));
	}

	function unpublish()
	{
		$app = JFactory::getApplication();
		if (!$this->can('core.edit.state', JText::_("JTOOLBAR_UNPUBLISH")))
			return;

		// Check for request forgeries
		JRequest::checkToken() or JRequest::checkToken('get') or jexit( 'Invalid Token' );

        $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
        if (empty($cid))
			$cid = JRequest::getVar( 'cid', array(), 'get', 'array' );

        JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseWarning(500, JText::sprintf( "JHACKS_ALERT_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST_TO", strtolower(JText::_("UNPUBLISH")) ) );
		}
		else
		{
			// unpublish
			$model = $this->getModel('process');
			$msg = $model->publish($cid, 0);
		}

		$app->enqueueMessage($msg->result);
		
		$vars = array();
		JRequest::setVar( 'view'  , 'processes');
		JRequest::setVar( 'layout', 'default' );
		JRequest::setVar( 'cid', null );

		$this->setRedirect(JhacksHelper::urlRequest($vars));

	}

	function delete()
	{
		if (!$this->can(array('core.delete', 'core.delete.own'), JText::_("JTOOLBAR_DELETE")))
			return;

		// Check for request forgeries
		JRequest::checkToken() or JRequest::checkToken('get') or jexit( 'Invalid Token' );


		$model = $this->getModel('process');
		$item = $model->getItem();

		//Check Item ACL
		if (!$this->can('access-delete', JText::_("JTOOLBAR_DELETE"), $item->params))
			return;


        $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
        if (empty($cid))
			$cid = JRequest::getVar( 'cid', array(), 'get', 'array' );

		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseWarning(500, JText::sprintf( '_ALERT_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST_TO', strtolower(JText::_("DELETE")) ) );
			$this->setRedirect(JhacksHelper::urlRequest());
			return;
		}

		$vars = array();
		if (parent::_delete($cid))
		{
			JRequest::setVar( 'view'  , 'processes');
			JRequest::setVar( 'layout', 'default' );
			JRequest::setVar( 'cid', null );

		}

		$this->setRedirect(JhacksHelper::urlRequest($vars));

	}

	function apply()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model = $this->getModel('process');
		$item = $model->getItem();

		if ($model->getId() == 0)
		{	//New item

			if (!$this->can('core.create', JText::_("JTOOLBAR_APPLY")))
				return;

		}
		else
		{	//Existing item
			if (!$this->can(array('core.edit', 'core.edit.own'), JText::_("JTOOLBAR_APPLY")))
				return;

			//Check Item ACL
			if (!$this->can('access-edit', JText::_("JTOOLBAR_APPLY"), $item->params))
				return;
		}


		$post	= JRequest::get('post');
		$post['id'] = $model->getId();

		$post['conflicts'] = JRequest::getVar('conflicts', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		$post['target_folder'] = dirname($post['target']);
		$post['target'] = basename($post['target']);
		

		if ($cid = parent::_apply($post))
		{
			$vars = array();
			JRequest::setVar( 'view'  , 'process');
			JRequest::setVar( 'layout', 'process' );

			$this->setRedirect(JhacksHelper::urlRequest($vars));
		}
		else
			//Keep the post and stay on page
			parent::display();


	}



	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model = $this->getModel('process');
		$item = $model->getItem();

		if ($model->getId() == 0)
		{	//New item

			if (!$this->can('core.create', JText::_("JTOOLBAR_SAVE")))
				return;

		}
		else
		{	//Existing item
			if (!$this->can(array('core.edit', 'core.edit.own'), JText::_("JTOOLBAR_SAVE")))
				return;

			//Check Item ACL
			if (!$this->can('access-edit', JText::_("JTOOLBAR_SAVE"), $item->params))
				return;
		}


		$post	= JRequest::get('post');
		$post['id'] = $model->getId();

		$post['conflicts'] = JRequest::getVar('conflicts', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		$post['target_folder'] = dirname($post['target']);
		$post['target'] = basename($post['target']);
		
		if ($cid = parent::_save($post))
		{
			$vars = array();
			JRequest::setVar( 'view'  , 'processes');
			JRequest::setVar( 'layout', 'default' );
			JRequest::setVar( 'cid', null );

			$this->setRedirect(JhacksHelper::urlRequest($vars));
		}
		else
			//Keep the post and stay on page
			parent::display();

	}

	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );


		$vars = array();
		JRequest::setVar( 'view'  , 'processes');
		JRequest::setVar( 'layout', 'default' );
		JRequest::setVar( 'cid', null );

		$this->setRedirect(JhacksHelper::urlRequest($vars));

	}

	function orderup()
	{
		if (!$this->can('core.edit.state', JText::_("JHACKS_JTOOLBAR_CHANGE_ORDER")))
			return;

		// Check for request forgeries
		JRequest::checkToken() or JRequest::checkToken('get') or jexit( 'Invalid Token' );


		$model = $this->getModel('process');
		$item = $model->getItem();	//Set the Id from request
		$model->move(-1);

		$vars = array();
		JRequest::setVar( 'view'  , 'processes');
		JRequest::setVar( 'layout', 'default' );
		JRequest::setVar( 'cid', null );

		$this->setRedirect(JhacksHelper::urlRequest($vars));
	}

	function orderdown()
	{
		if (!$this->can('core.edit.state', JText::_("JHACKS_JTOOLBAR_CHANGE_ORDER")))
			return;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );


		$model = $this->getModel('process');
		$item = $model->getItem();	//Set the Id from request
		$model->move(1);

		$vars = array();
		JRequest::setVar( 'view'  , 'processes');
		JRequest::setVar( 'layout', 'default' );
		JRequest::setVar( 'cid', null );

		$this->setRedirect(JhacksHelper::urlRequest($vars));
	}

	function saveorder()
	{
		if (!$this->can('core.edit.state', JText::_("JHACKS_JTOOLBAR_CHANGE_ORDER")))
			return;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );


		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('process');
		$model->saveorder($cid, $order);


		$vars = array();
		JRequest::setVar( 'view'  , 'processes');
		JRequest::setVar( 'layout', 'default' );
		JRequest::setVar( 'cid', null );

		$this->setRedirect(JhacksHelper::urlRequest($vars));
	}
	function toggle_pro_publish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		if (!$this->can(array('core.edit', 'core.edit.own'), JText::_("JTOOLBAR_EDIT")))
			return;


		$model = $this->getModel('Process');
		$process = $model->getItem();


		if ($process->id == 0)
		{
			$msg = JText::_( 'ERROR' );
			$this->setRedirect(JhacksHelper::urlRequest(), $msg);
			return;
		}

		$data = array("pro_publish" => is_null($process->pro_publish)?1:!$process->pro_publish);
        $this->_save($data);

		$this->setRedirect(JhacksHelper::urlRequest());

	}



	function cloneItems()
	{
		// Check for request forgeries
		JRequest::checkToken() or JRequest::checkToken('get') or jexit( 'Invalid Token' );

		if (!$this->can('core.create', JText::_("JTOOLBAR_CLONEITEMS")))
				return;
				
        $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
        if (empty($cid))
			$cid = JRequest::getVar( 'cid', array(), 'get', 'array' );

        JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseWarning(500, JText::sprintf( "JHACKS_ALERT_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST_TO", strtolower(JText::_("PUBLISH")) ) );
		} else {

			$model = $this->getModel('process');
			$msg = $model->cloneItem($cid);
			if (!$msg){
				JError::raiseWarning( 1000, JText::_("ERROR") );	
			}
			
			JRequest::setVar( 'view'  , 'processes');

			$this->setRedirect(JhacksHelper::urlRequest(), $msg);
		}
	}


}
