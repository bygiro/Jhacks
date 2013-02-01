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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Jhacks Component JhacksController Controller
 *
 * @package		Joomla
 * @subpackage	Jhacks
 *
 */
class JhacksController extends JController
{
	var $context;

	/**
	 * Constructor
	 *
	 */
	function __construct()
	{
		parent::__construct();

	}

	function can($accesses, $taskString, $itemAccess = null)
	{
		if (!is_Array($accesses))
			$accesses = array($accesses);

		if (isset($itemAccess))
			$acl = $itemAccess;
		else
			$acl = JhacksHelper::getAcl();


		foreach($accesses as $access)
		{
			if ($acl->get($access))
				return true;
		}

		JError::raiseWarning(403, JText::sprintf( "JHACKS_ACL_UNAUTORIZED_TASK", $taskString) );
		$this->setRedirect(JhacksHelper::urlRequest());
		return false;
	}

	function _apply($data)
	{

		$model = $this->getModel($this->singular);
		$item = $model->getItem();

		if ((int)$item->id > 0)
		{
			//Check Item ACL
			if (!$this->can('access-edit', JText::_("JHACKS_JTOOLBAR_EDIT"), $item->params))
				return;

		}


        if ($model->save($data))
        {
			JRequest::setVar('cid', $model->getId());
        	$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('JHACKS_CONTROLLER_DONE'));
        	return array($model->_id);
		}

    	JError::raiseWarning( 1000, JText::_('JHACKS_CONTROLLER_ERROR') );

		return false;

	}

	function _save($data)
	{

		$model = $this->getModel($this->singular);
		$item = $model->getItem();

		if ((int)$item->id > 0)
		{
			//Check Item ACL
			if (!$this->can('access-edit', JText::_("JHACKS_JTOOLBAR_EDIT"), $item->params))
				return;

		}


        if ($model->save($data))
        {
        	JRequest::setVar('cid', $model->getId());
        	$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('JHACKS_CONTROLLER_DONE'));
        	return array($model->_id);
        }

		JError::raiseWarning( 1000, JText::_('JHACKS_CONTROLLER_ERROR') );

		return false;
	}

	function _delete($cid)
	{

		$model = $this->getModel($this->singular);

	    if ($model->delete($cid))
	    {
	    	JRequest::setVar('cid', 0);
        	$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('JHACKS_CONTROLLER_DONE'));
        	return true;
	    }

        JError::raiseWarning( 1000, JText::_('JHACKS_CONTROLLER_ERROR') );

		return false;
	}



	function _upload($itemid, $fieldName, &$post, $extensions = null, $options = array(), $dir = null)
	{
		require_once(JPATH_ADMIN_JHACKS .DS. "classes" .DS. "upload.php");
		$config	= JComponentHelper::getParams( 'com_jhacks' );

		if (!$dir)
			$dir = JPATH_SITE . DS . $config->get('upload_dir_' . $this->ctrl . '_' . $fieldName, basename(JPATH_ADMINISTRATOR) . DS . 'components' . DS . 'com_jhacks' . DS . 'files');

	//Check Remove action
		$remove	= JRequest::getCmd('__' . $fieldName . '_remove', '');

	//Check Upload process
		$processUpload = false;
		$uploadFile =  JRequest::getVar( $fieldName, array(), 'files','array');

	//Check method
		$method = '';
		if (isset($uploadFile['name']))
		{
			$method = 'uploader_empty';
			if ($uploadFile['name'] != "")
				$method = 'uploader';
		}

	//Check if changed
		if ($method == '' && isset($post["__" . $fieldName]))
			$changed = ($post["__" . $fieldName] != $post[$fieldName]);
		else if ($method == 'uploader_empty')
			$changed = false;
		else
			$changed = true;

	//Attempt to unset previous
		if (($remove != '') && !$changed)
			$post[$fieldName] = "";

	//Process unlink/remove
		if (in_array($remove, array('thumbs', 'trash', 'delete')))
		{
			$fileName = $post["__" . $fieldName];
			$f = (preg_match("/\[.+\]/", $fileName)?"":$dir.DS) . $fileName;
			if (!JhacksFile::deleteFile($f, $remove)){
				JError::raiseWarning( 4101, JText::_("") );
			}
		}

	//Ignore the post value, in order to keep old
		if (($method == '') || ($method == 'uploader_empty'))
		{
			//Leave unchanged in case of imagepicker
			if (isset($post[$fieldName]) && ($post[$fieldName] == "") && ($remove == ''))
				unset($post[$fieldName]);

			return true;		// Request does not contain file to upload (in case of partial update)
		}

	// Process Upload
		$uploadClass = new JhacksUpload($dir);
		$uploadClass->setAllowed($extensions);

		if (!$result = $uploadClass->uploadFile($uploadFile, $options))
		{
			JError::raiseWarning( 4100, JText::_("") );
			$this->setRedirect(JhacksHelper::urlRequest());
			return false;
		}

		$post[$fieldName] = $result->filename;
		return true;
	}



}
