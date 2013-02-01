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

jimport('joomla.application.component.modelitem');

/**
 * Jhacks Component JhacksModelItem Model
 *
 * @package		Joomla
 * @subpackage	Jhacks
 *
 */
class JhacksModelItem extends JModelItem
{
	/**
	 * id
	 *
	 * @var int
	 */
	var $_id = null;

	/**
	 * data
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * params
	 *
	 * @var array
	 */
	var $_params = null;





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
	 * Where object
	 *
	 * @var array
	 */
	var $_where = array();

	/**
	 * Indicates if the internal state has been set
	 *
	 * @var    boolean
	 * @since  11.1
	 */
	protected $stateSet = null;

	/**
	 * POST object clone
	 * Very useful to refill the object automatically
	 * This copy is cleared on call of setId();
	 *
	 * @var array
	 */
	var $_post = array();



	/**
	 * Constructor
	 *
	 */
	function __construct($config = array())
	{
		parent::__construct($config);


		$layout = JRequest::getCmd('layout');
		$render	= JRequest::getCmd('render');

		$this->context = strtolower($this->option . '.' . $this->getNameList()
					. ($layout?'.' . $layout:'')
					. ($render?'.' . $render:'')
					);


		$this->_active = array();
		$this->_modes = array('predefined');
		$this->activeAll(null);
		if ($_POST)	//Clone the post
			$this->_post = array_merge($_POST, array());
	}

	/**
	 * Method to set the identifier
	 *
	 * @access	public
	 * @param	int
	 */
	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;

		if ($id != 0)
			$this->_post	= null;
	}

	/**
	 * Method to get the identifier
	 *
	 * @access	public
	 * @param	int
	 */
	function getId()
	{
		return $this->_id;
	}

	/**
	 * Deprecated
	 * Method to get the data object
	 * @return object
	 */
	function getData()
	{
		return $this->getItem();
	}

	/**
	 * Method to get the data object
	 * @return object
	 */
	function getItem($id = null)
	{
		if ($id)
			$this->setId($id);
		else if (!isset($this->_id))
		{
			$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
	        if (empty($cid))
				$cid = JRequest::getVar( 'cid', array(), 'get', 'array' );

			if (count($cid))
				$this->setId((int)$cid[0]);
		}

		if ($this->_loadData()){}
		else  $this->_initData();

		$this->populateObjects();

		return $this->_data;
	}

	/**
	 * Method to load data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 *
	 */
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{

			$query = $this->_buildQuery();

			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();




			// Overload data with post - Can be dangerous :
			//		Always do a setId() to flush post when unsure
			// Post is sent when an error occurs on save or apply task.
			if ($this->_post)
				foreach(array_keys($this->_post) as $key)
					$this->_data->$key = $this->_post[$key];


			return (boolean) $this->_data;
		}
		return true;
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

//TODO : ATTRIBS
//		$item = $this->_data;
//		$itemParams = new JRegistry;
//		$itemParams->loadString((isset($item->attribs)?$item->attribs:$item->params));

		$this->_data->params = clone $this->getState('params');
	}


	/**
	 * Method to initialise the data
	 *
	 * @access	private
	 * @type	abstract
	 * @return	boolean	True on success
	 */
	function _initData()
	{
		if (empty($this->_data))
		{
			$data = new stdClass();

			$data->id					= 0;
			$this->_data				= $data;

			return (boolean) $this->_data;
		}
		return true;
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

	protected function getNameList()
	{
		return $this->_name_plur;
	}

	function _deleteFiles($cids, $fileFields)
	{
		if (!count($fileFields))
			return;

		$errors = false;
		$table = $this->getTable();

		//Delete images
		$query = "SELECT `" . implode("`, `", array_keys($fileFields))
			. "` FROM `" . $table->getTableName() . "`"
			. ' WHERE id IN ( '.$cids.' )';


		$this->_db->setQuery($query);
		$files = $this->_db->loadObjectList();


		require_once(JPATH_ADMIN_JHACKS .DS. "classes" .DS. "file.php");
		$config	= JComponentHelper::getParams( 'com_jhacks' );


		foreach($fileFields as $fieldName => $op)
		{
			$dir = JPath::clean(JPATH_SITE . DS . $config->get('upload_dir_' . $this->_name_plur . '_' . $fieldName, '[COM_SITE]' .DS. 'files' .DS. $this->_name_plur . '_' . $fieldName));


			foreach($files as $fileObj)
			{
				$imagePath = $fileObj->$fieldName;
				if (!preg_match("/\[.+\]/", $imagePath))
					$imagePath = $dir .DS. $imagePath;
				if (!JhacksFile::deleteFile($imagePath, $op))
					$errors = true;

			}
		}

		return !$errors;
	}

	/**
	 * Method to get model state variables
	 * Override because native still use $__state_set deprecated var
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
	protected function populateState()
	{
		$globalParams = JComponentHelper::getParams('com_jhacks', true);
		$this->setState('params', $globalParams);



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

}