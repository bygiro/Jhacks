<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <     JDom Class - Cook Self Service library    |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.0.0
* @package		Cook Self Service
* @subpackage	JDom
* @license		GNU General Public License
* @author		100% Vitamin - Jocelyn HUARD
*
*	-> You can reuse this framework for all purposes. Keep this signature. <-
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


class JDomHtmlGridBool extends JDomHtmlGrid
{
	var $level = 3;			//Namespace position
	var $last = true;		//This class is last call

	var $togglable;
	var $taskYes;
	var $taskNo;
	var $toggleLabel;
	var $commandAcl;


	private $text;
	private $images;
	private $task;

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@num		: Num position in list
	 *
	 *	@togglable	: if you want this bool execute a task on click
	 *	@commandAcl	: ACL rights to toggle
	 *	@taskYes	: task to execute when current is true
	 *	@taskNo		: task to execute when current is true
	 *	@toggleLabel: label to show in image title if togglable
	 *
	 */
	function __construct($args)
	{

		parent::__construct($args);

		$this->arg('togglable'			, 6, $args, false);
		$this->arg('commandAcl'			, 7, $args);
		$this->arg('taskYes'			, 8, $args, 'toggle_' . $this->dataKey);
		$this->arg('taskNo'				, 9, $args, $this->taskYes);
		$this->arg('toggleLabel'		, 10, $args, "Toggle");


		$imageYES = "tick.png";
		$imageUndefined = "publish_y.png"; 


		if (version_compare(JVERSION, '1.6', '<'))
		{
			$strYES = "YES";
			$strNO = "NO";
			$strUndefined = "UNDEFINED"; // hack

			$imageNO = "publish_x.png";
		}
		else
		{
			$strYES = "JYES";
			$strNO = "JNO";
			$strUndefined = "JUNDEFINED"; 

			$imageNO = "publish_r.png";

		}

		if ($this->dataValue === null OR $this->dataValue == 2) 
		{
			$text = $strUndefined;
			$image = $imageUndefined;
			$task = $this->taskNo;
		}
		else if ($this->dataValue)
		{
			$text = $strYES;
			$image = $imageYES;
			$task = $this->taskYes;
		}
		else
		{
			$text = $strNO;
			$image = $imageNO;
			$task = $this->taskNo;
		}

		$imagesFolder = JURI::base() . $this->pathToUrl($this->systemImagesDir(), true);

		$this->task = $task;
		$this->image = $imagesFolder . '/' . $image;
		$this->text = $this->JText($text);

	}

	function build()
	{

		$togglable = $this->togglable;


		if ($this->commandAcl)
			$togglable = $this->access($this->commandAcl);


		$html = '';

		$title = $this->text;
		if ($togglable)
		{
			$html .= "<a style='cursor:pointer;' onclick=\"<%COMMAND%>\">" .LN;
			$title = $this->JText($this->toggleLabel);

		}

        $html .= "<img src='<%IMAGE_SOURCE%>' border='0' alt='<%ALT%>'"
			.	" title='" . htmlspecialchars($title, ENT_COMPAT, 'UTF-8') . "' />" .LN;

		if ($togglable)
			$html .= "</a>" .LN;




		return $html;
	}

	function parseVars($vars = array())
	{
		return array_merge(array(
				'IMAGE_SOURCE' 	=> $this->image,
				'COMMAND' 		=> $this->jsCommand(),
				'TITLE' 		=> "Toggle value",
				'ALT' 			=> htmlspecialchars($this->text, ENT_COMPAT, 'UTF-8'),

				), $vars);
	}

	function jsCommand()
	{

		$cmd = 	"javascript:listItemTask('cb" . (int)$this->num . "', '" . $this->task . "')";

		return $cmd;
	}


}