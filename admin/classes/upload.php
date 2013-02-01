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

require_once(JPATH_ADMIN_JHACKS .DS. "classes" .DS. "file.php");

@define("JHACKS_UPLOAD_RANDOM_CHARS", "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789");
@define("JHACKS_UPLOAD_DEFAULT_MAX_SIZE", 2097152);  //2Mb


//TODO
@define("JHACKS_UPLOAD_CHMOD_FOLDER", 0744);
@define("JHACKS_UPLOAD_CHMOD_FILE", 0644);


class JhacksUpload extends JhacksFile
{

	protected $uploadFolder;
	protected $allowedTypes;


	protected $maxSize;
	protected $file;

	protected $options;



	function __construct($uploadFolder)
	{
		$this->setUploadFolder($uploadFolder);
		$this->maxSize = $this->getMaxSize();


	}

	function getMaxSize($string = false, $maxSizeCustom = null)
	{
		$config	= JComponentHelper::getParams( 'com_jhacks' );

		$maxSize = (int)$config->get('upload_maxsize') * 1024 * 1024;
		if (!$maxSize)
			$maxSize = JHACKS_UPLOAD_DEFAULT_MAX_SIZE;

		if ($maxSizeCustom)
			$maxSize = min($maxSize, $maxSizeCustom);

		if ($string)
			$maxSize = JText::sprintf("JHACKS_UPLOAD_MAX_B", self::bytesToString($maxSize));

		return $maxSize;

	}

	function setUploadFolder($uploadFolder)
	{
		$uploadFolder = $this->parsePath($uploadFolder);
		$app = JFactory::getApplication();


		//Clean upload path
		$uploadFolder = JPath::clean(html_entity_decode($uploadFolder . DS));
		$uploadPath = JPath::clean($uploadFolder);




		//Check if upload directory exists
		if(!is_dir($uploadPath))
			JFolder::create($uploadPath);

		if (!is_dir($uploadPath))
			return false;

		$blankContent = '<html><body bgcolor="#FFFFFF"></body></html>';
		if (!self::exists($uploadPath.'index.html'))
			self::write($uploadPath.'index.html', $blankContent);


		//Protect against execution and set writable
		@chmod($uploadPath, JHACKS_UPLOAD_CHMOD_FOLDER);
		if(!is_writable($uploadPath))
		{
			$app->enqueueMessage(JText::sprintf( "JHACKS_UPLOAD_PLEASE_MAKE_SURE_THE_FOLDER_IS_WRITABLE",$uploadPath), 'notice');
			return false;
		}

		$this->uploadFolder = $uploadFolder;



	}

	function setAllowed($allowedTypes)
	{
		$this->allowedTypes = $allowedTypes;
	}

	function getAllowedExtensions()
	{
		return implode(",", $this->allowedTypes);
	}

	function getAllowedMimes()
	{
		return implode(" - ", array_keys($this->allowedTypes));
	}

	function uploadFile($uploadFile, $options = array())
	{
		$this->options = $options;

		if (isset($this->options["maxSize"]))  //Overwrite maxSize
			$this->maxSize = $this->options["maxSize"];


		$uploadFolder = $this->uploadFolder;


		$app = JFactory::getApplication();


		//Check file name
		if(empty($uploadFile['name'])){
			$app->enqueueMessage(JText::_("JHACKS_UPLOAD_PLEASE_BROWSE_A_FILE"),'notice');
			return false;
		}



		$file = null;
		$file->filename = $uploadFile['name'];
		$file->tmp = $uploadFile['tmp_name'];
		$file->size = $uploadFile['size'];



		$file->extension = $this->fileExtension($file->filename);
		$file->base = $this->fileBase($file->filename);


		$this->file = $file;




//CHECK SIZE
		$maxSize = self::getMaxUpload();
		if ($this->file->size > $maxSize)
		{
			$app->enqueueMessage(JText::sprintf( "JHACKS_UPLOAD_TOO_BIG_FILE_BYTES_MAX_ALLOWED_SIZE_BYTES",
												self::bytesToString($this->file->size),
												self::bytesToString($maxSize)), 'error');
			return false;
		}



//CORRECT FILENAME
		$this->renameFile();


//CHECK FILE PRESENCE
		if (!$this->checkFilePresence())  //And rename if allowed
		{
			$app->enqueueMessage(JText::sprintf( "JHACKS_UPLOAD_THIS_FILE_ALREADY_EXIST",$file->filename), 'notice');
			return false;
		}


//PROCESS UPLOAD
		if ( !$this->process())
		{
			if ($app->isSite())
				$msg = JText::sprintf( "JHACKS_UPLOAD_COULD_NOT_UPLOAD_THE_FILE", $file->filename);	// Don't show the complete directory in front-end
			else if ($app->isAdmin())
				$msg = JText::sprintf( "JHACKS_UPLOAD_COULD_NOT_UPLOAD_THE_FILE_TO",$file->tmp,$this->uploadFolder . $file->filename);

			$app->enqueueMessage($msg, 'error');
			return false;
		}


		return $file;
	}

