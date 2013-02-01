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


jimport('joomla.application.component.model');
require_once(JPATH_ADMIN_JHACKS .DS.'classes'.DS.'jmodel.item.php');

/**
 * Jhacks Component Activity Model
 *
 * @package		Joomla
 * @subpackage	Jhacks
 *
 */
class JhacksModelActivity extends JhacksModelItem
{
	var $_name_plur = 'logs';
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
	 * Method to initialise the activity data
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
			$data->type_item = JRequest::getVar('filter_type_item', $this->getState('filter.type_item'));
			$data->type_task = JRequest::getVar('filter_type_task', $this->getState('filter.type_task'));
			$data->result = JRequest::getVar('filter_result', $this->getState('filter.result'));
			$data->lg_creation_date = null;
			$data->details = null;

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

		if ($filter_type_item = $app->getUserState($this->context.'.filter.type_item'))
			$this->setState('filter.type_item', $filter_type_item, null, 'varchar');

		if ($filter_type_task = $app->getUserState($this->context.'.filter.type_task'))
			$this->setState('filter.type_task', $filter_type_task, null, 'varchar');

		if ($filter_result = $app->getUserState($this->context.'.filter.result'))
			$this->setState('filter.result', $filter_result, null, 'varchar');

		if ($search_search = $app->getUserState($this->context.'.search.search'))
			$this->setState('search.search', $search_search, null, 'varchar');



		parent::populateState();
	}


	/**
	 * Method to build a the query string for the Activity
	 *
	 * @access public
	 * @return integer
	 */
	function _buildQuery()
	{

		if (isset($this->_active['predefined']))
		switch($this->_active['predefined'])
		{
			case 'logactivity': return $this->_buildQuery_logactivity(); break;

		}



			$query = 'SELECT a.*'
					. 	$this->_buildQuerySelect()

					.	' FROM `#__jhacks_logs` AS a'
					. 	$this->_buildQueryJoin()

					. 	$this->_buildQueryWhere()

					.	'';

		return $query;
	}

	function _buildQuery_logactivity()
	{

			$query = 'SELECT a.*'
					. 	$this->_buildQuerySelect()

					.	' FROM `#__jhacks_logs` AS a'
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



		return parent::_buildQueryWhere($where);
	}

	/**
	 * Method to update activity in mass
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
	 * Method to save the activity
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
	 * Method to delete a activity
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

			$query = 'DELETE FROM `#__jhacks_logs`'
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

		$item->params->set('access-view', true);

		if ($acl->get('core.edit'))
			$item->params->set('access-edit', true);

		if ($acl->get('core.delete'))
			$item->params->set('access-delete', true);



	}




}