<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V1.5.2   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		0.4.0
* @package		Jhacks
* @subpackage	Languageoverrides
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
defined( '_JEXEC' ) or die( 'Restricted access' );
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);

@define('JPATH_ADMIN_JHACKS', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jhacks');
@define('JPATH_SITE_JHACKS', JPATH_SITE . DS . 'components' . DS . 'com_jhacks');
@define('JQUERY', 'BETA');

//Shortcut to include component libraries
if (!function_exists('cimport')){
	function cimport($namespace, $option = 'com_jhacks', $className = null){
		//Check if class already exists
		if (($className) && class_exists($className))
			return;
		@require_once JPATH_ADMINISTRATOR .DS. 'components' .DS. $option . DS . str_replace(".", DS, $namespace) . '.php';
	}
}

require_once(JPATH_ADMIN_JHACKS .DS.'helpers'.DS.'helper.php');
JHTML::_("behavior.framework");

// Set the table directory
JTable::addIncludePath(JPATH_ADMIN_JHACKS . DS . 'tables');

//Document title
$document	= &JFactory::getDocument();
$document->titlePrefix = "Jhacks - ";
$document->titleSuffix = "";

if (defined('JDEBUG') && count($_POST))
	$_SESSION['Jhacks']['$_POST'] = $_POST;

//FILE INDIRECT ACCESS
$task	= JRequest::getVar('task');
if ($task == 'file')
{
	require_once(JPATH_ADMIN_JHACKS .DS. "classes" .DS. "file.php");
	JhacksFile::returnFile();
}


if ($task == 'browse')
{
	JhacksHelper::browserFiles();
}

if ($task == 'loadfile')
{
	JhacksHelper::loadfilecontent();
}


$view = JRequest::getCmd( 'view');
$layout = JRequest::getCmd( 'layout');

$mainMenu = true;

switch ($view)
{

		case 'hacks' :
		case 'hack' :



        	$controllerName = "hacks";

		break;
		case 'operations' :
		case 'operation' :



        	$controllerName = "operations";

		break;
		case 'logs' :
		case 'activity' :



        	$controllerName = "logs";

		break;
		case 'processes' :
		case 'process' :



        	$controllerName = "processes";

		break;
		default:
			$view = 'hacks';
			$layout = 'default';

			JRequest::setVar( 'view', $view);
			JRequest::setVar( 'layout', $layout);
			$controllerName = "hacks";
			break;
}


if ($mainMenu)
{
		JSubMenuHelper::addEntry(JText::_("JHACKS_VIEW_CONTROL_PANEL"), 'index.php?option=com_jhacks&view=hacks&layout=default', ($view == 'hacks' && $layout == 'default'));
		JSubMenuHelper::addEntry(JText::_("JHACKS_VIEW_HACKS"), 'index.php?option=com_jhacks&view=hacks&layout=hacks', ($view == 'hacks' && $layout == 'hacks'));
		JSubMenuHelper::addEntry(JText::_("JHACKS_VIEW_OPERATIONS"), 'index.php?option=com_jhacks&view=operations', ($view == 'operations'));
		JSubMenuHelper::addEntry(JText::_("JHACKS_VIEW_PROCESSES"), 'index.php?option=com_jhacks&view=processes', ($view == 'processes'));
		JSubMenuHelper::addEntry(JText::_("JHACKS_VIEW_LOGS"), 'index.php?option=com_jhacks&view=logs', ($view == 'logs'));

}

require_once(JPATH_ADMIN_JHACKS .DS.'classes'.DS.'jcontroller.php');
if ($controllerName)
	require_once( JPATH_ADMIN_JHACKS .DS.'controllers'.DS.$controllerName.'.php' );

$controllerName = 'JhacksController'.$controllerName;




// Create the controller
$controller = new $controllerName();

// Perform the Request task
$controller->execute( JRequest::getCmd('task') );

// Redirect if set by the controller
$controller->redirect();

