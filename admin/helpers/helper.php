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


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!class_exists('JDom'))
	require_once(JPATH_ADMIN_JHACKS .DS.'dom'.DS.'dom.php');
	
if (!class_exists('JhacksFindReplace')){
	require_once('findreplace.php');
}	

if (!class_exists('ImportExport')){
	require_once('impexp.php');
}

if (!class_exists('JhacksProcessesCreator')){
	require_once('processescreator.php');
}

if (!class_exists('JhacksChecking')){
	require_once('checking.php');
}

class JhacksHelper
{
	var $config;
	var $backupFolder;
	
    function __construct() {
		$this->config 	= JComponentHelper::getParams('com_jhacks');
		$this->backupFolder = JPATH_SITE . DS . $config->get("backup_dir");
    }		
	
	/*
	 * Recreate the URL with a redirect in order to :
	 * 	-> keep an good SEF
	 *  -> always kill the post
	 *  -> precisely control the request
	 */
	function urlRequest($vars = array())
	{
		$parts = array();

		//Contains followers
		$authorizedInUrl = array('option', 'view', 'layout', 'Itemid', 'tmpl', 'lang');

		$request = JRequest::get();
		foreach($request as $key => $value)
		{
			if (in_array($key, $authorizedInUrl))
				$parts[] = $key . '=' . $value;
		}


		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
        if (empty($cid))
			$cid = JRequest::getVar( 'cid', array(), 'get', 'array' );
		if (!empty($cid))
		{
			$cidVals = implode(",", $cid);
			if ($cidVals != '0')
				$parts[] = 'cid[]=' . $cidVals;
		}


		if (count($vars))
		foreach($vars as $key => $value)
			$parts[] = $key . '=' . $value;

		return JRoute::_("index.php?" . implode("&", $parts), false);
	}

	/*
	 * Redirect Back
	 * Can be called in View if controller redirection has already been done
	 * Can be raised when the user ask a page with a direct url
	 * Handle some security to avoid recursivity
	 * TODO : Customize your own rules here
	 */
	function redirectBack($notAllowed = true)
	{
		if ($notAllowed && JFactory::getUser()->id == 0)
		{
			JRequest::setVar('option','com_users');
			JRequest::setVar('view','login');
			JRequest::setVar('layout','');

			$url = self::urlRequest();					//Login page
		}
		else
		{
			// TODO : Not finished : only redirect to Root
			JFactory::getApplication()->redirect(JURI::base(true));
			return;

			$current = JURI::current();

			//Get the previous page : TODO : finish this
			$url = (isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:null);

			if (!$url || ($url == $current))
			{
				JRequest::setVar('view','');
				JRequest::setVar('layout','');

				$url = self::urlRequest();				//Component Root
			}
			if ($url == $current)
			{
				$url = JURI::base(true);				// Site Root
			}
		}


		JFactory::getApplication()->redirect($url);

	}

