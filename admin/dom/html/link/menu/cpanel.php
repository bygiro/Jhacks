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


class JDomHtmlLinkMenuCpanel extends JDomHtmlLinkMenu
{
	var $level = 4;				//Namespace position
	var $fallback = 'button';	//Used for default


	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 *	@preview	: Preview type
	 *	@href		: Link
	 *	@link_title	: Title on the link
	 *	@target		: Target of the link  ('download', '_blank', 'modal', ...)
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('img'		, null, $args);
		$this->arg('extra'		, null, $args);

		$extension = JRequest::getVar('option');
		$this->img = 'components/'. $extension .'/images/'. $this->img;		
	}

	function buildButtonLink()
	{
		if ($this->target == 'modal')
			$this->modalLink();

		$html = "<a<%ID%><%CLASS%><%TITLE%><%HREF%><%JS%><%TARGET%><%SELECTORS%> ". $this->extra .">"
			.	"<img src='". $this->img ."' alt='". $this->link_title ."'>"
			.	"<span>". $this->link_title ."</span>"
			.	"</a>";

		return $html;
	}	

}