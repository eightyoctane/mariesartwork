<?php

include_once($_SERVER['DOCUMENT_ROOT']."/homedata.php");
include_once($rootlocation."logFileClass.php");


abstract class PresenterClass
{
    abstract protected function renderPage();
}

class GeneralPageBrowser extends PresenterClass
{
	protected $htmlpage;
	protected $statusbar;
	protected $menu;
	protected $body;
	protected $shiddenvalue;
	protected $regionbody;
	protected $footer;
	protected $errorstatusbar;
	protected $nextpage;
	protected $lf;
	protected $formaction;
	
	//get region and header content
	//$htmlpage is a file name	
	public function __construct($htmlpage) {
		$this->lf = new logFile();
		$this->htmlpage = $htmlpage;
		//$this->lf->logMessage("GeneralPage Constructor: ".$htmlpage."\n");		
		return;
	}
	
	protected function bufferEval($filename) {
		ob_start();
		include($filename);
		$buffer .= ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
	
	public function setNextPage($nextpage) {
		$this->nextpage = $nextpage;
	}
	
	public function clearRegionBodyContent() {
		$this->regionbody = '';
	}
	
	public function addRegionBodyContent($filename) {
		$this->regionbody = $this->bufferEval($filename);	}
	
	public function addHeader($filename) {
		//$this->lf->logMessage("addHeader: adding header\n");
		$this->header = $this->bufferEval($filename);
		return;
	}
	
	public function addFooter($filename) {
		$this->footer = $this->bufferEval($filename);
		return;
	}

	public function addMenu($filename) {
		$this->menu = $this->bufferEval($filename);
		return;
	}
	
	public function addBodyContent($filename) {
		$this->body .= $this->bufferEval($filename);
	}
	
	public function clearBodyContent() {
		$this->body = '';
	}
	
	public function addRtBodyContent($filename) {
		$this->rtbody = $this->bufferEval($filename);
	}

	function addHiddenValue($id, $value) {
		$this->shiddenval .="<input type=hidden name=$id value=$value>";
	}	

	public function addBodyText($text) {
		$this->body .= $text;
		
	}
	
	public function setStatusBarMessage($msg) {
		$this->statusbar = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'.$msg.'</td>';
		return;
	}
/*
	public function setErrorStatusBarMessage() {
		$this->statusbar = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'.$_SESSION['error'].'</td>';
	}
*/		
	public function clearStatusBarMessage() {
		$this->statusbar = '&nbsp';
	}


	public function renderPage()  {	
		$text = $this->bufferEval($this->htmlpage);
				
		//$this->lf->logMessage($text);
		echo($text);	
		//echo(include($this->htmlpage));
		return;
	}

}

class SessionPageBrowser extends GeneralPageBrowser
{
/* 

	This is an "in session" page. Are we really
	in a session? We can tell via the $_SESSION['UserName'].
	
	 The html component of this page should contain
	a. a region for a menu defined as the place for $this->menu
	b. a region which allows placement of the login status message
	c. a region which allows placement of other messages (could be the same as
		the login status message)
	d. hidden error field
	e. hidden nextpage field containing the next place to go if 
		already logged in
	f. a body definition $this->body
	
*/


	private $username;  //a string


	private $statsmsg;
	private $head;

	
	public function __construct($htmlpage) {
		parent::__construct($htmlpage);

		
		$this->username = $_SESSION['UserName'];
		$_SESSION['homepage'] = $homelocationpart.$homelocationsub."index.php";	
		//$this->lf->logMessage("sessionPage Constructor: Attempting to start session.\n");	
		return;
	}
	
	public function setFormAction($action) {
		$this->formaction = $action;
	}
	
	public function navigate() {
		header( 'Location: '.$_SESSION['nextpage']);
	}
	
	public function loginError() {
		if(isset($_GET['error'])) {
			return 1;
		} else return 0;
	}
	
	public function addHeader($filename) {
		$this->setLoginDetailsMessage();
		//$this->lf->logMessage("session addHeader: setting login details message\n");
		parent::addHeader($filename);
	}
	
	public function addHeadContent($filename) {
		$this->head .= file_get_contents($filename);
	}
	
	public function clearHeadContent() {
		$this->head = '';
	}

/*
	public function addHtmlText($htmlpage) {
		ob_start();
		include($htmlpage);
		$buffer = ob_get_contents();
		ob_end_clean();
		$this->body .= $buffer;
	}
	
*/	
	public function getUserType() {
		return $_SESSION['u_type'];
	}

	public function getUserPriv() {
		return $_SESSION['u_priv'];
	}
	
	public function loggedIn() {
		if(isset($_SESSION['UserName'])) {
				return 1;
		} else {
				return 0;
		} 
	}
	
	public function addStatsMessage($smsg) {
		$this->statsmsg .=$smsg;
		$this->stats = '<font size="1" color="#000000"><b><i>'.$this->statsmsg.'</i></b></font>';	
		//$this->stats = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'.$this->statsmsg.'</td>';	
	}
	
	public function setLoginDetailsMessage() {
		//$this->loginstatus = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'.$msg.'</td>';
		$this->loginstatus = '<font size="1" color="#FF0000"><b><i>'.$_SESSION['loginstatemessage'].'</i></b></font>';

		return;	
	}

	//Notice that this only sets the page, has nothing to do with a session not greatly useful but may help detect errors
	public function setLoginStatusMessage() {
		if(isset($_SESSION['UserName'])) {
				//$this->statusbar = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'."Logged In".'</td>';
				$this->statusbar = '<font size="1" color="#FF0000"><b><i>'."Logged In".'</i></b></font>';
		} else {
				//$this->statusbar = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'."Logged Out".'</td>';
				$this->statusbar = '<font size="1" color="#FF0000"><b><i>'."Logged Out".'</i></b></font>';
		} 
		return;
	}
	
	public function setStatusBarMessage($msg) {
		//$this->statusbar = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'.$msg.'</td>';
		$this->statusbar = '<font size="1" color="#FF0000"><b><i>'.$msg.'</i></b></font>';
		return;
	}
	
	public function setErrorStatus($msg) {
		$_SESSION['ErrorMessage'] = $msg;
	}
	
	public function clearErrorStatus() {
		$_SESSION['ErrorMessage'] = NULL;
	}

	private function setErrorStatusBarMessage() {
		//$this->errorstatusbar = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'.$_Session['ErrorMessage'].'</td>';
		$this->errorstatusbar = '<font size="1" color="#FF0000"><b><i>'.$_SESSION['ErrorMessage'].'</i></b></font>';
	}
		
	public function clearErrorStatusBarMessage() {
			$this->errorstatusbar = '';
	}
		
	public function clearStatusBarMessage() {
			$this->statusbar = '';			
	}
	
	public function renderPage() {
		$this->setErrorStatusBarMessage();
		parent::renderPage();
		$this->clearErrorStatus();
	}
	
	
}

class SessionPageBrowserDynamic extends SessionPageBrowser
{
	private $regioncontent;
	private $templatecontent;
	private $totalcontent;
	
	public function addFooter($htmlpage) {
		ob_start();
		include($htmlpage);
		$buffer = ob_get_contents();
		ob_end_clean();
		$this->footer .= $buffer;
	}


	function addRegionContent($filename) {
		$this->regioncontent .= file_get_contents($filename);
	}
	
	public function renderRegionContent() {
		ob_start();
		include($this->htmlpage);
		$this->totalcontent = ob_get_contents();
		ob_end_clean();
	}
}