	function headerDeclarations()
	{
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();

		$siteUrl = JURI::root(true);
		$siteUrl = str_replace("\\", "/", $siteUrl);   //Win servers

		$componentUrl = $siteUrl . '/components/com_jhacks';
		$componentUrlAdmin = $siteUrl .'/administrator/components/com_jhacks';

		$config = JComponentHelper::getParams('com_jhacks');  

		//Javascript
		$doc->addScript($siteUrl . '/media/system/js/core.js');
		// Mootools non conflict is handled here :
		$doc->addScriptDeclaration("var Moo = document.id;");

	//Load jQuery from the CDN
		
		if ($config->get("load_jquery") != 0 OR !$config->get("load_jquery") ) {
		//	$doc->addScript('http://code.jquery.com/jquery.min.js');
			$doc->addScript($componentUrlAdmin . '/js/jquery-1.7.2.min.js');  
		}
		
		
	//Load the jQuery-Validation-Engine (MIT License, Copyright(c) 2011 Cedric Dugas http://www.position-absolute.com)
		$doc->addScript($componentUrlAdmin . '/js/jquery.validationEngine.js');
		$doc->addStyleSheet($componentUrlAdmin . '/css/validationEngine.jquery.css');
		cimport('helpers.html.validator');
		JHtmlValidator::loadLanguageScript();

		
	//	$doc->addCustomTag('<script type = "text/javascript">var $j = jQuery.noConflict();</script>');
		$doc->addScript($componentUrlAdmin . '/js/jhacks.js');  
		$doc->addScript($componentUrlAdmin . '/js/jqueryFileTree.js');
		$doc->addScript($componentUrlAdmin . '/js/jquery.reveal.js');
		$doc->addScript($componentUrlAdmin . '/js/jquery.easing.js');
		$doc->addScript($componentUrlAdmin . '/js/jquery.autosize-min.js');
		$doc->addScript($componentUrlAdmin . '/js/indenthis.js');	
		

		//TODO : Define here the default framework to use with $
		// Mootools is still used in Joomla native javascript functionalities
		$doc->addScriptDeclaration("var $" . " = Moo;");  //Moo ; jQuery

		/*
		 * How to write a plugins in non-conflict modes :
		 *
		 (function($){
		// Write your code here, using $.
		// The local var $ represents jQuery framework
		})(jQuery);


		// exactly the same, with for MooTools plugins
		 (function($){
		...
		})(Moo);

		 */


		//CSS
		if ($app->isAdmin())
		{
			$doc->addStyleSheet($componentUrlAdmin . '/css/jhacks.css');
			$doc->addStyleSheet($componentUrlAdmin . '/css/toolbar.css');
			$doc->addStyleSheet($componentUrlAdmin . '/css/jqueryFileTree.css');  
			$doc->addStyleSheet($componentUrlAdmin . '/css/reveal.css');  
			// Blue stork override
			$styles = "fieldset td.key label{display: block;}fieldset input, fieldset textarea, fieldset select, fieldset img, fieldset button{float: none;}fieldset label, fieldset span.faux-label{float: none;display: inline;min-width: inherit;}";
			$doc->addStyleDeclaration($styles);

		}
		else if ($app->isSite())
		{
			$doc->addStyleSheet($componentUrl . '/css/jhacks.css');
			$doc->addStyleSheet($componentUrl . '/css/toolbar.css');
		}


	}


	function getACL()
	{
		jimport('joomla.access.access');
		$user	= JFactory::getUser();
		$result	= new JObject;


		$actions = JAccess::getActions('com_jhacks', 'component');

		foreach ($actions as $action) {
			$result->set($action->name,	$user->authorise($action->name, 'com_jhacks'));
		}

		return $result;

	}
	function getSqlDate($strtime, $formats)
	{
		//Push the default SQL date format
		if (!in_array("%Y-%m-%d", $formats))
			$formats[] = "%Y-%m-%d";

		//Push the default SQL datetime format
		if (!in_array("%Y-%m-%d %H:%M:%S", $formats))
			$formats[] = "%Y-%m-%d %H:%M:%S";

		//Push the default SQL time format
		if (!in_array("%H:%M:%S", $formats))
			$formats[] = "%H:%M:%S";


		foreach($formats as $format)
		{
			$regex = self::strftime2regex($format);
			if (preg_match($regex, $strtime))
			{

				return self::dateFromFormat($strtime, $format);
			}

		}
	}


	function getUnixTimestamp($strtime, $formats)
	{
		//Check if the string is already a timestamp
		if (preg_match("/^[0-9]{1,10}$/", $strtime))
			return $strtime;

		foreach($formats as $format)
		{
			$regex = self::strftime2regex($format);
			if (preg_match($regex, $strtime))
				return self::timeFromFormat($strtime, $format);
		}
	}

