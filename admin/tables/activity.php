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



// no direct access
defined('_JEXEC') or die('Restricted access');


/**
* Jhacks Table class
*
* @package		Joomla
* @subpackage	Jhacks
*
*/
class TableActivity extends JTable
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
	 * @var string
	 */
	var $type_item = null;
	/**
	 * @var string
	 */
	var $type_task = null;
	/**
	 * @var string
	 */
	var $result = null;
	/**
	 * @var string
	 */
	var $lg_creation_date = null;
	/**
	 * @var string
	 */
	var $details = null;






	/**
	* Constructor
	*
	* @param object Database connector object
	*
	*/
	function __construct(& $db)
	{
		parent::__construct('#__jhacks_logs', 'id', $db);
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
		$this->type_item = $filter->clean($this->type_item, 'STRING');
		$this->type_task = $filter->clean($this->type_task, 'STRING');
		$this->result = $filter->clean($this->result, 'STRING');
		$this->lg_creation_date = $filter->clean($this->lg_creation_date, 'STRING');
		$this->details = base64_encode($this->details); 

		
		$this->lg_creation_date = time();
		


		if ($this->result == null)
			$this->result = "undefined";



		if (($this->type_task === null) || ($this->type_task === '')){
			JError::raiseWarning(2001, JText::sprintf("JHACKS_VALIDATOR_IS_REQUESTED_PLEASE_RETRY", JText::_("JHACKS_FIELD_TYPE_TASK")));
			$valid = false;
		}




		return $valid;
	}
}
