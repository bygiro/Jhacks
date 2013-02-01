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

class JhacksFindReplace {
	
	var $db;
	var $backupFolder;
	var $original_md;
	var $modified_md;
	var $filepermission;
	var $logData;
	var $logReport;
	var $logErrors;
	var $totalReplaced;
	var $processesFailed;
	var $processes;
	var $process;
	var $operations;
	var $operation;
	var $hacks;
	var $hack;
	var $result;
	var $app;
	var $doc;
	var $config;
	
    function __construct() {
		$this->app 		= JFactory::getApplication();
		$this->doc 		= JFactory::getDocument();
		$this->db 		= JFactory::getDBO();
		$this->config 	= JComponentHelper::getParams('com_jhacks');
		
    }

	function initProcess(){

		$this->logData			= '';
		$this->logReport		= '';
		$this->logErrors		= '';
		$this->totalReplaced	= 0;
		$this->result			= "undefined";
		
		$this->backupFolder	= JPATH_SITE . DS . $this->config->get("backup_dir");
		$this->original_md	= null;
		$this->modified_md	= null;
		$this->processesFailed = array();
	}
	
	function replacementString(){
		if ($this->process->_pro_operation_id_type == 'createfolder' OR $this->config->get('comment_marker') < 1 OR !$this->process->pro_target) {
			$replacement = $this->process->_pro_operation_id_replacement;
		} else {
			if(!$this->process->id) {
				$this->logErrors .= "ERROR - Missing the process ID <br />";
			} else {
				// adding comment marker based on the file extension
				$ext = JhacksHelper::findExtension($this->process->pro_target);
				switch ($ext){
					case 'php':
					case 'PHP':
					case 'css':
					case 'CSS':
					case 'js':
					case 'JS':					
						$open = "/********";
						$close = "********/";
					break;

					case 'html':
					case 'HTML':
						$open = "<!-- - ---";
						$close = "--- - -->";					
					break;
					
					case 'ini':
					case 'INI':
						$open = "; --- ---";
						$close = "- --- ---";					
					break;
					
					default:
						$open = "";
						$close = "";
					break;
				}
			
				$replacement	= $open ." jhacks START - operation ID {". $this->process->pro_operation_id ."} - process ID {". $this->process->id ."} ". $close . PHP_EOL;
				$replacement	.= $this->process->_pro_operation_id_replacement . PHP_EOL;	  
				$replacement	.= $open ." jhacks  END  - operation ID {". $this->process->pro_operation_id ."} - process ID {". $this->process->id ."} ". $close . PHP_EOL;
			}
		}
		
		return $replacement;
	}

	function publish($process, $publish) {
		$this->initProcess();
		$this->process = $process;
		$this->process->pro_target = JPath::clean(JPATH_SITE . DS . $this->process->target_folder . DS . $this->process->target);
		$this->process->_pro_operation_id_data_file	= JPath::clean( JPATH_SITE . DS . $this->config->get('upload_dir_operations_data_file') . DS . $this->process->_pro_operation_id_data_file);
		$this->process->_pro_operation_id_search_key = base64_decode($this->process->_pro_operation_id_search_key);
		$this->process->_pro_operation_id_replacement = base64_decode($this->process->_pro_operation_id_replacement);
		$this->process->publish_way = $publish;
			
		set_time_limit(0);
		ignore_user_abort(true);			
		if ($publish == 0){ // unpublish
				$this->smartReplaceInverse();
		} else { // publish
			if($this->process->conflicts != ''){
				$this->logErrors .= "ERROR - the process id: ". $this->process->id ." has conflicts, please fix them and try again.<br />";
			} else {
				$this->smartReplace();
			}
		}
		
		$this->report();
	
		if($this->config->get("use_log")){		
			// write log of the task
			JhacksHelper::writeLog('process', 'publish', $this->result, $this->logReport);
		}
		
		if($this->result == 'failed'){
			return false;
		}
		
		return true;
	}