	 /**
	 * 	Convert format to REGEX language and escape the format
	 * @format	string : the strftime format used to decode
	 * 		/!\ Only supports : %Y, %y, %m, %d, %H, %M, %S
	 *	return standard regular expression
	 */
	function strftime2regex($format)
	{
		$d2 = "(\d{2})";
		$d4 = "([1-9]\d{3})";

		$patterns =
array(	"\\", 	"/", 	"#",	"!", 	"^", "$", "(", ")", "[", "]", "{", "}", "|", "?", "+", "*", ".",
		"%Y", 	"%y",	"%m",	"%d", 	"%H", 	"%M", 	"%S", 	" ");
		$replacements =
array(	"\\", "\/", 	"\#",	"\!", 	"\^", "$", "\(", "\)", "\[", "\]", "\{", "\}", "\|", "\?", "\+", "\*", "\.",
		$d4,	$d2,	$d2,	$d2,	$d2,	$d2,	$d2,	"\s");

		$regex = str_replace($patterns, $replacements, $format);

		return "/^" . $regex . "$/";
	}

	function explodeDate($time, $format)
	{
		$regex = self::strftime2regex($format);

	//Prepare the search depending on attempted format
		$pos = array();
		$pos['year4'] = strpos($format, "%Y");
		$pos['year2'] = strpos($format, "%y");
		$pos['month'] = strpos($format, "%m");
		$pos['day'] = strpos($format, "%d");

		$pos['hour'] = strpos($format, "%H");
		$pos['minute'] = strpos($format, "%M");
		$pos['second'] = strpos($format, "%S");

		asort($pos);

		$i = 1;
		foreach($pos as $key => $value)
		{
			if ($value === false)
			{
				unset($pos[$key]);
				continue;
			}
			$pos[$key] = $i;
			$i++;
		}

	//Split the values
		preg_match_all($regex, $time, $matches);

	//Choose year on 2 or 4 digits
		$pos['year'] = ((isset($pos['year4']) && (int)$pos['year4'] > 0)?$pos['year4']:(isset($pos['year2'])?$pos['year2']:null));

	//Retreive the independant values in the matches
		$v = array();
		$defaults = array('year' => '0000','month' => '00','day' => '00','hour' => '00','minute' => '00','second' => '00',);
		foreach($defaults as $key => $default)
		{
			if ((isset($pos[$key])) && ($p = (int)$pos[$key]) && ($p > 0) && (count($matches[$p])))
				$v[$key] = $matches[$p][0];
			else
				$v[$key] = $default;
		}

		return $v;
	}


	/*
	 * Reverse function of the JDate::toFormat()
	 *
	 * @datetime	string : the formated datetime to decode
	 * @format	 	string : the strftime format used to decode
	 * 		/!\ Only supports : %Y, %y, %m, %d, %H, %M, %S
	 *
	 *
	 */
	function dateFromFormat($datetime, $format)
	{
		$v = self::explodeDate($datetime, $format);

	// Check gregorian valid date
		if (trim($v['month'], '0') && trim($v['day'], '0') && trim($v['year'], '0'))
		if (!checkdate($v['month'], $v['day'], $v['year']))
			return null;

		return new JDate( $v['year'] .'-'. $v['month'] .'-'. $v['day'] .' '. $v['hour'] .':'. $v['minute'] .':'.$v['second']);
	}



	 /* Create a unix timestamp from a given format
	 *
	 * @datetime	string : the formated datetime to decode
	 * @format 		string : the strftime format used to decode
	 * 		/!\ Only supports : %Y, %y, %m, %d, %H, %M, %S
	 *
	 *
	 */
	function timeFromFormat($datetime, $format, $gmt = true)
	{
		$v = self::explodeDate($datetime, $format);

	// Check gregorian valid date
		if (trim($v['month'], '0') && trim($v['day'], '0') && trim($v['year'], '0'))
		if (!checkdate($v['month'], $v['day'], $v['year']))
			return null;

		if ($gmt)
			return gmmktime($v['hour'], $v['minute'], $v['second'], $v['month'], $v['day'], $v['year']);

		return mktime($v['hour'], $v['minute'], $v['second'], $v['month'], $v['day'], $v['year']);
	}

