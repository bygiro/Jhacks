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



// no direct access
defined('_JEXEC') or die('Restricted access');


/**
* Jhacks Table class
*
* @package		Joomla
* @subpackage	Jhacks
*
*/
class TableHack extends JTable
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
	var $name = null;
	/**
	 * @var string
	 */
	var $description = null;
	/**
	 * @var string
	 */
	var $warnings = null;
	/**
	 * @var string
	 */
	var $joomla_version = null;
	/**
	 * @var string
	 */
	var $jks_version = null;
	/**
	 * @var string
	 */
	var $hack_version = null;
	/**
	 * @var string
	 */
	var $element = null;
	/**
	 * @var string
	 */
	var $type = null;
	/**
	 * @var string
	 */
	var $client = null;
	/**
	 * @var string
	 */
	var $element_version = null;
	/**
	 * @var string
	 */
	var $author = null;
	/**
	 * @var string
	 */
	var $author_website = null;
	/**
	 * @var string
	 */
	var $author_email = null;
	/**
	 * @var string
	 */
	var $copyright = null;
	/**
	 * @var string
	 */
	var $license = null;
	/**
	 * @var string
	 */
	var $updates = null;
	/**
	 * @var string
	 */
	var $date_added = null;
	/**
	 * @var string
	 */
	var $last_update = null;
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
	 * @var bool
	 */
	var $locked = null;






	/**
	* Constructor
	*
	* @param object Database connector object
	*
	*/
	function __construct(& $db)
	{
		parent::__construct('#__jhacks_hacks', 'id', $db);
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
		$this->name = $filter->clean($this->name, 'STRING');
		$this->warnings = $filter->clean($this->warnings, 'STRING');
		$this->joomla_version = $filter->clean($this->joomla_version, 'STRING');
		$this->jks_version = $filter->clean($this->jks_version, 'STRING');
		$this->hack_version = $filter->clean($this->hack_version, 'STRING');
		$this->element = $filter->clean($this->element, 'STRING');
		$this->type = $filter->clean($this->type, 'STRING');
		$this->client = $filter->clean($this->client, 'STRING');
		$this->element_version = $filter->clean($this->element_version, 'STRING');
		$this->author = $filter->clean($this->author, 'STRING');
		$this->author_website = $filter->clean($this->author_website, 'STRING');
		$this->author_email = $filter->clean($this->author_email, 'STRING');
		$this->copyright = $filter->clean($this->copyright, 'STRING');
		$this->license = $filter->clean($this->license, 'STRING');
		$this->updates = $filter->clean($this->updates, 'STRING');
		$this->date_added = $filter->clean($this->date_added, 'STRING');
		$this->last_update = $filter->clean($this->last_update, 'STRING');
		$this->access = $filter->clean($this->access, 'INT');
		$this->ordering = $filter->clean($this->ordering, 'INT');
		$this->publish = $filter->clean($this->publish, 'INT');
		$this->locked = $filter->clean($this->locked, 'BOOL');

		
		unset($this->total_operations);
		unset($this->total_tmpl);
		unset($this->total_lang);
		
		
		if (!empty($this->author_email) && !preg_match("/^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/", $this->author_email)){
			JError::raiseWarning( 1000, JText::sprintf("JHACKS_VALIDATOR_WRONG_VALUE_FOR_PLEASE_RETRY", JText::_("JHACKS_FIELD_EMAIL")) );
			$valid = false;
		}

		if (!empty($this->date_added))
		{
			$date_added = JhacksHelper::getUnixTimestamp($this->date_added, array('%d-%m-%Y'));

			if ($date_added === null)
			{
				JError::raiseWarning(2001, JText::sprintf("JHACKS_VALIDATOR_WRONG_DATETIME_FORMAT_FOR_PLEASE_RETRY", JText::_("JHACKS_FIELD_DATE_ADDED")));
				$valid = false;
			}
			else
				$this->date_added = $date_added;
		}

		if (!empty($this->last_update))
		{
			$last_update = JhacksHelper::getUnixTimestamp($this->last_update, array('%d-%m-%Y'));

			if ($last_update === null)
			{
				JError::raiseWarning(2001, JText::sprintf("JHACKS_VALIDATOR_WRONG_DATETIME_FORMAT_FOR_PLEASE_RETRY", JText::_("JHACKS_FIELD_LAST_UPDATE")));
				$valid = false;
			}
			else
				$this->last_update = $last_update;
		}

		if (!empty($this->access) && !preg_match("/^(\d|-)?(\d|,)*\.?\d*$/", $this->access)){
			JError::raiseWarning( 1000, JText::sprintf("JHACKS_VALIDATOR_WRONG_VALUE_FOR_PLEASE_RETRY", JText::_("JHACKS_FIELD_ACCESS")) );
			$valid = false;
		}

		if (!empty($this->ordering) && !preg_match("/^(\d|-)?(\d|,)*\.?\d*$/", $this->ordering)){
			JError::raiseWarning( 1000, JText::sprintf("JHACKS_VALIDATOR_WRONG_VALUE_FOR_PLEASE_RETRY", JText::_("JHACKS_FIELD_ORDERING")) );
			$valid = false;
		}



		if ($this->joomla_version == null)
			$this->joomla_version = "j25";
		if ($this->jks_version == null)
			$this->jks_version = "jhacks02";
		if ($this->type == null)
			$this->type = "undefined";
		if ($this->client == null)
			$this->client = "site";
		if ($this->access == null)
			$this->access = 3;
		if ($this->ordering == null)
			$this->ordering = 0;
		if ($this->publish == null)
			$this->publish = 0;
		if ($this->locked === null)
			$this->locked = 0;


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

		//Creation date
		if (!trim($this->date_added))
			$this->date_added = gmmktime();

		//Modification date
		$this->last_update = gmmktime();


		if (($this->name === null) || ($this->name === '')){
			JError::raiseWarning(2001, JText::sprintf("JHACKS_VALIDATOR_IS_REQUESTED_PLEASE_RETRY", JText::_("JHACKS_FIELD_NAME")));
			$valid = false;
		}

		if (($this->joomla_version === null) || ($this->joomla_version === '')){
			JError::raiseWarning(2001, JText::sprintf("JHACKS_VALIDATOR_REQUIRED", JText::_("JHACKS_FIELD_JOOMLA_VERSION")));
			$valid = false;
		}

		if (($this->jks_version === null) || ($this->jks_version === '')){
			JError::raiseWarning(2001, JText::sprintf("JHACKS_VALIDATOR_REQUIRED", JText::_("JHACKS_FIELD_JHACKS_VERSION")));
			$valid = false;
		}

		if (($this->client === null) || ($this->client === '')){
			JError::raiseWarning(2001, JText::sprintf("JHACKS_VALIDATOR_REQUIRED", JText::_("JHACKS_FIELD_CLIENT")));
			$valid = false;
		}




		return $valid;
	}
}
