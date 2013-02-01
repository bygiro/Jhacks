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

jimport('joomla.filesystem.file');

@define("JHACKS_UPLOAD_EXTENSIONS_JOINED", "");

class JhacksFile extends JFile
{

	function __construct($uploadFolder)
	{

	}

	function fileExtension($file)
	{
		$ext = $ext3 = JFile::getExt($file);

		foreach(explode(",", JHACKS_UPLOAD_EXTENSIONS_JOINED) as $joinedExt)
		{
			$parts = explode(".", trim($joinedExt));
			for($i = count($parts)-1 ; $i > 0 ; $i--)
			{

				if ($ext3 == $parts[$i])
				{
					$file = self::stripExt($file);
					$ext2 = self::getExt($file);

					if ($ext2 == $parts[$i-1])
						$ext = $ext2 .".". $ext;

					$ext3 = $ext2;
				}

			}

		}

		return strtolower($ext);
	}

	function fileBase($file)
	{
		$base = self::stripExt($file);
		$ext3 = self::getExt($base);

		foreach(explode(",", JHACKS_UPLOAD_EXTENSIONS_JOINED) as $joinedExt)
		{
			$parts = explode(".", trim($joinedExt));
			for($i = count($parts)-2 ; $i >= 0 ; $i--)
			{
				if ($ext3 == $parts[$i])
				{
					$base = self::stripExt($base);
					$ext2 = self::getExt($base);

					if ($ext2 == $parts[$i-1])
						$ext = $ext2 .".". $ext;

					$ext3 = $ext2;
				}
			}
		}

		return self::stripExt($file);
	}

