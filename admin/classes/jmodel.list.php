<?php

/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V1.5.2   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		0.4.0
* @package		Jhacks
* @subpackage	Viewlevels
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

jimport('joomla.application.component.modellist');

/**
 * Jhacks Component JhacksModelList Model
 *
 * @package		Joomla
 * @subpackage	Jhacks
 *
 */
class JhacksModelList extends JModelList
{
	/**
	 * data array
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * total
	 *
	 * @var integer
	 */
	var $_total = null;

	/**
	 * Pagination object
	 *
	 * @var object
	 */
	var $_pagination = null;

	/**
	 * Model mode activations
	 *
	 * @var _active
	 */
	 var $_active;

	 /**
	 * Model modes
	 *
	 * @var _active
	 */
	 var $_modes;

	/**
	 * Select object
	 *
	 * @var array
	 */
	var $_select = array();

	/**
	 * Join object
	 *
	 * @var array
	 */
	var $_join = array();

	/**
	 * Order object
	 *
	 * @var array
	 */
	var $_order = array();

	/**
	 * Where object
	 *
	 * @var array
	 */
	var $_where = array();

	/**
	 * Extra object
	 *
	 * @var array
	 */
	var $_extra = array();

	/**
	 * GroupBy object
	 *
	 * @var array
	 */
	var $_groupby = array();

	/**
	 * Filterable fields keys
	 *
	 * @var array
	 */
	protected $filter_vars = array();

	/**
	 * Search entries
	 *
	 * @var array
	 */
	protected $search_vars = array();


	/**
	 * Indicates if the internal state has been set
	 *
	 * @var    boolean
	 * @since  11.1
	 */
	protected $stateSet = null;




	/**
	 * Constructor
	 *
	 */
	function __construct($config = array())
	{
		parent::__construct($config);


		$layout = JRequest::getCmd('layout');
		$render	= JRequest::getCmd('render');

		$this->context = strtolower($this->option . '.' . $this->getName()
					. ($layout?'.' . $layout:'')
					. ($render?'.' . $render:'')
					);

		$this->_active = array();
		$this->_modes = array('filter', 'order', 'pagination', 'predefined');
		$this->activeAll(null);
	}

	/**
	 * Deprecated
	 * Method to get the datas
	 *
	 * @access public
	 * @return array
	 */
	function getData()
	{
		return $this->getItems();
	}