	function enumList($ctrl, $fieldName)
	{
		$lists = array();

		$lists["hacks"]["joomla_version"] = array();
		$lists["hacks"]["joomla_version"]["j25"] = array("value" => "j25", "text" => JText::_("JHACKS_ENUM_HACKS_JOOMLA_VERSION_JOOMLA_25"));


		$lists["hacks"]["jks_version"] = array();
		$lists["hacks"]["jks_version"]["jhacks02"] = array("value" => "jhacks02", "text" => JText::_("JHACKS_ENUM_HACKS_JKS_VERSION_JHACKS_02X"));


		$lists["hacks"]["type"] = array();
		$lists["hacks"]["type"]["undefined"] = array("value" => "undefined", "text" => JText::_("JHACKS_ENUM_HACKS_TYPE_UNDEFINED"));
		$lists["hacks"]["type"]["component"] = array("value" => "component", "text" => JText::_("JHACKS_ENUM_HACKS_TYPE_COMPONENT"));
		$lists["hacks"]["type"]["module"] = array("value" => "module", "text" => JText::_("JHACKS_ENUM_HACKS_TYPE_MODULE"));
		$lists["hacks"]["type"]["plugin"] = array("value" => "plugin", "text" => JText::_("JHACKS_ENUM_HACKS_TYPE_PLUGINS"));
		$lists["hacks"]["type"]["template"] = array("value" => "template", "text" => JText::_("JHACKS_ENUM_HACKS_TYPE_TEMPLATE"));
		$lists["hacks"]["type"]["language"] = array("value" => "language", "text" => JText::_("JHACKS_ENUM_HACKS_TYPE_LANGUAGE_FILE"));
		$lists["hacks"]["type"]["joomlacore"] = array("value" => "joomlacore", "text" => JText::_("JHACKS_ENUM_HACKS_TYPE_JOOMLA_CORE"));
		$lists["hacks"]["type"]["other"] = array("value" => "other", "text" => JText::_("JHACKS_ENUM_HACKS_TYPE_LIBRARY"));


		$lists["hacks"]["client"] = array();
		$lists["hacks"]["client"]["site"] = array("value" => "site", "text" => JText::_("JHACKS_ENUM_HACKS_CLIENT_SITE"));
		$lists["hacks"]["client"]["administrator"] = array("value" => "administrator", "text" => JText::_("JHACKS_ENUM_HACKS_CLIENT_ADMINISTRATOR"));


		$lists["operations"]["type"] = array();
		$lists["operations"]["type"]["replacepattern"] = array("value" => "replacepattern", "text" => JText::_("JHACKS_ENUM_OPERATIONS_TYPE_ADD_OR_REPLACE_STRING"));
		$lists["operations"]["type"]["replacefile"] = array("value" => "replacefile", "text" => JText::_("JHACKS_ENUM_OPERATIONS_TYPE_ADD_OR_REPLACE_FILE"));
		$lists["operations"]["type"]["createfolder"] = array("value" => "createfolder", "text" => JText::_("JHACKS_ENUM_OPERATIONS_TYPE_CREATE_FOLDER"));
		$lists["operations"]["type"]["sql"] = array("value" => "sql", "text" => JText::_("JHACKS_ENUM_OPERATIONS_TYPE_SQL_QUERY_COMING_SOON"));


		$lists["operations"]["replace_type"] = array();
		$lists["operations"]["replace_type"]["above"] = array("value" => "above", "text" => JText::_("JHACKS_ENUM_OPERATIONS_REPLACE_TYPE_ABOVE_THE_KEY"));
		$lists["operations"]["replace_type"]["justreplace"] = array("value" => "justreplace", "text" => JText::_("JHACKS_ENUM_OPERATIONS_REPLACE_TYPE_JUST_REPLACE"));
		$lists["operations"]["replace_type"]["below"] = array("value" => "below", "text" => JText::_("JHACKS_ENUM_OPERATIONS_REPLACE_TYPE_BELOW_THE_KEY"));


		$lists["logs"]["type_item"] = array();
		$lists["logs"]["type_item"]["hack"] = array("value" => "hack", "text" => JText::_("JHACKS_ENUM_LOGS_TYPE_ITEM_HACK"));
		$lists["logs"]["type_item"]["process"] = array("value" => "process", "text" => JText::_("JHACKS_ENUM_LOGS_TYPE_ITEM_OPERATION_PROCESS"));


		$lists["logs"]["type_task"] = array();
		$lists["logs"]["type_task"]["publish"] = array("value" => "publish", "text" => JText::_("JHACKS_ENUM_LOGS_TYPE_TASK_PUBLISH_UNPUBLISH"));
		$lists["logs"]["type_task"]["creation"] = array("value" => "creation", "text" => JText::_("JHACKS_ENUM_LOGS_TYPE_TASK_PROCESSES_CREATION"));
		$lists["logs"]["type_task"]["impexp"] = array("value" => "impexp", "text" => JText::_("JHACKS_ENUM_LOGS_TYPE_TASK_IMPORT_EXPORT"));


		$lists["logs"]["result"] = array();
		$lists["logs"]["result"]["undefined"] = array("value" => "undefined", "text" => JText::_("JHACKS_ENUM_LOGS_RESULT_UNDEFINED"));
		$lists["logs"]["result"]["failed"] = array("value" => "failed", "text" => JText::_("JHACKS_ENUM_LOGS_RESULT_FAILED"));
		$lists["logs"]["result"]["successful"] = array("value" => "successful", "text" => JText::_("JHACKS_ENUM_LOGS_RESULT_SUCCESSFUL"));

		return $lists[$ctrl][$fieldName];
	}
	
