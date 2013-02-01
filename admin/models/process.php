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


jimport('joomla.application.component.model');
require_once(JPATH_ADMIN_JHACKS .DS.'classes'.DS.'jmodel.item.php');

/**
 * Jhacks Component Process Model
 *
 * @package		Joomla
 * @subpackage	Jhacks
 *
 */
class JhacksModelProcess extends JhacksModelItem
{
	var $_name_plur = 'processes';
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
	 * Method to initialise the process data
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
			$data->pro_operation_id = JRequest::getInt('filter_pro_operation_id', $this->getState('filter.pro_operation_id'));
			$data->target_folder = null;
			$data->target = null;
			$data->occurrences = null;
			$data->conflicts = null;
			$data->ordering = 0;
			$data->pro_publish = 0;
			$data->original_md = null;
			$data->modified_md = null;

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

		if ($filter_pro_publish = $app->getUserState($this->context.'.filter.pro_publish'))
			$this->setState('filter.pro_publish', $filter_pro_publish, null, 'cmd');

		if ($search_search = $app->getUserState($this->context.'.search.search'))
			$this->setState('search.search', $search_search, null, 'varchar');



		parent::populateState();
	}


	/**
	 * Method to build a the query string for the Process
	 *
	 * @access public
	 * @return integer
	 */
	function _buildQuery()
	{

		if (isset($this->_active['predefined']))
		switch($this->_active['predefined'])
		{
			case 'process': return $this->_buildQuery_process(); break;

		}



			$query = 'SELECT a.*'
					. 	$this->_buildQuerySelect()

					.	' FROM `#__jhacks_processes` AS a'
					. 	$this->_buildQueryJoin()

					. 	$this->_buildQueryWhere()

					.	'';

		return $query;
	}

	function _buildQuery_process()
	{

			$query = 'SELECT a.*'
					.	' , _pro_operation_id_.op_name AS `_pro_operation_id_op_name`'
					.	' , _pro_operation_id_.ordering AS `_pro_operation_id_ordering`'
					.	' , _pro_operation_id_.access AS `_pro_operation_id_access`'
					.	' , _pro_operation_id_.publish AS `_pro_operation_id_publish`'
					.	' , _pro_operation_id_hack_id_.ordering AS `hack_ordering`' // hack
					.	' , _pro_operation_id_.hack_id AS `_pro_operation_id_hack_id`'
					.	' , _pro_operation_id_.op_description AS `_pro_operation_id_op_description`'
					.	' , _pro_operation_id_.type AS `_pro_operation_id_type`'
					.	' , _pro_operation_id_.replace_type AS `_pro_operation_id_replace_type`'
					.	' , _pro_operation_id_.regex AS `_pro_operation_id_regex`'
					.	' , _pro_operation_id_.search_key AS `_pro_operation_id_search_key`'
					.	' , _pro_operation_id_.replacement AS `_pro_operation_id_replacement`'
					.	' , _pro_operation_id_.data_file AS `_pro_operation_id_data_file`'
					.	' , _pro_operation_id_hack_id_.name AS `_pro_operation_id_hack_id_name`'
					. 	$this->_buildQuerySelect()

					.	' FROM `#__jhacks_processes` AS a'
					.	' LEFT JOIN `#__jhacks_operations` AS _pro_operation_id_ ON _pro_operation_id_.id = a.pro_operation_id'
					.	' LEFT JOIN `#__jhacks_hacks` AS _pro_operation_id_hack_id_ ON _pro_operation_id_hack_id_.id = _pro_operation_id_.hack_id'
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
	 * Method to update process in mass
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
	 * Method to save the process
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

		
		if ($row->pro_publish == 1 OR $data['pro_publish'] == 1){
			JError::raiseWarning(1000, JText::_("JHACKS_ERROR_UNPUBLISH_ALL_RELATED_PROCESSES_FIRST") );
			return false;
		}
					
			
		//Some security checks
		$acl = JhacksHelper::getAcl();

		//Secure the published tag if not allowed to change
		if (isset($data['pro_publish']) && !$acl->get('core.edit.state'))
			unset($data['pro_publish']);


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

		
		$check = new JhacksChecking();
		$check->checkProcesses();		
		

		return true;
	}
	/**
	 * Method to delete a process
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

			$query = 'DELETE FROM `#__jhacks_processes`'
				. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				JError::raiseWarning(1000, $this->_db->getErrorMsg());
				return false;
			}
			
			
			$check = new JhacksChecking();
			$check->checkProcesses();		
						



		}

		return true;
	}
	/**
	 * Method to (un)publish a process
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function publish($cid = array(), $publish){
		
		$publish_msg = ' Unpublished';
		if($publish){
			$publish_msg = ' Published';
		}
		$user 	= JFactory::getUser();
		$cid2publish = array();

		foreach($cid as $d){
			// get data and check the publish state
			$this->active('predefined', 'process');
			$item = $this->getItem($d);

			$logData = '';
			$logError = '';
			if($item->pro_publish != $publish){
				$gohack = new JhacksFindReplace();
				if($gohack->publish($item, $publish)){
					$logData .= 'Process Id: ' . $d . $publish_msg .'<br />';
					$cid2publish[] = $d;
				} else {
					$logError .= $gohack->logErrors;
				}
			} else {
				if($item->pro_publish){
					$logData .= 'Process Id: '. $d .' already published<br />';
				} else {
					$logData .= 'Process Id: '. $d .' already unpublished<br />';
				}
			}
		}
		
		if (count( $cid2publish ))
		{
			JArrayHelper::toInteger($cid2publish);
			$cids = implode( ',', $cid2publish );

			$query = 'UPDATE #__jhacks_processes'
				. ' SET `pro_publish` = '.(int) $publish
				. ' WHERE id IN ( '.$cids.' )'


			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				JError::raiseWarning(1000, $this->_db->getErrorMsg());
				return false;
			}
		}

		$message = new stdClass;
		$message->logErrors = $logError;
		$message->logData = $logData;	
		$message->result = 'Failed';
		if($message->logErrors == ''){
			$message->result = 'Successful';			
		}
		return $message;
	}
	/**
	 * Method to move a process
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
	 * Method to save the order of the processes
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
			|| (bool)$item->pro_publish)
			$item->params->set('access-view', true);

		if ($acl->get('core.edit'))
			$item->params->set('access-edit', true);

		if ($acl->get('core.delete'))
			$item->params->set('access-delete', true);



	}
	/**
	 * Method to cascad delete processes items
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function integrityDelete($key, $cid = array())
	{

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			$query = 'SELECT id FROM #__jhacks_processes'
				. " WHERE `" . $key . "` IN ( " . $cids . " )";
			$this->_db->setQuery( $query );

			$list = $this->_getList($query);

			$cidsDelete = array();
			if (count($list) > 0)
				foreach($list as $item)
					$cidsDelete[] = $item->id;

			return $this->delete($cidsDelete);

		}

		return true;
	}

	function cloneItem($ids, $op_id = null)
	{
		$result = '';
		foreach($ids as $pro_id)
		{
			$item = $this->getItem($pro_id);

			$clone->id = 0;
			$clone->pro_publish = 0;
			$clone->occurrences = 0;
			$clone->conflicts = null;
			$clone->target_folder = $item->target_folder;
			$clone->target = $item->target;

			$model = JModel::getInstance('process', 'JhacksModel');
		
			$clone->pro_operation_id = $item->pro_operation_id;
			if($op_id){
				$clone->pro_operation_id = $op_id;
			}
				
			if (!$model->save($clone)) {
				$result .= 'Cannot clone the process ID: '. $pro_id .'<br />';
			} else {
				$result .= "Process ID: ". $pro_id ." - CLONED!<br />";
			}
		}
		
		return $result;
	}

	function integrityPublish($key, $cid = array(), $publish)
	{

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			$query = 'SELECT id FROM #__jhacks_processes'
				. " WHERE `" . $key . "` IN ( " . $cids . " )";
			$this->_db->setQuery( $query );

			$list = $this->_getList($query);

			$cidsPublish = array();
			if (count($list) > 0)
				foreach($list as $item)
					$cidsPublish[] = $item->id;

			return $this->publish($cidsPublish, $publish);

		}

		return true;
	}	
	

}