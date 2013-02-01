<?php
/**
* @version		1.6
* @package		Jhacks
* @copyright	2012 - Girolamo Tomaselli
* @author		Girolamo Tomaselli - http://bygiro.com - girotomaselli@gmail.com
* @license		GNU/GPL

* This version may have been modified pursuant to the GNU General Public License,
* and as distributed it includes or is derivative of works licensed under the
* GNU General Public License or other free or open source software licenses.
*/


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class JhacksProcessesCreator {
	
	var $operation;
	var $db;
	var $startingPoint;
	var $processescreated;
	var $filestoprocess;
	var $newprocess;
	var $allfiles;
	var $allSubfolders;
	var $logData;
	var $errorText;
	var $result;
	var $totalFound;
	var $app;
	var $doc;
	var $config;	
	
    function __construct() {
		$this->app 			= JFactory::getApplication();
		$this->doc 			= JFactory::getDocument();
		$this->db 			= JFactory::getDBO();
		$this->config 		= JComponentHelper::getParams('com_jhacks');

		$this->logData			= '';
		$this->errorText		= '';
		$this->result			= "undefined";
		$this->totalFound		= 0;
		$this->processescreated	= 0;

		set_time_limit(0);
		ignore_user_abort(true);
	}
	
	function findNewProcesses($post){
		
		$this->startingPoint 	= JPath::clean(JPATH_SITE . DS . $post['target']);
		
		if ($post['all_subfolders'] == 1){
			$this->allSubfolders = 1;
		}		
		
		$this->allfiles = $this->findFilesToProcess($this->startingPoint);

		$model = JModel::getInstance('operation', 'JhacksModel');
		$this->operation = $model->getItem((int)$post['operation_id']);		
		$this->operation->search_key = base64_decode($this->operation->search_key);
		$this->operation->replacement = base64_decode($this->operation->replacement);
		
		$this->LogInitialize();
		
		$pros_created = array();
		foreach ($this->allfiles as $item){
			$this->createProcesses($item);
			
			if ($this->newprocess->ok == 1){
				$pros_created[] = $this->newprocess->id;
			}
		}
				
		$this->report();
				
		if($this->config->get("use_log")){		
			// write log of the task
			JhacksHelper::writeLog('process', 'creation', $this->result, $this->logReport);
		}
		
	}
	
	function createProcesses($path) {
		$type = $this->operation->type;
		
		$good_process = 0;
		switch ($type) {
			case 'replacepattern':
				if (is_file($path)){
					
					$subject = JhacksHelper::stringfix(file_get_contents($path));
					$searchKey = JhacksHelper::stringfix($this->operation->search_key);
					$found = substr_count($subject, $searchKey);

					if ($found > 0){
						$good_process = 1;
						$this->totalFound += $found;
						$this->logData .= " $found matches found in $path<br />";
					} else {
						$this->logData .= " NO matches found in $path<br />";
					}
					
					//unset($subject);
				} else {
					$this->logData .= "$path is not a file<br />";
				}
			break;
				
			case 'replacefile':
			case 'createfolder':
				if (!is_file($path)){
					$good_process = 1;
				}
			break;
			
			default:

			break;
			
		}

		$this->newprocess = new stdClass;
		if ($good_process == 1){
			$this->newprocess->id = $this->addProcess($path);
			++$this->processescreated;
		} else {
			unset($this->newprocess);
		}
		
	}		

	function addProcess($path){
		$db = $this->db;
		
		$operation_id 	= $db->quote($this->operation->id);
		$relativePath = JhacksHelper::getRelativePath($path);
		
		if (is_dir($path)) {
			$target_folder	= $db->quote($relativePath);
			$target			= $db->quote('');			
		} else {
			$target_folder	= $db->quote(dirname($relativePath));
			$target			= $db->quote(basename($relativePath));
		}		
			
		$db->setQuery("INSERT INTO #__jhacks_processes (pro_operation_id, target_folder, target, occurrences, pro_publish) VALUES (". $operation_id .", ".$target_folder.", ".$target.", 0, 0 ) ");	
		$db->query();
		
		if ($db->getErrorNum()) {
			$this->errorText .= $db->getErrorMsg();
			$this->newprocess->ok = 0;
		} else {
			$this->newprocess->ok = 1;
			$check = new JhacksChecking();
			$check->checkProcesses();			
		}
		
		return $db->insertid();
	}

	/**
     * Get an array that represents directory tree
     * @param bool $recursive         Include sub directories
     */

    function findFilesToProcess($startingPath) {
		
		$recursive = $this->allSubfolders;
		
		if (is_file($startingPath)) {
			$startingPath = dirname($startingPath);
		}
		
		if ($recursive){
			$iterator = JhacksHelper::findpaths($startingPath);

			$arrPaths = array();		
			foreach ($iterator as $path) {
				$arrPaths[] = $path->__toString();
			}
		} else {
			$arrPaths = array_diff(scandir($startingPath), array('..', '.'));
			foreach ($arrPaths as &$path){
				$path = JPath::clean($startingPath. DS .$path);
			}
		}

        return $arrPaths;
    }
	
	function LogInfo() {
		$info = '';
		
		$yesORnot = 'no';
		if($this->allSubfolders){
			$yesORnot = 'yes';
		}
		$info .= "<tr><td>Operation id:</td><td>". $this->operation->id ."</td></tr>";
		$info .= "<tr><td>Target folder:</td><td>". $this->startingPoint ."</td></tr>";
		$info .= "<tr><td>All subfolders:</td><td>". $yesORnot ."</td></tr>";
		$info .= "<tr><td colspan=\"2\"><hr /></tr>";
		$info .= "<tr><td>Total paths scanned:</td><td>". count($this->allfiles) ."</td></tr>";
		$info .= "<tr><td>Total new processes created:</td><td>". $this->processescreated ."</td></tr>";

		return $info;
	}	
	
	function report(){
		if ($this->logErrors == ''){
			$this->result = "successful";
		} else {
			$this->result = "failed";
		}
				
		$color = 'red';
		if ($this->result == 'successful'){
			$color = 'green';
		}
		
		$this->logReport = '<table style="width:100%"><tr><td style="text-align: center; border: 1px solid '. $color .';" colspan="2"><span style="font-size: 16px; color: '. $color .'; font-weight: bold;">'. $this->result .'</span> - Task: New Processes creation</td></tr>';
		$this->logReport .= $this->logInfo();
		$this->logReport .= "<tr><td>Execution Info:</td><td>". $this->logData ."</td></tr>";
		$this->logReport .= "<tr><td>Errors:</td><td>". $this->logErrors ."</td></tr>";
		$this->logReport .= "</table><br />";	
	}		
}