	function process()
	{
		$fileDest = $this->uploadFolder . $this->file->filename;
		if ( !move_uploaded_file($this->file->tmp, $fileDest))
			if(!JFile::upload($this->file->tmp, $fileDest))
				return false;

		//Protect file against execution
		@chmod($fileDest, JHACKS_UPLOAD_CHMOD_FILE);

		return true;
	}

	function checkExtension($fileExt)
	{
		$valid = false;

		foreach($this->allowedTypes as $mime => $ext)
			if (in_array($fileExt, explode(",", $ext)))
				$valid = true;

		return $valid;
	}

	function extensionFromMime()
	{
		foreach($this->allowedTypes as $mime => $ext)
			if ($mime == $this->file->mime)
			{
				$exts = explode(",", $ext);
				return $exts[0];
			}

	}

	function checkMime($fileMime)
	{
		$valid = false;
		if (isset($this->allowedTypes) && count($this->allowedTypes))
		foreach($this->allowedTypes as $mime => $ext)
		{
			$mime = preg_replace("#\/#", "\\\/", $mime);
			if (preg_match("/" . $mime . "/", $fileMime))
				$valid = true;
		}
		return $valid;
	}

	function getMaxUpload()
	{

		$max = $this->maxSize;


		//PHP.INI (upload_max_filesize)
		$iniMaxUpload = self::bytes(ini_get('upload_max_filesize'));
		if ((int)$iniMaxUpload && ($iniMaxUpload < $max))
			$max = $iniMaxUpload;



		//PHP.INI (post_max_size)
		$iniMaxPost = self::bytes(ini_get('post_max_size'));
		if ((int)$iniMaxPost && ($iniMaxPost < $max))
			$max = $iniMaxPost;

		return $max;

	}


	function checkFilePresence()
	{

		if ($this->fileExists())
		{
			switch($this->options["overwrite"])
			{
				case 'no':		// Error file already present
					return false;
					break;

				case 'yes':
					return true; //Override
					break;

				default:
				case 'suffix':
								// Add a file suffix
					$this->renameIfExists();
					break;
			}
		}

		return true;
	}

