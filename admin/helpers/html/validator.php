<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		The Garden V2
* @subpackage
* @copyright	Copyright 2012, All rights reserved
* @author		 -  -
* @license		GNU/GPL
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

@define("LI_PREFIX", '<span class="msg-prefix">• </span>');


/**
* Helper HTML
*
* @package	Demo240
* @subpackage	Validator
*/
class JHtmlValidator
{

	/**
	* Transform a recursive associative array in JSON string.
	*
	* @access	public static
	* @param	array	$values	Associative array only (can be recursive).
	*
	* @return	string	JSON string.
	*/
	public static function jsonFromArray($values)
	{
		$entries = array();
		foreach($values as $key => $value)
		{
			$q = "'";

			if (is_array($value))
			{
				// ** Recursivity **
				$value = "{" . LN . self::jsonFromArray($value) . LN . "}";
				$q = "";
			}
			else if (substr($key, 0, 1) == '#')
			{
				//Do not require quotes
				$key = substr($key, 1);
				$q = "";
			}

			$entries[] = '"'. $key. '" : '. $q. $value. $q;
		}

		return implode(',' .LN, $entries);
	}

	/**
	* Instance the language script for the validator, and the default validation
	* rules.
	*
	* @access	public static
	*
	* @return	void
	* @return	void
	*/
	public static function loadLanguageScript()
	{
		$script = '(function($){' .
				'$.fn.validationEngineLanguage = function(){' .
				'};' .
				'$.validationEngineLanguage = {' .
				'newLang: function(){' .
				'$.validationEngineLanguage.allRules = {' .LN;

		$baseRules = array();

		$baseRules["required"] = array(
			"regex"	=> "none",
			"alertText" => LI_PREFIX . addslashes(JText::_("JHACKS_FORMVALIDATOR_THIS_FIELD_IS_REQUIRED")),
			"alertTextCheckboxMultiple" => LI_PREFIX . addslashes(JText::_("JHACKS_FORMVALIDATOR_PLEASE_SELECT_AN_OPTION")),
			"alertTextCheckboxe" => LI_PREFIX . addslashes(JText::_("JHACKS_FORMVALIDATOR_THIS_CHECKBOX_IS_REQUIRED")),
			"alertTextDateRange" => LI_PREFIX . addslashes(JText::_("JHACKS_FORMVALIDATOR_BOTH_DATE_RANGE_FIELDS_ARE_REQUIRED"))

		);



// Default handlers


		$baseRules["numeric"] = array(
			"#regex"	=> '/^[\-\+]?\d+$/',
			"alertText" => LI_PREFIX . addslashes(JText::_("JHACKS_FORMVALIDATOR_THIS_IS_NOT_A_VALID_INTEGER")),
		);

		$baseRules["integer"] = array(
			"#regex"	=> '/^[\-\+]?\d+$/',
			"alertText" => LI_PREFIX . addslashes(JText::_("JHACKS_FORMVALIDATOR_THIS_IS_NOT_A_VALID_INTEGER")),
		);


		$baseRules["username"] = array(
			"#regex"	=> '/![\<|\>|\"|\'|\%|\;|\(|\)|\&]/i',
			"alertText" => LI_PREFIX . addslashes(JText::_("JHACKS_FORMVALIDATOR_THIS_IS_NOT_A_VALID_USERNAME")),
		);


		$baseRules["password"] = array(
			"#regex"	=> '/^\S[\S ]{2,98}\S$/',
			"alertText" => LI_PREFIX . addslashes(JText::_("JHACKS_FORMVALIDATOR_THIS_IS_NOT_A_VALID_PASSWORD")),
		);

		$baseRules["email"] = array(
			"#regex"	=> '/^[a-zA-Z0-9._-]+(\+[a-zA-Z0-9._-]+)*@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/',
			"alertText" => LI_PREFIX . addslashes(JText::_("JHACKS_FORMVALIDATOR_THIS_IS_NOT_A_VALID_EMAIL")),
		);






		/* TODO : You can add some rules here
		 * These rules are executed ONLY in client side (javascript)
		 * If you want both JS and PHP validation, create a rule file
		 */

		$script .= self::jsonFromArray($baseRules);

		$script .= LN. '};}};' .
				'$.validationEngineLanguage.newLang();' .
				'})(jQuery);';


		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($script);
	}


}