	function mapProcessesbyOperationId($inputArr){
		// $ouput array will be indexed by the 'pro_operation_id' value
		$output = array();

		// Iterate over the main array and create a new subarray if 
		// it doesn't already exist, or add to it if it does.
		foreach($inputArr as $subarr) {
		  if (!isset($output[$subarr->pro_operation_id])) {
			// New array indexed by pro_operation_id
			$output[$subarr->pro_operation_id] = array();
		  }
		  // Append the whole array
		  $output[$subarr->pro_operation_id][] = $subarr;
		}
		return $output;
	}
	
	function writeLog($item, $task, $result, $details) {
		$model = JModel::getInstance('activity', 'JhacksModel');

		$activity->type_item = $item;
		$activity->type_task = $task;
		$activity->result = $result;		
		$activity->details = $details;
		
		$model->save($activity);	
		
	}
	
	function getNextId($table_name, $keystring = null, $valuestring = null){
		$db = JFactory::getDBO();

		$db->setQuery("INSERT INTO ". $table_name ." (". $keystring .") VALUES (". $valuestring .") ");
		$db->query();
		/*
		if ($db->getErrorNum()) {
			$this->errorText .= $db->getErrorMsg();
		}
		*/
		return $db->insertid();
	}
	
	function getRelativePath($file, $client = 0) {
		/*** get the document root ***/
		$dr = JPATH_SITE . DS;
		if($client){
			$dr = JPATH_SITE . DS . basename(JPATH_ADMINISTRATOR) . DS;
		}
		/*** get the file working directory ***/
		$cwd = realpath($file);

		/*** return the path ***/
		return JPath::clean(str_replace($dr, '',  $cwd));
	} 	

	function getRealFilename($filename){
		$parts = explode('_-_', trim($filename), 2);
		
		$realname = '';
		if (count($parts) == 2) {
			list($realname, $opID) = $parts;
			
			$ext = self::findExtension($opID);
		}
		return $realname . '.' . $ext;
	}
	