	function smartReplace() {
		$type = $this->process->_pro_operation_id_type;
		$file = $this->process->pro_target;
		$data_file = $this->process->_pro_operation_id_data_file;
				
		if ($type == 'replacepattern'){
			$subject = JhacksHelper::stringfix(file_get_contents($file));
			$searchKey = JhacksHelper::stringfix($this->process->_pro_operation_id_search_key);
			$this->filepermission = fileperms($file);

		}	
		
		$replacementString = $this->replacementString();
		
		$replaced = 0;
		switch ($type) {
			case 'replacepattern':
				if (is_file($file)){
					$result_replace = $this->replacepattern($subject, $searchKey, $replacementString);
					
					$outputStr = $result_replace->outputstr;
					$replaced = $result_replace->replaced;
					
					if ($replaced > 0){
						$this->logData .= "Searching File $file : Content replaced in $replaced places<br />";
					} else {
						$this->logData .= "Searching File $file: search key not found!<br />";
					}					
					
				}
			break;
				
			case 'replacefile':
				if (!file_exists($data_file)){
					$this->logErrors .= "ERROR - the file $data_file does not exist.<br />";
				}
				
				if ($this->logErrors == '') {
					if (is_file($file)){
						if($this->backupFile($file)){
							$original_perm = fileperms($file);
							if(!copy($data_file, $file)) {
								$this->logErrors .= "ERROR - Can not copy the $data_file to $file.<br />";
							} else {
								chmod($file, $original_perm);
								$this->logData .= " File $file : the file has been replaced <br />";
								$replaced = 1;

								$this->modified_md = md5_file($file);
							}
						}
					} else {
						$directory = $file;
						
						$newfile = JPath::clean($directory . DS . JhacksHelper::getRealFilename(basename($data_file)));
					
						if(!copy($data_file, $newfile)) {
							$this->logErrors .= "ERROR - Can not add the ". $data_file ." into the folder: ". dirname($newfile) ." .<br />";	
						} else {
							chmod($newfile, 0744);
							$this->logData .= " The file $data_file has been added into the folder: ". dirname($newfile) ." <br />";
							$replaced = 1;

							$this->modified_md = md5_file($newfile);
						}						
					}
				}

			break;
			
			case 'createfolder':
				// remove whitespaces from dirname
				$foldername = $this->process->_pro_operation_id_replacement;
				
				if ($foldername == ''){
					$this->logErrors .= "ERROR - Please add a valid foldername.<br />";
				}

				$directory = $file;
				if (is_file($file)){
					$directory = dirname($file). DS;
				}
					
				$folder = JPath::clean($directory . $foldername);
				if (!file_exists($folder)){
					if ($this->logErrors == '') {
						if (!mkdir($folder, 0644)) {
							$this->logErrors .= "ERROR - Can not create the folder $folder.<br />";
						} else {
							$this->logData .= "New folder created: $folder<br />";
							$replaced = 1;
							
							// let's create an empty index.html to protect the folder content by curious people :-)
							if(file_put_contents($folder . DS . 'index.html','what are you doing?!')){
								chmod($folder . DS . 'index.html', 0744);
								$this->logData .= "New empty file index.html created in: $folder<br />";
							} else {
								$this->logData .= "ERROR - Can not create the index.html in: $folder<br />";	
							}
						}
					}
				} else {
					$this->logData .= "Folder already exist: $folder<br />";
				}
			break;
			
			default:
				$this->logErrors .= "ERROR - Operation Type Not Recognized";
			break;
			
		}

		if ($this->logErrors == '') {
			if ($type == 'replacepattern'){
				if ($this->backupFile($file)){
					$this->writeToFile($file, $outputStr);
				}
			}
			
			$this->updateProcess($replaced, 1);
		}
	}	
	
	function smartReplaceInverse() {
		$type = $this->process->_pro_operation_id_type;
		$file = $this->process->pro_target;
		$data_file = $this->process->_pro_operation_id_data_file;
		
		$replaced = 0;
		switch ($type) {
			case 'replacepattern':
			case 'replacefile':
				if (is_file($file)){
					if($this->restoringBackup($file)){
						$replaced = 1;
					}
				} else {
					$directory = $file;
						
					$newfile = JPath::clean($directory . DS . JhacksHelper::getRealFilename(basename($data_file)));					

					if(!unlink($newfile)) {
						$this->logErrors .= "ERROR - Can not delete the $newfile.<br />";	
					} else {
						$this->logData .= " The file $newfile has been deleted<br />";
						$replaced = 1;
					}
				}
			break;
			
			case 'createfolder':
				// remove whitespaces from dirname
				$foldername = $this->process->_pro_operation_id_replacement;
				$directory = $file;
				if (is_file($file)){
					$directory = dirname($file). DS;
				}
				$folder = JPath::clean($directory . DS . $foldername);
									
				if (($files = scandir($folder)) && count($files) <= 3){ //check if folder is empty
					if ($this->logErrors == ''){
						if(unlink($folder. DS .'index.html')){
							if (rmdir($folder)){
								$this->logData .= "Folder deleted: $folder<br />";
								$replaced = 1;
							} else {
								$this->logErrors .= "ERROR - Can not delete the folder: ".$folder."<br />";
							}
						} else {
							$this->logErrors .= "ERROR - Can not delete the file: ".$folder. DS ."index.html.<br />";
						}
					} else {
						$this->logData .= "DEMO mode: The folder $folder could be deleted.<br />";
						$replaced = 1;
					}
				
				} else {
					$this->logErrors .= "ERROR - I can not delete the $folder. It's not empty<br />";
				}
			break;
			
			default:
				$this->logErrors .= "ERROR - Operation Type Not Recognized";
			break;
			
		}

		if ($replaced > 0 AND $this->logErrors == ''){
			$this->updateProcess(0, 0);
		}		
	}

