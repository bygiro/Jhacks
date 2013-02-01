<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Script file of Jhacks component
 */
class com_jhacksInstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent)
	{
		// remove the frontend folder com_jhacks
		rmdir(JPATH_SITE . DS . 'components' . DS . 'com_jhacks');
		
		// $parent is the class calling this method
		$parent->getParent()->setRedirectURL('index.php?option=com_jhacks');
	}

	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent)
	{
		// if config says REMOVE HACKS allora unpublish all the process e POI disinstalla
		//TODO : WRITE HERE YOUR CODE
		echo '';
	}

	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent)
	{
		//TODO : WRITE HERE YOUR CODE
		echo '';
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent)
	{
		// $type is the type of change (install, update or discover_install)

		//TODO : WRITE HERE YOUR CODE
		echo '';
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent)
	{
		// define the following parameters only if it is an original install
		if ( $type == 'install' ) {
			$params['upload_maxsize'] = '2';
			$params['trash_dir'] = 'administrator/components/com_jhacks/files/trash';
			$params['upload_dir_operations_data_file'] = 'administrator/components/com_jhacks/files/operations';
			$params['backup_dir'] = 'administrator/components/com_jhacks/files/backup';
			$params['load_jquery'] = '1';
			$params['use_log'] = '1';
			$params['comment_marker'] = '1';
			
			self::setParams( $params , 1);
		}
 
		echo '';		
	}

	/*
	 * get a variable from the manifest file (actually, from the manifest cache).
	 */
	function getParam( $name ) {
		$db = JFactory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "com_jhacks"');
		$manifest = json_decode( $db->loadResult(), true );
		return $manifest[ $name ];
	}
	
	/*
	 * sets parameter values in the component's row of the extension table
	 */
	function setParams($param_array, $newInstall = null) {
		if ( count($param_array) > 0 ) {
			// read the existing component value(s)
			$db = JFactory::getDbo();
			$db->setQuery('SELECT params FROM #__extensions WHERE name = "com_jhacks"');
			$params = json_decode( $db->loadResult(), true );
			// add the new variable(s) to the existing one(s)
			foreach ( $param_array as $name => $value ) {
				$params[ (string) $name ] = (string) $value;
			}
			
			if($newInstall){
				// store the new values as a JSON string
				$paramsString = json_encode( $param_array );
			} else {
				// store the combined new and existing values back as a JSON string
				$paramsString = json_encode( $params );
			}
			$db->setQuery('UPDATE #__extensions SET params = ' .
				$db->quote( $paramsString ) .
				' WHERE element = "com_jhacks"' );
			$db->query();
		}
	}
	
}