	/*
	 * Rewrite the file name before upload
	 * PATTERNS :
	 * 	{EXT}				: Original extension
	 * 	{MIMEXT} 			: Corrected extension from Mime-header
	 *	{BASE}				: Original file name without extension
	 *	{ALIAS}				: Safe aliased original file name
	 *	{RAND}				: Randomized value
	 *	{DATE(%Y-%m-%d)} 	: formated date
	 *
	 *	Modifiers
	 *	{[PATTERN]#6} 		: Limit to 6 chars
	 */
	function renameFile()
	{
		$file = $this->file;
		if ($this->options["rename"])
			$pattern = $this->options["rename"];
		else
			$pattern = "{ALIAS}.{MIMEXT}";

		if (isset($this->options['id']))
		{
			//Original extension
			$this->parsePattern($pattern, "ID", $this->options['id']);
		}

		//Original extension
		$this->parsePattern($pattern, "EXT", $file->extension);

		//Corrected extension from Mime-header
		$this->parsePattern($pattern, "MIMEXT", $this->extensionFromMime());

		//Original file name without extension
		$this->parsePattern($pattern, "BASE", $file->base);


		//Safe aliased original file name
		$this->parsePattern($pattern, "ALIAS", $this->alias($file->base, 'lower'));


		//Randomized value
		$length = $this->patternLength("RAND", $pattern);
		$this->parsePattern($pattern, "RAND", $this->randomAlias($length));

		//formated date
		$format = $this->patternParam("DATE", $pattern);
		if (!$format)
			$format = "%Y-%m-%d";
		$this->parsePattern($pattern, "DATE", JFactory::getDate()->toFormat($format));


		//remove spaces
		$pattern = preg_replace("/\s+/", "", $pattern);

		//remove backdir
		$pattern = preg_replace("/\.\./", "", $pattern);

		//Non empty string
		if (trim($pattern) == "")
			$pattern = $this->randomAlias(8);

		$file->filename = $pattern;
		$file->base = $this->fileBase($file->filename);
		$file->extension = $this->fileExtension($file->filename);


		$this->file = $file;

	}

	protected function parsePattern(&$pattern, $name, $value)
	{
		$name = strtoupper($name);

		if (preg_match("/{" . $name . "(\(.+\))?(\#?[0-9]+)?}/", $pattern))
		{
			//Trim to length
			if (preg_match("/{" . $name . "(\(.+\))?\#?[0-9]+}/", $pattern))
			{
				$length = $this->patternLength($name, $pattern);

				$value = substr($value, 0, $length);
			}

			$pattern = preg_replace("/{" . $name . "(\(.+\))?(\#?[0-9]+)?}/", $value, $pattern);

		}
	}

	protected function patternLength($name, $pattern)
	{
		$name = strtoupper($name);

		if (!preg_match("/{" . $name . "\#[0-9]+}/", $pattern))
			return;

		$length = preg_replace("/^(.+)?{" . $name . "(\(.+\))?\#?([0-9]+)(}(.+)?)$/", "$3", $pattern);

		return $length;
	}

	protected function patternParam($name, $pattern)
	{
		$name = strtoupper($name);

		if (!preg_match("/{" . $name . "\(.+\)\#?([0-9]+)}/", $pattern))
			return null;

		$param = preg_replace("/^(.+)?{" . $name . "\((.+)?\)\#?([0-9]+)(}(.+)?)$/", "$2", $pattern);

		return $param;
	}

	function fileExists($suffix = null)
	{
		$s = (isset($suffix)?"-" . $suffix:"");

		return JFile::exists($this->uploadFolder .DS. $this->file->base . $s . '.' . $this->file->extension);

	}

	function renameIfExists()
	{
		$file = $this->file;

		if ($this->fileExists())
		{
			$suffix = 1;
			while($this->fileExists($suffix))
				$suffix++;

			$file->base = $file->base . "-" . $suffix;
			$file->filename = $file->base . "." . $file->extension;

		}

	}


	function randomAlias($length)
	{
		$lenChars = strlen(JHACKS_UPLOAD_RANDOM_CHARS);
		$random = "";

		if ((int)$length == 0)
			$length = 8;

		for($i = 0 ; $i < $length ; $i++)
		{
			$pos = rand(0, $lenChars);
			$random .= substr(JHACKS_UPLOAD_RANDOM_CHARS, $pos, 1);
		}

		return $random;
	}

	function alias($str, $toCase = 'lower')
	{

		//ACCENTS
		$accents = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
		$replacements = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
		$str = str_replace($accents, $replacements, $str);

		//SPACES
		$str = preg_replace("/\s+/", "-", $str);

		switch($toCase)
		{
			case 'lower':
				$str = strtolower($str);
				break;

			case 'upper':
				$str = strtoupper($str);
				break;

			case 'ucfirst':
				$str = ucfirst($str);
				break;

			case 'ucwords':
				$str = ucwords($str);
				break;

			default:
				break;

		}


		$str = JFile::makeSafe($str);

		return $str;


	}



}