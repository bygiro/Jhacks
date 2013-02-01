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


class JDomHtmlFly extends JDomHtml
{
	var $level = 2;				//Namespace position
	var $fallback = 'default';	//Used for default

	protected $dataKey;
	protected $dataObject;
	protected $dataValue;



	protected $preview;
	protected $handler; // Can be 'iframe'
	protected $href;
	protected $link_title;
	protected $target;

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


		$this->arg('dataKey'	, null, $args);
		$this->arg('dataObject'	, null, $args);
		$this->arg('dataValue'	, null, $args, (($item = $this->dataObject) && ($key = $this->dataKey))?$this->parseKeys($item, $key):null);



		$this->arg('preview'	, null, $args);
		$this->arg('handler'		, null, $args);
		$this->arg('href'		, null, $args);
		$this->arg('link_title'	, null, $args);
		$this->arg('target'		, null, $args);


	}

	function parseVars($vars = array())
	{
		return array_merge(array(
				'DOM_ID'		=> $this->domId,
				'STYLE'		=> $this->buildDomStyles(),
				'CLASS'			=> $this->buildDomClass(),		//With attrib name
				'CLASSES'		=> $this->getDomClass(),		// Only classes
				'SELECTORS'		=> $this->buildSelectors(),
				'VALUE'			=> htmlspecialchars($this->dataValue, ENT_COMPAT, 'UTF-8'),
				'JSON_REL' 		=> htmlspecialchars($this->jsonArgs(), ENT_COMPAT, 'UTF-8'),
				), $vars);
	}



}