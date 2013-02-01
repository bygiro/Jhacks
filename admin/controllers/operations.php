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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


/**
 * Jhacks Operations Controller
 *
 * @package		Joomla
 * @subpackage	Jhacks
 *
 */
class JhacksControllerOperations extends JhacksController
{
	var $ctrl = 'operations';
	var $singular = 'operation';

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
		//Hack > Name
		$filter_hack_id = $app->getUserState( $this->context . ".filter.hack_id");
		if ($filter_hack_id) $vars["filter_hack_id"] = $filter_hack_id;

		//Type
		$filter_type = $app->getUserState( $this->context . ".filter.type");
		if ($filter_type) $vars["filter_type"] = $filter_type;

		//Regex
		$filter_regex = $app->getUserState( $this->context . ".filter.regex");
		if ($filter_regex) $vars["filter_regex"] = $filter_regex;

		//Publish
		$filter_publish = $app->getUserState( $this->context . ".filter.publish");
		if ($filter_publish) $vars["filter_publish"] = $filter_publish;

		//Name
		$filter_op_name = $app->getUserState( $this->context . ".filter.op_name");
		if ($filter_op_name) $vars["filter_op_name"] = $filter_op_name;



		JRequest::setVar( 'cid', 0 );
		JRequest::setVar( 'view'  , 'operation');
		JRequest::setVar( 'layout', 'operation' );