	function bytes($val)
	{
		$val = trim($val);
		if(empty($val))
		{
			return 0;
		}
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			case 'g':
			$val *= 1024;
			case 'm':
			$val *= 1024;
			case 'k':
			$val *= 1024;
		}
		return (int)$val;
	}

	function bytesToString($bytes)
	{
		$suffix = "";
		$units = array('K', 'M', 'G', 'T');

		$i = 0;
		while ($bytes >= 1024)
		{
			$bytes = $bytes / 1024;
			$suffix = $units[$i];
			$i++;
		}

		return round($bytes, 2) . $suffix;
	}


	function getMime($file)
	{
		if (!self::exists($file))
			return null;

		$mime = null;

		//prefered order methods to call the mime decodage
		$mimeMethods = array(
			'mime_content_type',
			'finfo_file',
			'image_check',
			'system',
			'shell_exec',
		);

		foreach($mimeMethods as $method)
		{
			if (!$mime)
			switch($method)
			{

				case 'system':
					if (!function_exists('system'))
						continue;

					$mime = system("file -i -b " . $file);
					break;

				case 'shell_exec':
					if (!function_exists('shell_exec'))
						continue;

					$mime = trim( @shell_exec( 'file -bi ' . escapeshellarg( $file ) ) );
					break;

				case 'mime_content_type':
					if (!function_exists('mime_content_type'))
						continue;
					$mime = mime_content_type($file);
					break;


				case 'finfo_file':
					if (!function_exists('finfo_file'))
						continue;
					$finfo = finfo_open(FILEINFO_MIME);
					$mime = finfo_file($finfo, $file);
					finfo_close($finfo);
					break;


				case 'image_check':
					$file_info = getimagesize($file);
					$mime = $file_info['mime'];
					break;

			}

		}

		//DEFAULT MIME
		if (!$mime)
			$mime = "application/force-download";

		return $mime;
	}

	function downloadUrl($path, $dirPattern, $options = array())
	{
		if (!preg_match("/^\[.+\]/", $path))
			$path = "[" . $dirPattern . "]" .DS. $path;

		$url = JURI::base(true) . "/index.php?option=com_jhacks&task=file"
			.	"&path=" . $path
			. 	"&action=download";

		foreach($options as $key => $value)
			$url .= "&" . $key . "=" . $value;

		return $url;
	}

	function getMarkers()
	{

		$configMedias = JComponentHelper::getParams('com_media');
		$config = JComponentHelper::getParams('com_jhacks');



		return array(
			'DIR_OPERATIONS_DATA_FILE' => $config->get("upload_dir_operations_data_file", "[COM_SITE]" .DS. "files" .DS. "operations_data_file") .DS,
			'DIR__TRASH' => $config->get("trash_dir", JPATH_ADMIN_JHACKS .DS. "images" . DS . "trash") .DS,

			'COM_ADMIN' => JPATH_ADMIN_JHACKS,
			'ADMIN' => JPATH_ADMINISTRATOR,
			'COM_SITE' => JPATH_SITE_JHACKS,
			'IMAGES' => JPATH_SITE .DS. $config->get('image_path', 'images')  .DS,
			'MEDIAS' => JPATH_SITE .DS. $configMedias->get('file_path', 'images') .DS,
			'ROOT' => JPATH_SITE
		);

	}

	function parsePath($path)
	{
		// Protect against back dir (../)
		if (DS == "\\")
			$ds = "\\\\";
		else
			$ds = "\/";

		$path = preg_replace("/\.\." .$ds. "/", "", $path);


		$markers = self::getMarkers();


		//DIR SPECIFIC FOLDERS (Starts with DIR... )
		foreach($markers as $marker => $pathStr)
			if (substr($marker, 0, 3) == "DIR")
				$path = preg_replace("/\[" . $marker . "\]/", $pathStr, $path);



		//OTHER MARKERS
		foreach($markers as $marker => $pathStr)
			if (substr($marker, 0, 3) != "DIR")
				$path = preg_replace("/\[" . $marker . "\]/", $pathStr, $path);


		$path = preg_replace("/\[.+\]/", "", $path);  // Clean tags if remains



		return $path;
	}

	function returnFile($mode = null)
	{
		$path	= JRequest::getVar('path');
		$size	= JRequest::getVar('size');
		$action	= JRequest::getVar('action');
		$attrs	= JRequest::getVar('attrs');

		$filePath = self::parsePath($path);
		$ext = self::getExt($filePath);



		jimport('joomla.filesystem.file');

		$imagesExt = array('jpg', 'jpeg', 'gif', 'png', 'bmp');
		$mime = null;
		if ($action == 'download')
			$mime = 'application/force-download';  // OU    application/octet-stream
		else if (self::exists($filePath))
			$mime = self::getMime($filePath);


		//Is image ?
		if (($action != 'download') &&
		(in_array($ext, $imagesExt)		//Check on extension
		|| ($mime && preg_match("/^image/", $mime)))			//Check on mime
		)
		{

			require_once(JPATH_ADMIN_JHACKS .DS. 'classes' .DS. 'images.php');
			$thumb = new JhacksImages($filePath, $mime);


			if ($attrs)
				$thumb->attrs($attrs);


			if ($size && preg_match("/([0-9]+)x([0-9]+)/", $size, $matches))
			{
				$thumb->width($matches[1]);
				$thumb->height($matches[2]);;
			}

			$thumb->get();

			exit();


		}
		else if (!JFile::exists($filePath))
		{
			$app = JFactory::getApplication();
			$msg = JText::sprintf( "JHACKS_UPLOAD_FILE_NOT_FOUND", $path);
			$app->enqueueMessage($msg, 'error');

			jexit();
		}


		//Non image and non outputable mimes : Force download
		if (!in_array($mime, array(
								'application/x-shockwave-flash'
							)))
		{
			header('Content-Description: File Transfer');
		    header("Content-Disposition: attachment; filename=\"".basename($filePath) . "\"");
		}



		//Read and return file contents with original mime header
	    header('Content-Type: ' . $mime);
	    header('Content-Transfer-Encoding: binary');
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($filePath));
	    ob_clean();
	    flush();

	    readfile($filePath);



		jexit();

	}



	function deleteFile($path, $remove = 'delete')
	{
		$op = array('thumbs', 'trash', 'delete');
		
		
		$filePath = self::parsePath($path);		
 		$filepath = JPath::clean(JPATH_SITE . DS . $filePath);
		
		
		if (self::exists($filePath))
		{
			if (in_array($remove, array('trash')))
			{
				$trashPath = self::parsePath("[DIR__TRASH]");
				if (!JFolder::exists($trashPath))
					JFolder::create($trashPath);

				if (!self::move($filePath, $trashPath .DS. self::getName($filePath)))
					return false;
			}
			else if (in_array($remove, array('delete')))
			{
				if (!self::delete($filePath))
					return false;
			}
		}


		$thumbs = in_array($remove, array('thumbs', 'trash', 'delete'));

	//DELETE THUMBS
		if (!$thumbs)
			return true;

		$dir = dirname($filePath);
		if (!JFolder::exists($dir))
			return true;


		$fileName = self::getName($filePath);
		$len = strlen($fileName);
		foreach(JFolder::files($dir,'.',false,false,array('.svn', 'CVS','.DS_Store','__MACOSX'),array()) as $file)
			if (substr($file, 0, $len +1) == "." . $fileName)
				self::delete($dir .DS. $file);



		return true;
	}


}