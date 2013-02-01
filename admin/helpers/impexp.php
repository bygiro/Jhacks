<?php
/**
* @version		1.0
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

class ImportExport{
	var $app;
	var $doc;
	var $config;
	var $tempFolder;
	var $db;
	var $logData;
	var $logErrors;
	var $logReport;
	var $result;
	
    function __construct() {
		$this->app = JFactory::getApplication();
		$this->doc = JFactory::getDocument();
		$this->config = JComponentHelper::getParams('com_jhacks');
		$this->db = JFactory::getDBO();
		$this->operationsFolder = JPath::clean( JPATH_SITE . DS . $this->config->get('upload_dir_operations_data_file') . DS );
		$this->logData = '';
		$this->logErrors = '';
		$this->logReport = '';
		$this->result = 'undefined';
	}
		
	function string2File($filename, $string, $opId){
		
		$content = base64_decode($string);
		$structure = dirname($filename);
		
		if (!file_exists($structure)) {
			if (!mkdir($structure, 0644, true)){
				$this->logErrors .= "ERROR - Can not create the folder: $structure<br />";	
			} else {
				if (!file_exists($structure . DS . 'index.html')){
					file_put_contents($structure . DS . 'index.html', 'What are you doing?!');
				}
			}
		}

		$realname = JhacksHelper::getRealFilename(basename($filename));
		$newFilename = JhacksHelper::makeOperationFilename($realname, $opId);
		$newfile =  $structure . DS . $newFilename;		
		
		if(file_put_contents($newfile, $content)){
			chmod($filename, 0744);
		} else {
			$this->logErrors .= "ERROR - Can not create the $filename<br />";	
		}
	}
	
	function getJhacksVer(){
		$db = $this->db;
		$query = "SELECT manifest_cache "
		.		 " FROM #__extensions"
		.		 " WHERE type = 'component' AND element = 'com_jhacks'";
		
		$db->setQuery($query);
		$result = $db->loadObject();
		$data = json_decode($result->manifest_cache);

		return $data->version;
	}
	
	// IMPORT	
	function importFile($file){
		$db = $this->db;
		$data = file_get_contents($file);
		
		$hacks = unserialize($data);
		foreach($hacks as $hack){
			// $skip = array('total_tmpl','total_lang','total_operations','operations', 'tmpl_overrides', 'lang_overrides', 'processes', 'data_file', 'data_file_content', 'search_key', 'replacement', 'file_content');
			$hackmodel = JModel::getInstance('hack', 'JhacksModel');
			
			If($hackmodel->save($hack)){
				$newHackid = $hackmodel->_id;
				$this->logData .= "Imported a new hack id: $newHackid<br />";
			} else {
				$this->logErrors .= 'Error: I cannot save the hack: ' . $hack->name . ' into the DB';
			}
			
			
			if($newHackid){
				// import operations and then processes
				foreach($hack->operations as $operation){
									
					$opermodel = JModel::getInstance('operation', 'JhacksModel');
					$cleanOp = $operation;
					$cleanOp->hack_id = $newHackid;					
					$cleanOp->data_file = '';
					
					If($opermodel->save($cleanOp)){
						$newOpid = $opermodel->_id;
						$this->logData .= "Imported a new operation id: $newOpid<br />";
						
						$realname = JhacksHelper::getRealFilename($operation->data_file);
						$newFilename = JhacksHelper::makeOperationFilename($realname, $newOpid);							
						$operation->id = $newOpid;
						$operation->search_key = base64_decode($operation->search_key);
						$operation->replacement = base64_decode($operation->replacement);						
						if($operation->data_file != ''){
							$operation->data_file = $newFilename;
							// save the file into the jhacks directory
							self::string2File($newFilename, $operation->data_file_content, $newOpid);
						}
						If($opermodel->save($operation)){
							$this->logData .= "Saved the data file for the operation id: $newOpid<br />";
						} else {
							$this->logErrors .= 'Error: I cannot save the data file for the operation: ' . $operation->op_name . ' into the DB';
						}
						
					} else {
						$this->logErrors .= 'Error: I cannot save the operation: ' . $cleanOp->op_name . ' into the DB';
					}

					// import processes
					if($newOpid){
						foreach($operation->processes as $process){
							$process->pro_operation_id = $newOpid;							
							$promodel = JModel::getInstance('process', 'JhacksModel');
							
							If($promodel->save($process)){
								$this->logData .= "Imported a new process id: ". $promodel->_id ."<br />";
							} else {
								$this->logErrors .= 'Error: I cannot import the process with TARGET: ' . $process->target_folder . $process->target .' into the DB';
							}
						}
					}
				}
			}
		}
		
		$this->report();

		if($this->config->get("use_log")){		
			// write log of the task
			JhacksHelper::writeLog('hack', 'impexp', $this->result, $this->logReport);
		}		
		return true;
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
		
		$this->logReport = '<table style="width:100%"><tr><td style="text-align: center; border: 1px solid '. $color .';" colspan="2"><span style="font-size: 16px; color: '. $color .'; font-weight: bold;">'. $this->result .'</span> - Task: Import Hacks</td></tr>';
		$this->logReport .= "<tr><td>Execution Info:</td><td>". $this->logData ."</td></tr>";
		$this->logReport .= "<tr><td>Errors:</td><td>". $this->logErrors ."</td></tr>";
		$this->logReport .= "</table><br />";	
	}	
}