		$this->setRedirect(JhacksHelper::urlRequest($vars));
	}

	function edit()
	{
		//Check Component ACL
		if (!$this->can(array('core.edit', 'core.edit.own'), JText::_("JTOOLBAR_EDIT")))
			return;

		$model = $this->getModel('operation');
		$item = $model->getItem();

		//Check Item ACL
		if (!$this->can('access-edit', JText::_("JTOOLBAR_EDIT"), $item->params))
			return;

		$vars = array();
		JRequest::setVar( 'view'  , 'operation');
		JRequest::setVar( 'layout', 'operation' );

		$this->setRedirect(JhacksHelper::urlRequest($vars));
	}

	function publish()
	{
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
			$model = $this->getModel('operation');
	        if ($model->publish($cid)){
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::_( 'DONE' ));

			} else
				JError::raiseWarning( 1000, JText::_("ERROR") );
		}

		$vars = array();
		JRequest::setVar( 'view'  , 'operations');
		JRequest::setVar( 'layout', 'default' );
		JRequest::setVar( 'cid', null );

		$this->setRedirect(JhacksHelper::urlRequest($vars));

	}

	function unpublish()
	{
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
			$model = $this->getModel('operation');
			if ($model->publish($cid, 0)){
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::_( 'DONE' ));

			} else
				JError::raiseWarning( 1000, JText::_("ERROR") );

		}

		$vars = array();
		JRequest::setVar( 'view'  , 'operations');
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


		$model = $this->getModel('operation');
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
			JRequest::setVar( 'view'  , 'operations');
			JRequest::setVar( 'layout', 'default' );
			JRequest::setVar( 'cid', null );

		}

		$this->setRedirect(JhacksHelper::urlRequest($vars));

	}

	function apply()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model = $this->getModel('operation');
		$item = $model->getItem();

		if ($model->getId() == 0)
		{	//New item

			if (!$this->can('core.create', JText::_("JTOOLBAR_APPLY")))
				return;
			$isnew = 1;
		}
		else
		{	//Existing item
			if (!$this->can(array('core.edit', 'core.edit.own'), JText::_("JTOOLBAR_APPLY")))
				return;

			//Check Item ACL
			if (!$this->can('access-edit', JText::_("JTOOLBAR_APPLY"), $item->params))
				return;
			$isnew = 0;
		}


		$post	= JRequest::get('post');
		$post['id'] = $model->getId();
		
		// get new id
		if ($post['id'] == 0){
			$post['id'] = JhacksHelper::getNextId('#__jhacks_operations');
		}
				


		//allow raw code HACK
		$post['search_key'] = JRequest::getVar('search_key', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['replacement'] = JRequest::getVar('replacement', '', 'post', 'string', JREQUEST_ALLOWRAW);

		$renameString = '{BASE}_-_'. $post['id'] .'.{EXT}';
		//UPLOAD FILE : Data File
		if (!$this->_upload($post['id'],'data_file', $post, array(), array('overwrite' => 'yes', 'rename' => $renameString )))
			return;

		if ($cid = parent::_apply($post))
		{
			$vars = array();
			JRequest::setVar( 'view'  , 'operation');
			JRequest::setVar( 'layout', 'operation' );

			$this->setRedirect(JhacksHelper::urlRequest($vars));
		}
		else{
			
			// cancella l'id inserito
			if ($isnew){
				JhacksHelper::deleteItem($post['id']);
			}
					
			//Keep the post and stay on page
			parent::display();
		}
	}



	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model = $this->getModel('operation');
		$item = $model->getItem();

		if ($model->getId() == 0)
		{	//New item

			if (!$this->can('core.create', JText::_("JTOOLBAR_SAVE")))
				return;
			$isnew = 1;
		}
		else
		{	//Existing item
			if (!$this->can(array('core.edit', 'core.edit.own'), JText::_("JTOOLBAR_SAVE")))
				return;

			//Check Item ACL
			if (!$this->can('access-edit', JText::_("JTOOLBAR_SAVE"), $item->params))
				return;
			$isnew = 0;
		}


		$post	= JRequest::get('post');
		$post['id'] = $model->getId();
		
		// get new id
		if ($post['id'] == 0){
			$post['id'] = JhacksHelper::getNextId('#__jhacks_operations');
		}
			
		//allow raw code HACK
		$post['search_key'] = JRequest::getVar('search_key', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['replacement'] = JRequest::getVar('replacement', '', 'post', 'string', JREQUEST_ALLOWRAW);

		$renameString = '{BASE}_-_'. $post['id'] .'.{EXT}';
		//UPLOAD FILE : Data File
		if (!$this->_upload($post['id'],'data_file', $post, array(), array('overwrite' => 'yes', 'rename' => $renameString )))
			return;

		if ($cid = parent::_save($post))
		{
			$vars = array();
			JRequest::setVar( 'view'  , 'operations');
			JRequest::setVar( 'layout', 'default' );
			JRequest::setVar( 'cid', null );

			$this->setRedirect(JhacksHelper::urlRequest($vars));
		}
		else{
			
			// cancella l'id inserito
			if ($isnew){
				JhacksHelper::deleteItem($post['id'], 'operation');
			}
							
			//Keep the post and stay on page
			parent::display();
		}

	}

	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );


		$vars = array();
		JRequest::setVar( 'view'  , 'operations');
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


		$model = $this->getModel('operation');
		$item = $model->getItem();	//Set the Id from request
		$model->move(-1);

		$vars = array();
		JRequest::setVar( 'view'  , 'operations');
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


		$model = $this->getModel('operation');
		$item = $model->getItem();	//Set the Id from request
		$model->move(1);

		$vars = array();
		JRequest::setVar( 'view'  , 'operations');
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

		$model = $this->getModel('operation');
		$model->saveorder($cid, $order);


		$vars = array();
		JRequest::setVar( 'view'  , 'operations');
		JRequest::setVar( 'layout', 'default' );
		JRequest::setVar( 'cid', null );

		$this->setRedirect(JhacksHelper::urlRequest($vars));
	}
	function toggle_publish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		if (!$this->can(array('core.edit', 'core.edit.own'), JText::_("JTOOLBAR_EDIT")))
			return;


		$model = $this->getModel('Operation');
		$operation = $model->getItem();


		if ($operation->id == 0)
		{
			$msg = JText::_( 'ERROR' );
			$this->setRedirect(JhacksHelper::urlRequest(), $msg);
			return;
		}

		$data = array("publish" => is_null($operation->publish)?1:!$operation->publish);
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

			$model = $this->getModel('operation');
			$msg = $model->cloneItem($cid);
			if (!$msg){
				JError::raiseWarning( 1000, JText::_("ERROR") );	
			}
			
			JRequest::setVar( 'view'  , 'operations');

			$this->setRedirect(JhacksHelper::urlRequest(), $msg);
		}
	}
}