	/**
	 * Method to get the items
	 *
	 * @access public
	 * @return array
	 */
	function getItems()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{

			$query = $this->_buildQuery();

			if ($this->_active['pagination'])
				$this->_data = $this->_getList($query, $this->getState('list.start'), $this->getState('list.limit'));
			else
				$this->_data = $this->_getList($query);

	 	    $this->_total = $this->_getListCount($query);


			$this->populateObjects();
		}
		return $this->_data;
	}

	/**
	 * Method to get the total number of items
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
			$this->getData();

		return $this->_total;
	}


	/**
	 * Method to prepare the related oblects
	 *
	 * @access public
	 * @return void
	 */
	protected function populateObjects()
	{
		$this->populateParams();
	}

	/**
	 * Method to Convert the parameter fields into objects.
	 *
	 * @access public
	 * @return void
	 */
	protected function populateParams()
	{
		if (!isset($this->_data))
			return;

		// Convert the parameter fields into objects.
		foreach ($this->_data as &$item)
		{

// TODO : attribs
//			$itemParams = new JRegistry;
//			$itemParams->loadString((isset($item->attribs)?$item->attribs:$item->params));

			//$item->params = clone $this->getState('params');

			$item->params = new JObject();

		}


	}

	/**
	 * Method to get a pagination object
	 *
	 * @access public
	 * @return integer
	 */
	function getPagination()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(),
					$this->getState('list.start'),$this->getState('list.limit') );
		}
		return $this->_pagination;
	}

	/**
	 * Method to build a the query string
	 *
	 * @access public
	 * @return integer
	 */
	function _buildQuery()
	{
		//Abstract

	}

	function activeAll($active = true)
	{
		foreach($this->_modes as $mode)
		{
			$this->active($mode, $active);
		}
	}

	function active($mode, $active = true)
	{
		$this->_active[$mode] = $active;
	}

	function addSelect($select_clause)
	{
		$this->_select[] = $select_clause;
	}

	function addJoin($join_clause)
	{
		$this->_join[] = $join_clause;
	}

	function addWhere($where_clause)
	{
		$this->_where[] = $where_clause;
	}

	function addOrder($order_clause)
	{
		$this->_order[] = $order_clause;
	}

	function addExtra($extra_clause)
	{
		$this->_extra[] = $extra_clause;
	}

	function addGroupBy($groupby_clause)
	{
		$this->_groupby[] = $groupby_clause;
	}

	function _buildQuerySelect($select = array())
	{
		$select = array_merge($select, $this->_select);
		$select 		= ( count( $this->_select ) ? ',' . implode( ',', $this->_select ) : '' );
		return $select;
	}

	function _buildQueryJoin($join = array())
	{
		$join = array_merge($join, $this->_join);
		$join 		= ( count( $this->_join ) ? ' ' . implode( ' ', $this->_join ) : '' );
		return $join;
	}


	function _buildQueryWhere($where = array())
	{
		$where = array_merge($where, $this->_where);
		$where = ( count($where) ? ' WHERE '. implode(' AND ', $where ) : '' );

		return $where;
	}

	function _buildQueryOrderBy($order = array(), $pre_order = '')
	{
		$order = array();
		$filter_order		= $this->getState( 'list.ordering');
		$filter_order_Dir	= $this->getState( 'list.direction');

		if (count($this->_groupby))
		{
			$groupby = array();
			foreach($this->_groupby as $group)
			{
				if ($filter_order == $group)
					$group .= ' ' . $filter_order_Dir;

				$groupby[] = $group;
			}
			$order = array_merge($order, $groupby);
		}

		if ($this->_active['order'])
		{
			if ($filter_order != '')
				$order[] = $filter_order . ' ' . $filter_order_Dir;
		}

		if ($pre_order)
			$order[] = $pre_order;

		$order = array_merge($this->_order, $order);
		$order = (count($order) ? ' ORDER BY ' . implode(',', $order) : '');

		return $order;
	}

	function _buildQueryExtra($extra = array())
	{
		$extra = array_merge($extra, $this->_extra);
		$extra 		= ( count( $this->_extra ) ? ' ' . implode( ' ', $this->_extra ) : '' );
		return $extra;
	}

	function _addSearch($instance, $namespace, $method)
	{
		$search = new stdClass();
		$search->method = $method;


		if (!isset($this->_searches[$instance]))
			$this->_searches[$instance] = array();

		$this->_searches[$instance][$namespace] = $search;
	}

	function _buildSearch($instance, $searchText, $options = array('join' => 'AND', 'ignoredLength' => 0))
	{
		if (!isset($this->_searches[$instance]))
			return;

		$db= JFactory::getDBO();
		$tests = array();
		foreach($this->_searches[$instance] as $namespace => $search)
		{
			$test = "";
			switch($search->method)
			{
				case 'like':
					$test = $namespace . " LIKE " . $db->Quote("%%s%");
					break;

				case 'exact':
					$test = $namespace . " = " . $db->Quote("%s");
					break;

				case '':
					break;
			}

			if ($test)
				$tests[] = $test;
		}

		if (!count($tests))
			return "";


		$whereSearch = implode(" OR ", $tests);


		//SPLIT SEARCHED TEXT
		$searchesParts = array();

		foreach(explode(" ", $searchText) as $searchStr)
		{
			$searchStr = trim($searchStr);
			if ($searchStr == '')
				continue;

			if ((isset($options['ignoredLength'])) && (strlen($searchStr) <= $options['ignoredLength']))
				continue;

			if ($search->method == 'like')
			{
				$searchStr = $db->escape($searchStr);
			}


			$searchesParts[] = "(" . str_replace("%s", $searchStr, $whereSearch) . ")";

		}

		if (!count($searchesParts))
			return;

		if (isset($options['join']))
			$join = strtoupper($options['join']);
		else
			$join = "AND";

		$where = implode(" " . $join . " ", $searchesParts);


		return $where;

	}
	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * @return  void
	 *
	 * @note    Calling getState in this method will result in recursion.
	 * @since   11.1
	 */
	protected function populateState($ordering = null, $direction = null)
	{


		$globalParams = JComponentHelper::getParams('com_jhacks', true);
		$this->setState('params', $globalParams);

		// If the context is set, assume that stateful lists are used.
		if ($this->context)
		{
			$app = JFactory::getApplication();

			//Determines if the request vars should be stored in session (user states vars)
			$persistent = !(JRequest::getVar('layout') == 'ajax');


		// List limit
			$value = $this->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
			$limit = $value;
			$this->setState('list.limit', $limit);

		// List limit start
			$value = $this->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0, 'int', false);
			$limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
			$this->setState('list.start', $limitstart);

		// Check if the ordering field is in the white list, otherwise use the incoming value.
			$value = $this->getUserStateFromRequest($this->context . '.ordercol', 'filter_order', $ordering);
			if (!in_array($value, $this->filter_fields))
			{
				$value = $ordering;
				if ($persistent)
					$app->setUserState($this->context . '.ordercol', $value);
			}
			$this->setState('list.ordering', $value);


		// Check if the ordering direction is valid, otherwise use the incoming value.
			$value = $this->getUserStateFromRequest($this->context . '.orderdirn', 'filter_order_Dir', $direction);
			if (!in_array(strtoupper($value), array('ASC', 'DESC', '')))
			{
				$value = $direction;
				if ($persistent)
					$app->setUserState($this->context . '.orderdirn', $value);
			}
			$this->setState('list.direction', $value);


		//Filters
			foreach($this->filter_vars as $var => $varType)
			{
				$value = $this->getUserStateFromRequest($this->context . '.filter.' . $var, 'filter_' . $var, null, $varType);

				//Convert datetime entries back from a custom format
				if ($value && (preg_match("/^date:(.+)/", $varType, $matches)))
				{
					$date = JhacksHelper::dateFromFormat($value, $matches[1]);
					if ($date)
						$value = $date->toMySQL();
					else
						continue;
				}


				$this->setState('filter.' . $var, $value);
			}

		//Searches
			foreach($this->search_vars as $var => $varType)
			{
				$value = $this->getUserStateFromRequest($this->context . '.search.' . $var, 'filter_' . $var, null, $varType);
				$this->setState('search.' . $var, $value);
			}

		}
		else
		{
			$this->setState('list.start', 0);
			$this->state->set('list.limit', 0);
		}




		if (defined('JDEBUG'))
			$_SESSION["Jhacks"]["Model"][$this->getName()]["State"] = $this->state;

	}


	/**
	 * Method to set model state variables
	 *
	 * @param   string  $property  The name of the property.
	 * @param   mixed   $value     The value of the property to set or null.
	 *
	 * @return  mixed  The previous value of the property or null if not set.
	 *
	 * @since   11.1
	 */
	public function setState($property, $value = null)
	{
		return $this->state->set($property, $value);
	}



	/**
	 * Method to get model state variables
	 * Override for compatibility since 1.6 support
	 *
	 * @param   string  $property  Optional parameter name
	 * @param   mixed   $default   Optional default value
	 *
	 * @return  object  The property where specified, the state object where omitted
	 *
	 * @since   11.1
	 */
	public function getState($property = null, $default = null)
	{
		if (!$this->stateSet)
		{
			// Protected method to auto-populate the model state.
			$this->populateState();

			// Set the model state set flag to true.
			$this->stateSet = true;
		}

		return $property === null ? $this->state : $this->state->get($property, $default);
	}


	/**
	 * Gets the value of a user state variable and sets it in the session
	 *
	 * This is the same as the method in JApplication except that this also can optionally
	 * force you back to the first page when a filter has changed
	 *
	 * @param   string   $key        The key of the user state variable.
	 * @param   string   $request    The name of the variable passed in a request.
	 * @param   string   $default    The default value for the variable if not found. Optional.
	 * @param   string   $type       Filter for the variable, for valid values see {@link JFilterInput::clean()}. Optional.
	 * @param   boolean  $resetPage  If true, the limitstart in request is set to zero
	 *
	 * @return  The request user state.
	 *
	 * @since   11.1
	 */
	public function getUserStateFromRequest($key, $request, $default = null, $type = 'none', $resetPage = true)
	{

		$persistent = !(JRequest::getVar('layout') == 'ajax');

		if (!$persistent)
			return JRequest::getVar($request, null, $type);




		$app = JFactory::getApplication();
		$old_state = $app->getUserState($key);
		$cur_state = (!is_null($old_state)) ? $old_state : $default;
		$new_state = JRequest::getVar($request, null, 'default', $type);

		//Return to the first pagination page if var state changed
		if ((trim($new_state) != "") && ($cur_state != $new_state) && ($resetPage))
		{
			$this->setState('limitstart', 0);
			$app->setUserState($this->context . '.limitstart', 0);
		}

		// Save the new value only if it is set in this request.
		if ($new_state !== null)
			$app->setUserState($key, $new_state);
		else
			$new_state = $cur_state;

		return $new_state;
	}

}