	function makeOperationFilename($filename, $id){

		$ext = self::findExtension($filename);
		$basename = str_replace('.'. $ext, '', $filename);
		
		$newFilename = $basename . '_-_' . $id . '.' . $ext;
		
		return $newFilename;
	}

	function findExtension($file){
		return $ext = substr(strrchr($file, '.'), 1);
	}

	function operationStatus($items){
		
		$db = JFactory::getDBO();		
		if(!is_array($items)){
			$items = array($items);
		}
		
		foreach ($items as $item){
			$query = "SELECT count(*) "
			.		 " FROM #__jhacks_processes "
			.		 " WHERE pro_publish = 1 AND pro_operation_id = ". $item->id;
			
			$db->setQuery($query);
			$published = $db->loadResult();
			
			$query = "SELECT count(*) "
			.		 " FROM #__jhacks_processes "
			.		 " WHERE pro_operation_id = ". $item->id;
			
			$db->setQuery($query);
			$total = $db->loadResult();			
			
			$item->total_processes =  $published .' / '. $total;
			
			if ($total > 0){
				if ($total == $published){ // published
					$val = 1;
				} elseif($published == 0){ // unpublished
					$val = 0;
				} else {
					$val = 2;
				}
			}
			
			if($val != $item->publish){
				$db->setQuery("UPDATE #__jhacks_operations SET `publish` = ".(int) $val .' WHERE id = '. $item->id );
				if($db->query()){
					$item->publish = (int) $val;
				}
			}
		}
	}
	
	function hackStatus($items){
		
		$db = JFactory::getDBO();
		
		if(!is_array($items)){
			$items = array($items);
		}
		
		foreach ($items as $item){
			
			// operations
			$query = "SELECT id, publish "
			.		 " FROM #__jhacks_operations "
			.		 " WHERE hack_id = ". $item->id;
			
			$db->setQuery($query);
			$operations = $db->loadObjectList();
			
			JhacksHelper::operationStatus($operations);

			$op_total = count($operations);
			
			$query = "SELECT count(*) "
			.		 " FROM #__jhacks_operations "
			.		 " WHERE publish = 1 AND hack_id = ". $item->id;
			
			$db->setQuery($query);
			$op_published = $db->loadResult();
			
			$item->total_operations =  $op_published .' / '. $op_total;

			
			// report
			$hack_total = $op_total;
			$hack_published = $op_published;
			
			if ($hack_total > 0){
				if ($hack_total == $hack_published){ // published
					$val = 1;
				} elseif($hack_published == 0){ // unpublished
					$val = 0;
				} else {
					$val = 2;
				}
			}
			
			if($val != $item->publish){
				$db->setQuery("UPDATE #__jhacks_hacks SET `publish` = ".(int) $val .' WHERE id = '. $item->id );
				if($db->query()){
					$item->publish = (int) $val;
				}
			}			
		}
	}
	
	function stringfix($str){
		// Order of replacement
		$order   = array("\r\n", "\n", "\r");
		$replace = "\n";

		// Processes \r\n's first so they aren't converted twice.
		$newstr = str_replace($order, $replace, $str);
		
		return $newstr;
	}
	
	function cleanDirname($name) {
		$except = array('\\', '/', ':', '*', '?', '"', '<', '>', '|');
		return str_replace($except, '', $name);
	} 

