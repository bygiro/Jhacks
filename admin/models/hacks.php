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

require_once(JPATH_ADMIN_JHACKS .DS.'classes'.DS.'jmodel.list.php');


/**
 * Jhacks Component Hacks Model
 *
 * @package		Joomla
 * @subpackage	Jhacks
 *
 */
class JhacksModelHacks extends JhacksModelList
{
	var $_name_sing = 'hack';



	/**
	 * Constructor
	 *
	 */
	function __construct($config = array())
	{
		//Define the sortables fields (in lists)
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'name', 'a.name',
				'joomla_version', 'a.joomla_version',
				'jks_version', 'a.jks_version',
				'hack_version', 'a.hack_version',
				'element', 'a.element',
				'type', 'a.type',
				'client', 'a.client',
				'element_version', 'a.element_version',
				'author', 'a.author',
				'author_website', 'a.author_website',
				'copyright', 'a.copyright',
				'license', 'a.license',
				'updates', 'a.updates',
				'date_added', 'a.date_added',
				'last_update', 'a.last_update',
				'access', 'a.access',
				'ordering', 'a.ordering',
				'publish', 'a.publish',

			);
		}

		//Define the filterable fields
		$this->set('filter_vars', array(
			'publish' => 'int',
			'type' => 'string',
			'client' => 'string',
			'joomla_version' => 'string',
			'jks_version' => 'string'
				));

		//Define the filterable fields
		$this->set('search_vars', array(
			'search' => 'varchar'
				));



		parent::__construct($config);
		$this->_modes = array_merge($this->_modes, array('access', 'publish'));


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
			case 'hacks': return $this->_buildQuery_hacks(); break;
			case 'default': return $this->_buildQuery_default(); break;

		}



		$query = ' SELECT a.*'

			. $this->_buildQuerySelect()

			. ' FROM `#__jhacks_hacks` AS a '

			. $this->_buildQueryJoin() . ' '

			. $this->_buildQueryWhere()


			. $this->_buildQueryOrderBy()
			. $this->_buildQueryExtra()
		;

		return $query;
	}

	function _buildQuery_hacks()
	{

		$query = ' SELECT a.*'

			. $this->_buildQuerySelect()

			. ' FROM `#__jhacks_hacks` AS a '

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

			. $this->_buildQuerySelect()

			. ' FROM `#__jhacks_hacks` AS a '

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
			$filter_publish = $this->getState('filter.publish');
			if ($filter_publish != '')		$where[] = "a.publish = " . $db->Quote($filter_publish);

			$filter_type = $this->getState('filter.type');
			if ($filter_type != '')		$where[] = "a.type = " . $db->Quote($filter_type);

			$filter_client = $this->getState('filter.client');
			if ($filter_client != '')		$where[] = "a.client = " . $db->Quote($filter_client);

			$filter_joomla_version = $this->getState('filter.joomla_version');
			if ($filter_joomla_version != '')		$where[] = "a.joomla_version = " . $db->Quote($filter_joomla_version);

			$filter_jks_version = $this->getState('filter.jks_version');
			if ($filter_jks_version != '')		$where[] = "a.jks_version = " . $db->Quote($filter_jks_version);

			//search_search : search on Name + Description + Warnings +  + Element + Author + 
			$search_search = $this->getState('search.search');
			$this->_addSearch('search', 'a.name', 'like');
			$this->_addSearch('search', 'a.description', 'like');
			$this->_addSearch('search', 'a.warnings', 'like');
			$this->_addSearch('search', 'a.element', 'like');
			$this->_addSearch('search', 'a.author', 'like');
			if (($search_search != '') && ($search_search_val = $this->_buildSearch('search', $search_search)))
				$where[] = $search_search_val;


		}
		if (!isset($this->_active['access']) || $this->_active['access'] !== false)
		{
			$acl = JhacksHelper::getAcl();

			$user 	= JFactory::getUser();
			$levels	= implode(',', $user->getAuthorisedViewLevels());

			//Admin access always pass, in case of a broken access key value.
			if (!$acl->get('core.admin'))
				$where[] = "(a.access = '' OR a.access IN (" .$levels. "))";
		}
		if (!$acl->get('core.edit.state')
		&& (!isset($this->_active['publish']) || $this->_active['publish'] !== false))
				$where[] = "a.publish=1";


		return parent::_buildQueryWhere($where);
	}

	function _buildQueryOrderBy($order = array(), $pre_order = 'a.ordering, a.name')
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
				|| (bool)$item->publish)
				$item->params->set('access-view', true);

			if ($acl->get('core.edit'))
				$item->params->set('access-edit', true);

			if ($acl->get('core.delete'))
				$item->params->set('access-delete', true);


		}

	}

}
