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


jimport('joomla.application.component.model');
require_once(JPATH_ADMIN_JHACKS .DS.'classes'.DS.'jmodel.item.php');

/**
 * Jhacks Component Operation Model
 *
 * @package		Joomla
 * @subpackage	Jhacks
 *
 */
class JhacksModelOperation extends JhacksModelItem
{
	var $_name_plur = 'operations';
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
	 * Method to initialise the operation data
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
			$data->hack_id = JRequest::getInt('filter_hack_id', $this->getState('filter.hack_id'));
			$data->op_name = null;
			$data->op_description = null;
			$data->type = JRequest::getVar('filter_type', $this->getState('filter.type'));
			$data->replace_type = JRequest::getVar('filter_replace_type', $this->getState('filter.replace_type', 'justreplace'));
			$data->regex = 0;
			$data->search_key = null;
			$data->replacement = null;
			$data->data_file = null;
			$data->access = 3;
			$data->ordering = 0;
			$data->publish = 0;

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

		if ($filter_hack_id = $app->getUserState($this->context.'.filter.hack_id'))
			$this->setState('filter.hack_id', $filter_hack_id, null, 'int');

		if ($filter_type = $app->getUserState($this->context.'.filter.type'))
			$this->setState('filter.type', $filter_type, null, 'varchar');

		if ($filter_regex = $app->getUserState($this->context.'.filter.regex'))
			$this->setState('filter.regex', $filter_regex, null, 'cmd');

		if ($filter_publish = $app->getUserState($this->context.'.filter.publish'))
			$this->setState('filter.publish', $filter_publish, null, 'cmd');

		if ($search_search = $app->getUserState($this->context.'.search.search'))
			$this->setState('search.search', $search_search, null, 'varchar');



		parent::populateState();
	}


	/**
	 * Method to build a the query string for the Operation
	 *
	 * @access public
	 * @return integer
	 */
	function _buildQuery()
	{

		if (isset($this->_active['predefined']))
		switch($this->_active['predefined'])
		{
			case 'operation': return $this->_buildQuery_operation(); break;

		}



			$query = 'SELECT a.*'
					. 	$this->_buildQuerySelect()

					.	' FROM `#__jhacks_operations` AS a'
					. 	$this->_buildQueryJoin()

					. 	$this->_buildQueryWhere()

					.	'';

		return $query;
	}

	function _buildQuery_operation()
	{

			$query = 'SELECT a.*'
					.	' , _hack_id_.name AS `_hack_id_name`'
					.	' , _hack_id_.description AS `_hack_id_description`'
					. 	$this->_buildQuerySelect()

					.	' FROM `#__jhacks_operations` AS a'
					.	' LEFT JOIN `#__jhacks_hacks` AS _hack_id_ ON _hack_id_.id = a.hack_id'
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
	 * Method to update operation in mass
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
	 * Method to save the operation
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

		
		JhacksHelper::operationStatus($row);
		
		if ($row->publish == 1){
			JError::raiseWarning(1000, JText::_("JHACKS_ERROR_UNPUBLISH_ALL_RELATED_PROCESSES_FIRST") );
			return false;
		}
		
		if ($data['publish'] == 1){
			JError::raiseWarning(1000, JText::_("JHACKS_ERROR_SAVE_FIRST_THEN_PUBLISH") );
			return false;
		}				
		
			
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
	 * Method to delete a operation
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
			if (!$this->_deleteFiles($cids, array(
										'data_file' => 'delete'
												))){}

			$query = 'DELETE FROM `#__jhacks_operations`'
				. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				JError::raiseWarning(1000, $this->_db->getErrorMsg());
				return false;
			}

			//Integrity : Cascade delete in process on pro_operation_id
			$model = JModel::getInstance('process', 'JhacksModel');
			if (!$model->integrityDelete('pro_operation_id', $cid))
			{
				JError::raiseWarning( 1010, JText::_("JHACKS_ALERT_ERROR_ON_CASCAD_DELETE") );
				return false;
			}



		}

		return true;
	}
	/**
	 * Method to (un)publish a operation
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
			$model = JModel::getInstance('process', 'JhacksModel');
			if (!$model->integrityPublish('pro_operation_id', $cid, $publish))
			{
				JError::raiseWarning( 1010, JText::_("JHACKS_ALERT_ERROR_ON_CASCAD_DELETE") );
				return false;
			}
			
			
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__jhacks_operations'
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
	 * Method to move a operation
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
	 * Method to save the order of the operations
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
			|| (bool)$item->publish)
			$item->params->set('access-view', true);

		if ($acl->get('core.edit'))
			$item->params->set('access-edit', true);

		if ($acl->get('core.delete'))
			$item->params->set('access-delete', true);



	}
	/**
	 * Method to cascad delete operations items
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
			$query = 'SELECT id FROM #__jhacks_operations'
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

	
	function cloneItem($ids, $hack_id = null)
	{
		$model = JModel::getInstance('process', 'JhacksModel');
		$result = '';
		$todelete = array();
		foreach($ids as $opid)
		{
			$error = '';
			$item = $this->getItem($opid);			
			$new_id = JhacksHelper::getNextId('#__jhacks_operations');
			
			$filename = $item->data_file;
			if ($filename != '') {
				if (!JhacksHelper::clonefile($opid, $filename, $new_id)){
					$result .= "I cannot clone the file: $filename contained in the operation ID: ". $opid;
					$todelete[] = $new_id;
					$error = 1;
				} else {
					$item->data_file = JhacksHelper::getRealFilename($item->data_file);
					$item->data_file = JhacksHelper::makeOperationFilename($item->data_file, $new_id);					
				}
			}
			
			$item->id = $new_id;
			$item->publish = 0;
			$item->op_name = 'COPY OF ' . $item->op_name;
			$item->op_modification_date = '';
			
			if($hack_id){
				$item->hack_id = $hack_id;
			}
				
			if (!$error){
				if (!$this->save($item)) {
					$todelete[] = $new_id;
					$result .= 'Cannot clone the operation ID: '.$opid;
				} else {
					$result .= "Operation ID: ". $opid ." - CLONED!<br />";
					
					// clone the processes related to the operation ID
					$processes = JModel::getInstance('processes', 'JhacksModel');
					$processes->addWhere(' a.pro_operation_id = '. $opid);
					
					$pro_ids = array();
					foreach ($processes->getItems() as $pro){
					
						$pro_ids[] = $pro->id;
					}
					$result .= $model->cloneItem($pro_ids, $new_id);
				}
			}
		}
		
		if (!empty($todelete)){
			// delete the operation cloned with error
			$this->delete($todelete);
		}
		
		return $result;
	}

	function integrityPublish($key, $cid = array(), $publish)
	{

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			$query = 'SELECT id FROM #__jhacks_operations'
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