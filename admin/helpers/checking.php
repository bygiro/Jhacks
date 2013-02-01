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

class JhacksChecking {
	
	var $processes;
	var $pro;
	var $app;
	var $doc;
	var $config;	
	var $db;	
	
    function __construct($testmode = 1) {
		$this->app = JFactory::getApplication();
		$this->db  = JFactory::getDBO();
		$this->doc = JFactory::getDocument();
		$this->config = JComponentHelper::getParams('com_jhacks');
		
		$model = JModel::getInstance('processes', 'JhacksModel');
		$model->active('predefined', 'getoperation');

		$this->processes = $model->getItems();
		
		$this->pro = '';
	}
	
	function checkProcesses(){

		$model = JModel::getInstance('processes', 'JhacksModel');
		$model->active('predefined', 'getoperation');
		$pros = $model->getItems();

			
		foreach ($pros as $pro){
			$this->checkConflicts($pro);
		}
		
		return true;
	}	
	
	function checkConflicts($process){
		$conflicts = '';
		foreach($this->processes as $conflict){	
			if ($conflict->target_folder == $process->target_folder AND $conflict->id != $process->id){
				
				$in_conflict = 0;
				$type_conflict = '';

				$pro_file = JhacksHelper::getRealFilename($process->_pro_operation_id_data_file);
				$pro_searchkey = base64_decode($process->_pro_operation_id_search_key);
				
				$con_file = JhacksHelper::getRealFilename($conflict->_pro_operation_id_data_file);
				$con_searchkey = base64_decode($conflict->_pro_operation_id_search_key);
				
				switch ($process->_pro_operation_id_type){
					case 'replacepattern':
						if($process->target == $conflict->target){
							if ($conflict->_pro_operation_id_type == 'replacepattern'){ // both processes are replacepattern type
								// check if search_keys are contained one on each other
								$a_key_conflict = strpos($pro_searchkey, $con_searchkey);
								$b_key_conflict = strpos($con_searchkey, $pro_searchkey);
									if ($a_key_conflict !== false OR $b_key_conflict !== false){
									$in_conflict = 1;
									$type_conflict = "Same operation type and Search keys overlaps<br />";
								}
							}
						
							// replace/add file on a file used by replacepattern
							if ($conflict->_pro_operation_id_type == 'replacefile'){
								$in_conflict = 1;
								$type_conflict = "There is a file replacement on the target file<br />";
							}
						}
					break;
					
					case 'replacefile':
						if ($con_file == $pro_file){
							// same operation type and same target file
							if ($conflict->_pro_operation_id_type == 'replacefile'){	// replacing the same file
								$in_conflict = 1;
								$type_conflict = "The target file is replaced/added more than once<br />";									
							}
							
							// replace/add file on a file used by replacepattern
							if ($conflict->_pro_operation_id_type == 'replacepattern'){
								$in_conflict = 1;
								$type_conflict = "There is a replace pattern on the target file<br />";					
							}
						}						
					break;
					
					case 'createfolder':
						if ($conflict->_pro_operation_id_type == 'createfolder' AND 
							$conflict->_pro_operation_id_replacement == $process->_pro_operation_id_replacement AND 
							$process->target == $conflict->target){
								$in_conflict = 1;
								$type_conflict = "Another process is creating the same subfolder<br />";								
							}					
					break;
					
					case 'sql':
					
					break;
				}
				
				if ($in_conflict){
					$conflicts .= JText::_("JHACKS_PROCESS_ID") . $conflict->id .' - '. $type_conflict;
				}
				unset ($in_conflict);
			}			
		}
		
		if ($conflicts == ''){$conflicts = null;}
		
		if($conflicts != $process->conflicts){
			$this->updateProcess($process->id, $conflicts);
			$process->conflicts = $conflicts;
		}
	}	

	
	function updateProcess($id, $conflicts = null){
		$db = $this->db;

		$db->setQuery(
		"UPDATE #__jhacks_processes SET"
		.	" conflicts = ". $db->quote( $db->getEscaped($conflicts), false )
		.	" WHERE"
		.	" id = ". $id
		);
		
		$db->query();
		
		if ($db->getErrorNum()) {
			
		}
	}	
	
}