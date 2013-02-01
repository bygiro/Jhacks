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


jimport('joomla.application.component.model');
require_once(JPATH_ADMIN_JHACKS .DS.'classes'.DS.'jmodel.item.php');

/**
 * Jhacks Component Hack Model
 *
 * @package		Joomla
 * @subpackage	Jhacks
 *
 */
class JhacksModelHack extends JhacksModelItem
{
	var $_name_plur = 'hacks';
	var $params;



	/**
	 * Constructor
	 *
	 */
	function __construct()
	{
		parent::__construct();
		$this->_modes = array_merge($this->_modes, array(''));

	}

	/**
	 * Method to initialise the hack data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	function _initData()
	{
		if (empty($this->_data))
		{
			//Default values shown in the form for new item creation
			$data = new stdClass();

			$data->id = 0;
			$data->attribs = null;
			$data->name = null;
			$data->description = null;
			$data->warnings = null;
			$data->joomla_version = JRequest::getVar('filter_joomla_version', $this->getState('filter.joomla_version', 'j25'));
			$data->jks_version = JRequest::getVar('filter_jks_version', $this->getState('filter.jks_version', 'jhacks02'));
			$data->hack_version = null;
			$data->element = null;
			$data->type = JRequest::getVar('filter_type', $this->getState('filter.type', 'undefined'));
			$data->client = JRequest::getVar('filter_client', $this->getState('filter.client', 'site'));
			$data->element_version = null;
			$data->author = null;
			$data->author_website = null;
			$data->author_email = null;
			$data->copyright = null;
			$data->license = null;
			$data->updates = null;
			$data->date_added = null;
			$data->last_update = null;
			$data->access = 3;
			$data->ordering = 0;
			$data->publish = 0;
			$data->locked = null;

			$this->_data = $data;

			return (boolean) $this->_data;
		}
		return true;
	}


	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();

		if ($filter_publish = $app->getUserState($this->context.'.filter.publish'))
			$this->setState('filter.publish', $filter_publish, null, 'cmd');

		if ($filter_type = $app->getUserState($this->context.'.filter.type'))
			$this->setState('filter.type', $filter_type, null, 'varchar');

		if ($filter_client = $app->getUserState($this->context.'.filter.client'))
			$this->setState('filter.client', $filter_client, null, 'varchar');

		if ($filter_joomla_version = $app->getUserState($this->context.'.filter.joomla_version'))
			$this->setState('filter.joomla_version', $filter_joomla_version, null, 'varchar');

		if ($filter_jks_version = $app->getUserState($this->context.'.filter.jks_version'))
			$this->setState('filter.jks_version', $filter_jks_version, null, 'varchar');

		if ($search_search = $app->getUserState($this->context.'.search.search'))
			$this->setState('search.search', $search_search, null, 'varchar');



		parent::populateState();
	}


	/**
	 * Method to build a the query string for the Hack
	 *
	 * @access public
	 * @return integer
	 */
	function _buildQuery()
	{

		if (isset($this->_active['predefined']))
		switch($this->_active['predefined'])
		{
			case 'hack': return $this->_buildQuery_hack(); break;

		}



			$query = 'SELECT a.*'
					. 	$this->_buildQuerySelect()

					.	' FROM `#__jhacks_hacks` AS a'
					. 	$this->_buildQueryJoin()

					. 	$this->_buildQueryWhere()

					.	'';

		return $query;
	}

	function _buildQuery_hack()
	{

			$query = 'SELECT a.*'
					. 	$this->_buildQuerySelect()

					.	' FROM `#__jhacks_hacks` AS a'
					. 	$this->_buildQueryJoin()

					. 	$this->_buildQueryWhere()

					.	'';

		return $query;
	}



	function _buildQueryWhere($where = array())
	{
		$app = JFactory::getApplication();
		$acl = JhacksHelper::getAcl();

		$where[] = 'a.id = '.(int) $this->_id;

		if (!isset($this->_active['access']) || $this->_active['access'] !== false)
		{
			$acl = JhacksHelper::getAcl();

			$user 	= JFactory::getUser();
			$levels	= implode(',', $user->getAuthorisedViewLevels());

			//Admin access always pass, in case of a broken access key value.
			if (!$acl->get('core.admin'))
				$where[] = "(a.access = '' OR a.access IN (" .$levels. "))";
		}


		return parent::_buildQueryWhere($where);
	}