	function replacepattern($subject, $searchKey, $replacementString){				
		if ($this->process->_pro_operation_id_regex AND $searchKey){
			$outputStr = preg_replace($searchKey, $replacementString , $subject, -1, $replaced);				
		} elseif ($searchKey) {
			switch ($this->process->_pro_operation_id_replace_type) {
				case 'above':
					$replacement = $replacementString . $searchKey;
				break;
				
				default:
				case 'justreplace':
					$replacement = $replacementString;	
				break;

				case 'below':
					$replacement = $searchKey . $replacementString;
				break;			
			}
			
			$outputStr = str_replace($searchKey , $replacement, $subject, $replaced);
		} else {
			$outputStr = $replacementString;
			$replaced = 1;
		}
				
		$result_replace = new stdClass;
		$result_replace->outputstr = $outputStr;
		$result_replace->replaced = $replaced;
		
		return $result_replace;
	}
	
	function restoringBackup($file){
		$relativePath = JhacksHelper::getRelativePath($file);
		$filebackup = $this->backupFolder . DS . $relativePath;
		
		if (file_exists($filebackup)){
			if(!copy($filebackup, $file)) {
				$this->logErrors .= "ERROR - Can not restore the backup file $filebackup to $file.<br />";	
			} else {
				// restoring original permissions on file
				$original_perm = fileperms($filebackup);
				chmod($file, $original_perm);
				$this->logData .= "The file $file has been restored to the original<br />";
				$this->modified_md = md5_file($file);
				
				/* check if there are other process on the same file, if not the backup will be deleted */
				$related = JhacksHelper::findRelatedProcess($this->process);
				
				if(count($related) == 1){
					if(!unlink($filebackup)){
						$this->logErrors .= "ERROR - Can not delete the backup file $filebackup .<br />";
					} else {
						$this->logData .= "The backup file: $filebackup has been deleted, because it was not used by any published process<br />";
						
						// remove empty folders
						JhacksHelper::removeEmptySubfolders($this->backupFolder);
						
						// check for index.html
						JhacksHelper::recursive_check_index_exist($this->backupFolder);
					}
				}
				return true;
			}
		} else {
			$this->logErrors .= "ERROR - the backup file $filebackup is missing! I cannot restore the original file: $file.<br />";
			return false;
		}
	}
		
	function writeToFile($file, $data) {
		if(is_writable($file)) {
			if(file_put_contents($file,$data)){
				chmod($file, $this->filepermission);
				$this->modified_md = md5_file($file);
			}
			
		} else {
			$this->logErrors .= "ERROR - Can not replace text. File $file is not writable. Please make it writable<br />";	
		}
	}
	
	function getOriginalMD5($process){
		$items = JhacksHelper::findRelatedProcess($process);
		
		foreach ($items as $item){
			if($item->original_md != '' AND $item->original_md){
				$md5 = $item->original_md;
				break;
			}
		}
				
		return $md5;
	}
	
