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

require_once(JPATH_ADMIN_JHACKS .DS.'classes'.DS.'jmodel.list.php');


/**
 * Jhacks Component Processes Model
 *
 * @package		Joomla
 * @subpackage	Jhacks
 *
 */
class JhacksModelProcesses extends JhacksModelList
{
	var $_name_sing = 'process';



	/**
	 * Constructor
	 *
	 */
	function __construct($config = array())
	{
		//Define the sortables fields (in lists)
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'pro_operation_id', 'a.pro_operation_id',
				'_pro_operation_id_op_name', '_pro_operation_id_.op_name',
				'_pro_operation_id_op_description', '_pro_operation_id_.op_description',
				'target_folder', 'a.target_folder',
				'target', 'a.target',
				'occurrences', 'a.occurrences',
				'original_md', 'a.original_md',
				'modified_md', 'a.modified_md',
				'pro_publish', 'a.pro_publish',
				'ordering', 'a.ordering',

			);
		}

		//Define the filterable fields
		$this->set('filter_vars', array(
			'pro_publish' => 'int',
			'pro_operation_id' => 'int'
				));

		//Define the filterable fields
		$this->set('search_vars', array(
			'search' => 'varchar'
				));



		parent::__construct($config);
		$this->_modes = array_merge($this->_modes, array('publish'));


	}




	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 *
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.



		return parent::getStoreId($id);
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
			case 'default': return $this->_buildQuery_default(); break;
			case 'getoperation': return $this->_buildQuery_getoperation(); break; 

		}



		$query = ' SELECT a.*'

			. $this->_buildQuerySelect()

			. ' FROM `#__jhacks_processes` AS a '

			. $this->_buildQueryJoin() . ' '

			. $this->_buildQueryWhere()


			. $this->_buildQueryOrderBy()
			. $this->_buildQueryExtra()
		;

		return $query;
	}

	function _buildQuery_default()
	{

		$query = ' SELECT a.*'
					.	' , _pro_operation_id_.op_name AS `_pro_operation_id_op_name`'
					.	' , _pro_operation_id_.op_description AS `_pro_operation_id_op_description`'
					.	' , _pro_operation_id_.type AS `_pro_operation_id_type`'

			. $this->_buildQuerySelect()

			. ' FROM `#__jhacks_processes` AS a '
					.	' LEFT JOIN `#__jhacks_operations` AS _pro_operation_id_ ON _pro_operation_id_.id = a.pro_operation_id'

			. $this->_buildQueryJoin() . ' '

			. $this->_buildQueryWhere()


			. $this->_buildQueryOrderBy()
			. $this->_buildQueryExtra()
		;

		return $query;
	}


	function _buildQuery_getoperation()
	{

		$query = ' SELECT a.*'
					.	' , _pro_operation_id_.hack_id AS `_pro_operation_id_hack_id`'
					.	' , _pro_operation_id_.type AS `_pro_operation_id_type`'
					.	' , _pro_operation_id_.replace_type AS `_pro_operation_id_replace_type`'
					.	' , _pro_operation_id_.regex AS `_pro_operation_id_regex`'
					.	' , _pro_operation_id_.search_key AS `_pro_operation_id_search_key`'
					.	' , _pro_operation_id_.replacement AS `_pro_operation_id_replacement`'
					.	' , _pro_operation_id_.data_file AS `_pro_operation_id_data_file`'
			. $this->_buildQuerySelect()

			. ' FROM `#__jhacks_processes` AS a '
					.	' LEFT JOIN `#__jhacks_operations` AS _pro_operation_id_ ON _pro_operation_id_.id = a.pro_operation_id'

			. $this->_buildQueryJoin() . ' '

			. $this->_buildQueryWhere()


			. $this->_buildQueryOrderBy()
			. $this->_buildQueryExtra()
		;

		return $query;
	}



	function _buildQueryWhere($where = array())
	{
		$app = JFactory::getApplication();
		$db= JFactory::getDBO();
		$acl = JhacksHelper::getAcl();


		if (isset($this->_active['filter']) && $this->_active['filter'])
		{
			$filter_pro_publish = $this->getState('filter.pro_publish');
			if ($filter_pro_publish != '')		$where[] = "a.pro_publish = " . $db->Quote($filter_pro_publish);

			//search_search : search on Operation ID > Name + Target Folder + Target
			$search_search = $this->getState('search.search');
			$this->_addSearch('search', '_pro_operation_id_.op_name', 'like');
			$this->_addSearch('search', 'a.target_folder', 'like');
			$this->_addSearch('search', 'a.target', 'like');
			if (($search_search != '') && ($search_search_val = $this->_buildSearch('search', $search_search)))
				$where[] = $search_search_val;

			$filter_pro_operation_id = $this->getState('filter.pro_operation_id');
			if ($filter_pro_operation_id != '')		$where[] = "a.pro_operation_id = " . (int)$filter_pro_operation_id . "";


		}
		if (!$acl->get('core.edit.state')
		&& (!isset($this->_active['publish']) || $this->_active['publish'] !== false))
				$where[] = "a.pro_publish=1";


		return parent::_buildQueryWhere($where);
	}

	function _buildQueryOrderBy($order = array(), $pre_order = 'a.ordering, a.pro_operation_id')
	{

		return parent::_buildQueryOrderBy($order, $pre_order);
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
		$acl = JhacksHelper::getAcl();
		if (!isset($this->_data))
			return;

		// Convert the parameter fields into objects.
		foreach ($this->_data as &$item)
		{

			if ($acl->get('core.edit.state')
				|| (bool)$item->pro_publish)
				$item->params->set('access-view', true);

			if ($acl->get('core.edit'))
				$item->params->set('access-edit', true);

			if ($acl->get('core.delete'))
				$item->params->set('access-delete', true);


		}

	}

}
