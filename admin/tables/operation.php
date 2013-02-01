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
defined('_JEXEC') or die('Restricted access');


/**
* Jhacks Table class
*
* @package		Joomla
* @subpackage	Jhacks
*
*/
class TableOperation extends JTable
{

	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;

	/**
	 * @var string
	 */
	var $attribs = null;

	/**
	 * @var int
	 */
	var $hack_id = null;
	/**
	 * @var string
	 */
	var $op_name = null;
	/**
	 * @var string
	 */
	var $op_description = null;
	/**
	 * @var string
	 */
	var $type = null;
	/**
	 * @var string
	 */
	var $replace_type = null;
	/**
	 * @var bool
	 */
	var $regex = null;
	/**
	 * @var string
	 */
	var $search_key = null;
	/**
	 * @var string
	 */
	var $replacement = null;
	/**
	 * @var string
	 */
	var $data_file = null;
	/**
	 * @var int
	 */
	var $access = null;
	/**
	 * @var int
	 */
	var $ordering = null;
	/**
	 * @var int
	 */
	var $publish = null;






	/**
	* Constructor
	*
	* @param object Database connector object
	*
	*/
	function __construct(& $db)
	{
		parent::__construct('#__jhacks_operations', 'id', $db);
	}




	/**
	* Overloaded bind function
	*
	* @acces public
	* @param array $hash named array
	* @return null|string	null is operation was satisfactory, otherwise returns an error
	* @see JTable:bind
	*
	*/
	function bind($src, $ignore = array())
	{

		if (isset($src['attribs']) && is_array($src['attribs']))
		{
			$registry = new JRegistry;
			$registry->loadArray($src['attribs']);
			$src['attribs'] = (string) $registry;
		}

		return parent::bind($src, $ignore);
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 * @see JTable:check
	 */
	function check()
	{
		$valid = true;

		$filter = new JFilterInput(array(), array(), 0, 0);
		$this->hack_id = $filter->clean($this->hack_id, 'INT');
		$this->op_name = $filter->clean($this->op_name, 'STRING');
		$this->op_description = $filter->clean($this->op_description, 'STRING');
		$this->type = $filter->clean($this->type, 'STRING');
		$this->replace_type = $filter->clean($this->replace_type, 'STRING');
		$this->regex = $filter->clean($this->regex, 'BOOL');
		$this->search_key = base64_encode($this->search_key);	
		$this->replacement = base64_encode($this->replacement);	
		$this->data_file = $filter->clean($this->data_file, 'STRING');
		$this->access = $filter->clean($this->access, 'INT');
		$this->ordering = $filter->clean($this->ordering, 'INT');
		$this->publish = $filter->clean($this->publish, 'INT');

		
		unset($this->total_processes);
		


		if (!empty($this->access) && !preg_match("/^(\d|-)?(\d|,)*\.?\d*$/", $this->access)){
			JError::raiseWarning( 1000, JText::sprintf("JHACKS_VALIDATOR_WRONG_VALUE_FOR_PLEASE_RETRY", JText::_("JHACKS_FIELD_ACCESS")) );
			$valid = false;
		}

		if (!empty($this->ordering) && !preg_match("/^(\d|-)?(\d|,)*\.?\d*$/", $this->ordering)){
			JError::raiseWarning( 1000, JText::sprintf("JHACKS_VALIDATOR_WRONG_VALUE_FOR_PLEASE_RETRY", JText::_("JHACKS_FIELD_ORDERING")) );
			$valid = false;
		}



		if ($this->replace_type == null)
			$this->replace_type = "justreplace";
		if ($this->regex === null)
			$this->regex = 0;
		if ($this->access == null)
			$this->access = 3;
		if ($this->ordering == null)
			$this->ordering = 0;
		if ($this->publish == null)
			$this->publish = 0;


		//New row : Ordering : place to the end
		if ($this->id == 0)
		{
			$db= JFactory::getDBO();

			$query = 	'SELECT `ordering` FROM `' . $this->_tbl . '`'
					. 	' ORDER BY `ordering` DESC LIMIT 1';
			$db->setQuery($query);
			$lastOrderObj = $db->loadObject();
			$this->ordering = (int)$lastOrderObj->ordering + 1;
		}


		if (($this->op_name === null) || ($this->op_name === '')){
			JError::raiseWarning(2001, JText::sprintf("JHACKS_VALIDATOR_IS_REQUESTED_PLEASE_RETRY", JText::_("JHACKS_FIELD_NAME")));
			$valid = false;
		}

		if (($this->type === null) || ($this->type === '')){
			JError::raiseWarning(2001, JText::sprintf("JHACKS_VALIDATOR_REQUIRED", JText::_("JHACKS_FIELD_TYPE")));
			$valid = false;
		}

		if (($this->regex === null) || ($this->regex === '')){
			JError::raiseWarning(2001, JText::sprintf("JHACKS_VALIDATOR_REQUIRED", JText::_("JHACKS_FIELD_REGEX")));
			$valid = false;
		}

		if (($this->publish === null) || ($this->publish === '')){
			JError::raiseWarning(2001, JText::sprintf("JHACKS_VALIDATOR_REQUIRED", JText::_("JHACKS_FIELD_PUBLISH")));
			$valid = false;
		}




		return $valid;
	}
}