	/**
	 * Method to update hack in mass
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function update($cids, $data)
	{
		foreach($cids as $cid)
		{
			if ($cid == 0)
				continue;
			$data['id'] = $cid;
			if (!$this->save($data))
				return false;
		}
		return true;
	}

	/**
	 * Method to save the hack
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function save($data)
	{

		$row = $this->getTable();



		//Convert data from a stdClass
		if (is_object($data)){
			if (get_class($data) == 'stdClass')
				$data = JArrayHelper::fromObject($data);
		}

		//Current id if unspecified
		if ($data['id'] != null)
			$id = $data['id'];
		else if (($this->_id != null) && ($this->_id > 0))
			$id = $this->_id;


		//Load the current object, in order to process an update
		if (isset($id))
			$row->load($id);
		
		
		JhacksHelper::hackStatus($row); 
		 
		if ($row->publish == 1){
			JError::raiseWarning(1000, JText::_("JHACKS_ERROR_UNPUBLISH_ALL_RELATED_PROCESSES_FIRST") );
			return false;
		}
		
		unset($data['publish']);		
		
		
		//Some security checks
		$acl = JhacksHelper::getAcl();

		//Secure the published tag if not allowed to change
		if (isset($data['publish']) && !$acl->get('core.edit.state'))
			unset($data['publish']);

		//Secure the access key if not allowed to change
		if (isset($data['access']) && !$acl->get('core.edit'))
			unset($data['access']);


		// Bind the form fields to the jhacks table
		$ignore = array();
		if (!$row->bind($data, $ignore)) {
			JError::raiseWarning(1000, $this->_db->getErrorMsg());
			return false;
		}





		// Make sure the jhacks table is valid
		if (!$row->check()) {
			JError::raiseWarning(1000, $this->_db->getErrorMsg());
			return false;
		}



		// Store the jhacks table to the database
		if (!$row->store())
        {
			JError::raiseWarning(1000, $this->_db->getErrorMsg());
			return false;
		}



		$this->_id = $row->id;
		$this->_data = $row;



		return true;
	}
	/**
	 * Method to delete a hack
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete($cid = array())
	{
		$result = false;

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'DELETE FROM `#__jhacks_hacks`'
				. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				JError::raiseWarning(1000, $this->_db->getErrorMsg());
				return false;
			}

			//Integrity : Cascade delete in operation on hack_id
			$model = JModel::getInstance('operation', 'JhacksModel');
			if (!$model->integrityDelete('hack_id', $cid))
			{
				JError::raiseWarning( 1010, JText::_("JHACKS_ALERT_ERROR_ON_CASCAD_DELETE") );
				return false;
			}
			
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			
			$query = 'DELETE FROM `#__jhacks_hacks`'
				. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				JError::raiseWarning(1000, $this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
	/**
	 * Method to move a hack
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function move($direction)
	{
		$row = $this->getTable();
		if (!$row->load($this->_id)) {
			JError::raiseWarning(1000, $this->_db->getErrorMsg());
			return false;
		}

		$condition = "1";


		if (!$row->move( $direction,  $condition)) {
			JError::raiseWarning(1000, $this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	/**
	 * Method to save the order of the hacks
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function saveorder($cid = array(), $order)
	{
		$row = $this->getTable();

		// update ordering values
		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					JError::raiseWarning(1000, $this->_db->getErrorMsg());
					return false;
				}
			}
		}

		$row->reorder();


		return true;
	}
	/**
	 * Method to (un)publish a hack
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function publish($cid = array(), $publish = 1)
	{
		$user 	= JFactory::getUser();

		if (count( $cid ))
		{
			
			
			//Integrity : Cascade publish in operation on hack_id
			$model = JModel::getInstance('operation', 'JhacksModel');
			if (!$model->integrityPublish('hack_id', $cid, $publish))
			{
				JError::raiseWarning( 1010, JText::_("JHACKS_ALERT_ERROR_ON_CASCAD_PUBLISH") );
				return false;
			}
					
			
			
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__jhacks_hacks'
				. ' SET `publish` = '.(int) $publish
				. ' WHERE id IN ( '.$cids.' )'


			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				JError::raiseWarning(1000, $this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
	/**
	 * Method to Convert the parameter fields into objects.
	 *
	 * @access public
	 * @return void
	 */
	protected function populateParams()
	{
		parent::populateParams();

		if (!isset($this->_data))
			return;

		$item = $this->_data;
		$acl = JhacksHelper::getAcl();

		if ($acl->get('core.edit.state')
			|| (bool)$item->publish)
			$item->params->set('access-view', true);

		if ($acl->get('core.edit'))
			$item->params->set('access-edit', true);

		if ($acl->get('core.delete'))
			$item->params->set('access-delete', true);



	}

}