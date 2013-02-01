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



// no direct access
defined('_JEXEC') or die('Restricted access');


/**
* Jhacks Table class
*
* @package		Joomla
* @subpackage	Jhacks
*
*/
class TableProcess extends JTable
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
	var $pro_operation_id = null;
	/**
	 * @var string
	 */
	var $target_folder = null;
	/**
	 * @var string
	 */
	var $target = null;
	/**
	 * @var int
	 */
	var $occurrences = null;
	/**
	 * @var string
	 */
	var $conflicts = null;
	/**
	 * @var int
	 */
	var $ordering = null;
	/**
	 * @var int
	 */
	var $pro_publish = null;
	/**
	 * @var string
	 */
	var $original_md = null;
	/**
	 * @var string
	 */
	var $modified_md = null;






	/**
	* Constructor
	*
	* @param object Database connector object
	*
	*/
	function __construct(& $db)
	{
		parent::__construct('#__jhacks_processes', 'id', $db);
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
		$this->pro_operation_id = $filter->clean($this->pro_operation_id, 'INT');
		$this->target_folder = $filter->clean($this->target_folder, 'STRING');
		$this->target = $filter->clean($this->target, 'STRING');
		$this->occurrences = $filter->clean($this->occurrences, 'INT');
		$this->ordering = $filter->clean($this->ordering, 'INT');
		$this->pro_publish = $filter->clean($this->pro_publish, 'INT');
		$this->original_md = $filter->clean($this->original_md, 'STRING');
		$this->modified_md = $filter->clean($this->modified_md, 'STRING');


		if (!empty($this->occurrences) && !preg_match("/^(\d|-)?(\d|,)*\.?\d*$/", $this->occurrences)){
			JError::raiseWarning( 1000, JText::sprintf("JHACKS_VALIDATOR_WRONG_VALUE_FOR_PLEASE_RETRY", JText::_("JHACKS_FIELD_OCCURRENCES")) );
			$valid = false;
		}

		if (!empty($this->ordering) && !preg_match("/^(\d|-)?(\d|,)*\.?\d*$/", $this->ordering)){
			JError::raiseWarning( 1000, JText::sprintf("JHACKS_VALIDATOR_WRONG_VALUE_FOR_PLEASE_RETRY", JText::_("JHACKS_FIELD_ORDERING")) );
			$valid = false;
		}



		if ($this->occurrences == null)
			$this->occurrences = 0;
		if ($this->ordering == null)
			$this->ordering = 0;
		if ($this->pro_publish == null)
			$this->pro_publish = 0;


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


		if (($this->pro_operation_id === null) || ($this->pro_operation_id === '')){
			JError::raiseWarning(2001, JText::sprintf("JHACKS_VALIDATOR_REQUIRED", JText::_("JHACKS_FIELD_OPERATION_ID")));
			$valid = false;
		}

		if (($this->target_folder === null) || ($this->target_folder === '')){
			JError::raiseWarning(2001, JText::sprintf("JHACKS_VALIDATOR_IS_REQUESTED_PLEASE_RETRY", JText::_("JHACKS_FIELD_TARGET_FOLDER")));
			$valid = false;
		}




		return $valid;
	}
}