	function removeEmptySubfolders($path){

		
	  if(is_dir($path)){
		$files = glob( $path . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
		
		foreach( $files as $file ) {
			JhacksHelper::removeEmptySubfolders( $file );
			if ($path != $this->backupFolder){
				rmdir( $path );
			}
		}
	  } elseif(basename($path) == 'index.html') {
		unlink( $path );
	  }
	  
	}	

    function recursive_check_index_exist($directory) {

		if(!is_file(JPath::clean($directory . '/index.html'))){
			file_put_contents(JPath::clean($directory . '/index.html'), 'What are you doing?!');
			
		}	
	
        $handle = opendir($directory);
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
				preg_match("/(^(([\.]){1,2})$|(\.(svn|git|md))|(Thumbs\.db|\.DS_STORE))$/iu", $file, $skip);

				$filepath = $directory . DS . $file;
						
				if (!$skip) {
					if (is_dir($filepath)) {
						JhacksHelper::recursive_check_index_exist($filepath);

						if(!is_file($filepath . DS .'index.html')){
							file_put_contents($filepath . DS .'index.html', 'What are you doing?!');
							
						}
					}
				}
			}
			
			closedir($handle);
        }
    }	
	
	function clonefile($old_id, $filename, $new_id)
	{
		$config	= JComponentHelper::getParams( 'com_jhacks' );
		$basePath = JPATH_SITE . DS . $config->get('upload_dir_operations_data_file', basename(JPATH_ADMINISTRATOR) .DS. 'components' .DS. 'com_jhacks' .DS. 'files' .DS. 'operations');
		$oldfile =  $basePath . DS . $filename;
		
		$realname = self::getRealFilename($filename);
		$newFilename = self::makeOperationFilename($realname, $new_id);
		
		$newfile =  $basePath . DS . $newFilename;

		$structure = dirname($newfile);
		// To create the nested structure, the $recursive parameter to mkdir() must be specified.
		if (!file_exists($structure)) {
			if (!mkdir($structure, 0644, true)){
				return false;
			} else {
				if (!file_exists($structure . DS . 'index.html')){
					file_put_contents($structure . DS . 'index.html', 'What are you doing?!');
				}
			}
		}

		if(!copy($oldfile, $newfile)) {
			return false;
		}
		chmod($newfile, 0744);
		
		return true;
	}

	function rrmdir($dir){
		foreach(glob($dir . '/*') as $file) {
			if(is_dir($file)){
				self::rrmdir($file);
			} else {
				unlink($file);
			}
		}
		rmdir($dir);
	}
	
	function deleteItem($ids, $tablename){
		$model = JModel::getInstance($tablename, 'JhacksModel');
		$model->delete($ids);
	}
	
	function verify($process) {

		$path = $process->target_folder . DS . $process->target;
		/* live modified checksum */
		$livePath = JPath::clean(JPATH_SITE . DS . $path);

		if (file_exists($livePath)){
			$liveCksum = md5_file($livePath);
		}		
		
		/* live original (backup) checksum */
		$config = JComponentHelper::getParams('com_jhacks');
		$backupPath = JPATH_SITE . DS . $config->get("backup_dir") . DS . $path;
		
		if (file_exists($backupPath)){
			$livebackupCksum = md5_file($backupPath);
		}


		/* stored original (backup) checksum */
		$storedbackupCksum = $process->original_md;
		
		/* stored modified (last time) checksum */
		$storedmodifiedCksum = $process->modified_md;
		
		$verify = new stdClass;
		$verify->livePath = $path;
		$verify->backupPath = $backupPath;
		
		if(!$liveCksum){
			$verify->live = 1;  // live file missing
		}elseif($liveCksum == $storedmodifiedCksum){
			$verify->live = 2; // file OK
		}elseif($liveCksum != $storedmodifiedCksum){
			if($liveCksum == $storedbackupCksum){
				$verify->live = 4; // file modified! but restorable 
			} else {
				$verify->live = 3; // file modified, not restorable.
			}
		} 
		
		if(!$livebackupCksum){
			$verify->backup = 1; // backup file missing
		} elseif($livebackupCksum != $storedbackupCksum){
			$verify->backup = 3; // file modified.
		} elseif($livebackupCksum == $storedbackupCksum){
			$verify->backup = 2; // file OK.
		} 

		return $verify;
	}

	function findRelatedProcess($process) {
		$db = JFactory::getDBO();
		
		$target_folder = $db->quote( $db->getEscaped($process->target_folder), false );
		$target = $db->quote( $db->getEscaped($process->target), false );
		
		$model = JModel::getInstance('processes', 'JhacksModel');
		$model->active('predefined', 'default');
		$model->addWhere(" a.target_folder = ". $target_folder);
		$model->addWhere(" a.target = ". $target);
		$model->addWhere(" a.pro_publish = 1 ");
		$items = $model->getItems();
			
		return $items;
	}

	
	// to do: merge readDir + findpath
	
	function findpaths($startingPath){
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($startingPath), RecursiveIteratorIterator::CHILD_FIRST);
				
		// Skip "dot" files
		$iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);		
		return $iterator;		
	}

	function browserFiles()
	{

		$path = urldecode($_POST['dir']);
		if (urldecode($_POST['dir']) == '') {
			$path = JPATH_SITE . DS;
		}	
		
		if( file_exists($path) ) {
			$foldersinpath = JFolder::folders($path, $filter = '.', false, false);
			$filesinpath = JFolder::files($path, $filter = '.', false, false);
		
			$files = array_merge($foldersinpath, $filesinpath);
			natcasesort($files);
			
			echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
			// All dirs
			foreach( $files as $file ) {
				if( file_exists($path . $file) && $file != '.' && $file != '..' && JFolder::exists($path . $file) ) {
					echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($path . $file) . "/\">" . htmlentities($file) . "</a></li>";
				}
			}
			
			// All files
			foreach( $files as $file ) {
				if( file_exists($path . $file) && $file != '.' && $file != '..' && !JFolder::exists($path . $file) ) {
					$ext = preg_replace('/^.*\./', '', $file);
					echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlentities($path . $file) . "\">" . htmlentities($file) . "</a></li>";
				}
			}
			echo "</ul>";		
		}	
		jexit();

	}	

	function loadfilecontent(){
		$file	= JRequest::getVar('file');
		$filepath = JPath::clean(JPATH_SITE . DS . $file);
		if (is_file($filepath)){
			$source = file($filepath);	
		
			print self::printCode($source, $filepath);
			
		} else {
			echo "the Target is not a file, there isn't nothing to show";
		}
		jexit();
	}

	function printCode($code){
		// Loop through our array, show HTML source as HTML source; and line numbers too.
		$numeri = '';
		$num = 1;
		foreach ($code as $line) {
			$numeri .= ($num) . '</br>';
			$num++;
		}
		echo '<table style="width:100%">';
		echo '<tr><td style="vertical-align:top; text-align: center; background-color: #EEEEEE; width:20px;"><pre>'. $numeri . '</pre></td>';
		echo '<td style="vertical-align:top; text-align: left;"><pre style="width: 100%">'. htmlspecialchars(implode($code)) .'</pre></td></tr>';
		echo '</table>';
	}
	
}
class JhacksThirdTable
{
	var $tableName;
	var $select;
	var $join;
	var $order;
	var $extra;

	function __construct($tableName)
	{
		$this->tableName = $tableName;
	}
	function setQuery($select = null, $join = null, $where = null, $order = null, $extra = null)
	{
		$this->select =	$select;
		$this->join = 	$join;
		$this->where = 	$where;
		$this->order = 	$order;
		$this->extra = 	$extra;

	}
	function loadObjectList()
	{
		$db = JFactory::getDBO();


		$query = 		'SELECT *' . $this->select
					. 	' FROM #__' . $this->tableName;

		if ($this->join)
			$query .=	", " . $this->join;

		if ($this->where)
			$query .= 	' WHERE ' . $this->where;

		if ($this->order)
			$query .= 	' ORDER BY '. $this->order;

		if ($this->extra)
			$query .=	" " . $this->extra;

		$db->setQuery( $query );

		return $db->loadObjectList();
	}
}

class JhacksViewLevels
{
	function getList($select = '', $join = '', $where = '', $order = '', $extra = '')
	{
		$third = new JhacksThirdTable('viewlevels');
		$third->setQuery($select, $join, $where, $order, $extra);

		return $third->loadObjectList();
	}


}