	function backupFile($file) {
		$relativePath = JhacksHelper::getRelativePath($file);
		$filebackup = $this->backupFolder . DS . $relativePath;
		$structure = dirname($filebackup);
		$this->original_md = md5_file($file);
		
		if (file_exists($filebackup)) {
			$this->logData .= "Backup of File $file : the backup already exist<br />";
			$this->original_md = $this->getOriginalMD5($this->process);
			
			return true;
		} elseif (file_exists($structure)) {
			// backup copy
			if(!copy($file, $filebackup)) {
				$this->logErrors .= "ERROR - Can not backup the $relativePath to $filebackup.<br />";	
			} else {
				$original_perm = fileperms($file);
				chmod($filebackup, $original_perm);
				$this->logData .= "Backup of File $relativePath : File copy backup completed<br />";
				
				/* check for index.html */
				JhacksHelper::recursive_check_index_exist($this->backupFolder);
				
				return true;
			}
			
		} else {
			// To create the nested structure, the $recursive parameter to mkdir() must be specified.
			if (!mkdir($structure, 0644, true)) {
				$this->logErrors .= "ERROR - Can not create the folder structure $structure.<br />";
			} else {
				$this->logData .= "Backup folder structure : created the folder structure $structure<br />";			
			}
			
			// backup copy
			if(!copy($file, $filebackup)) {
			 $this->logErrors .= "ERROR - Can not backup the $relativePath to $filebackup.<br />";	
			} else {
				chmod($filebackup, 0744);
				$this->logData .= "Backup of File $relativePath : File copy backup completed<br />";

				/* check for index.html */
				JhacksHelper::recursive_check_index_exist($this->backupFolder);
				
				return true;
			}
		}
		
		return false;		
	}
	
	function LogInfo() {
		$info = '';
		switch ($this->process->_pro_operation_id_type) {
			case 'replacepattern':
				$info .= "<tr><td>Process Type:</td><td>JUST REPLACE CONTENT</td></tr>";
			
				if ($this->process->_pro_operation_id_search_key) {
					$info .= "<tr><td>Searching for Key:</td><td><pre>". htmlspecialchars($this->process->_pro_operation_id_search_key) ."</pre></td></tr>";
				} else {
					$info .= '<tr><td colspan="2">No key to search, the content of the file will be completely replaced</td></tr>';
				}
		
				$info .= "<tr><td>Replacement data:</td><td><pre>". htmlspecialchars($this->process->_pro_operation_id_replacement) .'</pre></td></tr>';
			break;
			
			case 'replacefile':
				$info .= "<tr><td>Process Type:</td><td>ADD / REPLACE FILE</td></tr>";
				
			break;

			case 'createfolder':
				$info .= "<tr><td>Process Type:</td><td>CREATE FOLDER</td></tr>";	
				
			break;
			
			default:
			// nothing to do
			break;
		}
		return $info;
	}

	// to do: merge all the report functions in ONE
	function report(){
		if ($this->logErrors == ''){
			$this->result = "successful";
		} else {
			$this->result = "failed";
		}
		
		$way = 'Publish';
		if(!$this->process->publish_way){
			$way = 'Unpublish and Restoring';
		}
		
		$color = 'red';
		if ($this->result == 'successful'){
			$color = 'green';
		}
		
		$this->logReport = '<table style="width:100%"><tr><td style="text-align: center; border: 1px solid '. $color .';" colspan="2">Process ID: '. $this->process->id .' - <span style="font-size: 16px; color: '. $color .'; font-weight: bold;">'. $this->result .'</span> - Task: '. $way .'</td></tr>';
		$this->logReport .= $this->logInfo();
		$this->logReport .= "<tr><td>Execution Info:</td><td>". $this->logData ."</td></tr>";
		$this->logReport .= "<tr><td>Errors:</td><td>". $this->logErrors ."</td></tr>";
		$this->logReport .= "</table><br />";	
	}
	
	function updateProcess($occurrences, $publish){
		$db = $this->db;

		if ($publish) {
			$string = ", modified_md = ". $db->quote($this->modified_md)
					. ", original_md = ". $db->quote($this->original_md) ;
		} else {
			$string = ", modified_md = NULL "
					. ", original_md = NULL ";			
		}
		
		$db->setQuery(
		"UPDATE #__jhacks_processes SET"
		.	" occurrences = ". $occurrences
		.	", pro_publish = ". $publish
		.	$string
		.	" WHERE"
		.	" id = ". $this->process->id
		);
		
		$db->query();
		
		if ($db->getErrorNum()) {
			$this->logErrors .= $db->getErrorMsg();
		} else {
			/* update the MD5 */
			if ($publish){
				$this->updateMD5();
			}
		}
	}
	
	function updateMD5(){
		$db = $this->db;

		$db->setQuery(
		"UPDATE #__jhacks_processes SET"
		.	" modified_md = ". $db->quote($this->modified_md)
		.	", original_md = ". $db->quote($this->original_md)
		.	" WHERE"
		.	" pro_publish = 1 "
		.	" AND target_folder = ". $db->quote($this->process->target_folder)
		.	" AND target = ". $db->quote($this->process->target)
		);
		
		$db->query();
		
		if ($db->getErrorNum()) {
			$this->logErrors .= $db->getErrorMsg();
		}			
	}
	
}
