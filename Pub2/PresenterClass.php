<?php

abstract class PresenterClass
{
    abstract protected function renderPage();
}

class GeneralPageBrowser extends PresenterClass
{
	protected $htmlpage;
	protected $head;
	protected $statusbar;
	protected $menu;
	protected $pagetitle;
	protected $body;
	protected $bodyleft;
	protected $shiddenvalue;
	protected $regionbody;
	protected $footer;
	protected $errorstatusbar;
	protected $nextpage;
	protected $lf;
	protected $formaction;
	
	protected $system;
	protected $dbuser;
	protected $dbpwd;
	protected $database;
	protected $website;
	protected $rootlocation;
	protected $homelocationurl;
	protected $stylescript;
	protected $sitetitle;

	//get region and header content
	//$htmlpage is a file name	
	public function __construct($htmlpage) {
		$this->lf = new logFile();
		$this->htmlpage = $htmlpage;
		//$this->lf->logMessage("GeneralPage Constructor: ".$htmlpage."\n");
		
		//$path = $_SERVER['DOCUMENT_ROOT']."/homedatapub2.php";
		//$this->lf->logMessage("generalPageBrowser: constructor Starting path: ".$path."\n");	
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		require_once($rootlocation."login/mysqlClass.php");
		include($rootlocation."dbdata.php");
		$this->system = $system;
		$this->dbuser = $dbuser;
		$this->dbpwd = $dbpwd;
		$this->database = $database;
		$this->website = $website;
		$this->rootlocation = $rootlocation;
		$this->homelocationurl = $homelocationurl;

		//$this->lf->logMessage("generalPageBrowser: Starting rootlocation: ".$this->rootlocation."\n");
		
		$this->addHeadContent($homemenuheadfull);
		$this->addHeadTextContent("<title>$pagetitle</title>");
		if(!$this->validateInSession()) $this->addMenu($homemenufull);
		$this->setStyleSheet($sitewidecssfull);
		$this->addSiteTitle($sitetitlefull);
		$this->addFooter($footercontentfull);
		return;
	}
	
	public function setNoMenu() {
		$this->clearMenu();
	}
	
	protected function setStyleSheet($sheetfile) {
		$this->stylescript = '<link href="'.$sheetfile.'" rel="stylesheet" type="text/css" />';
	}

	protected function bufferEval($filename) {
		ob_start();
		include($filename);
		$buffer .= ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
	
	public function validateInSession() {
		session_start();
		if(!$_SESSION['UserName']) {
			//$this->lf->logMessage("validateInSession: not in session\n");
			return 0;
		} else return 1;
	}

	public function setNextPage($nextpage) {
		$this->nextpage = $nextpage;
	}
	
	public function clearRegionBodyContent() {
		$this->regionbody = '';
	}
	
	public function addRegionBodyContent($filename) {
		$this->regionbody = $this->bufferEval($filename);	
	}
		
	public function addHeadContent($filename) {
		$this->head .= file_get_contents($filename);
	}
		
	public function addHeader($filename) {
		//$this->lf->logMessage("addHeader: adding header\n");
		$this->header .= $this->bufferEval($filename);
		return;
	}
	
		
	public function addHeadTextContent($text) {
		$this->head .= $text;
	}
		
	public function clearHeadContent() {
		$this->head = '';
	}
	
	public function addSiteTitle($filename) {
		$this->sitetitle = $this->bufferEval($filename);
		return;
	}
	
	public function addFooter($filename) {
		$this->footer = $this->bufferEval($filename);
		return;
	}

	public function addMenu($filename) {
		//$this->lf->logMessage("addMenu: adding contents of file: ".$filename."\n");
		$this->menu = $this->bufferEval($filename);
		return;
	}
	
	protected function clearMenu() {
		$this->menu = "";
	}
	
	public function addBodyContent($filename) {
		//$this->lf->logMessage("addBodyContent: adding contents of file: ".$filename."\n");
		$this->body .= $this->bufferEval($filename);
	}
	
	public function clearBodyContent() {
		$this->body = '';
	}
	
	public function addRtBodyContent($filename) {
		$this->rtbody .= $this->bufferEval($filename);
	}
	
	public function addBodyLeftContent($filename) {
		$this->bodyleft .= $this->bufferEval($filename);
	}
	
		public function addBodyLeftText($text) {
		$this->bodyleft .= $text;	
	}

	
	public function clearBodyLeftContent() {
		$this->bodyleft = '';
	}
	
	public function addPageTitleContent($filename) {
		$this->pagetitle .=$this->bufferEval($filename);
	}
	
	public function clearPageTitleContent($filename) {
		$this->pagetitle = '';
	}

	function addHiddenValue($id, $value) {
		$this->shiddenval .="<input type=hidden id=$id name=$id value=$value>";
	}	

	public function addBodyText($text) {
		$this->body .= $text;
		
	}

	public function setFormAction($action) {
		$this->formaction = $action;
	}
	
	public function loginError() {
		if(isset($_GET['error'])) {
			return 1;
		} else return 0;
	}
	

	
	public function loggedIn() {
		if(isset($_SESSION['UserName'])) {
				return 1;
		} else {
				return 0;
		} 
	}
	
	public function setLoginStatusMessage() {
	//Notice that this only sets the page, has nothing to do with a session not greatly useful but may help detect errors

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
		$this->statusbar = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'.$msg.'</td>';
		return;
	}
	
	
	public function setErrorStatusBarMessage($msg) {
		//$this->errorstatusbar = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'.$_Session['ErrorMessage'].'</td>';
		$this->errorstatusbar = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'.$msg.'</td>';
		
		//$this->errorstatusbar = '<font size="1" color="#FFFFFF"><b><i>'.$msg.'</i></b></font>';
		//$this->lf->logMessage(" errorstatusbar: ".$this->errorstatusbar."\n");
	}

/*
	public function setCookieErrorStatusBarMessage() {
		isset($_COOKIE['errorstatus']) {
			$this->errorstatusbar = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'.$_COOKIE['errorstatus'].'</td>';
			
			setcookie('errorstatus', '    ', false, '/account', $this->website);
		}	
	}
*/		
	public function clearStatusBarMessage() {
		$this->statusbar = '&nbsp';
	}


	public function alignText($text, $cmd) {
		// left, right, center
		$text = '<div align="'.$cmd.'">'.$text.'</div>';
		return($text);
	}


	public function renderPage()  {
		//$this->setCookieErrorStatusBarMessage();
		
		//echo "<p>*******renderPage file: ".$this->htmlpage."<p>";
	
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


	protected $name;
	protected $username;  //a string
	private $statsmsg;

	protected $u_id;
	protected $mysqldb;
	
	public function __construct($htmlpage,$minpriv,$ppage) {
		parent::__construct($htmlpage);
		//session_start();

		//$path = $_SERVER['DOCUMENT_ROOT']."/homedatapub2.php";
		//$this->lf->logMessage("sessionPageBrowser: constructor Starting path: ".$path."\n");	
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");


		$this->name = $_SESSION['Name'];
		$this->username = $_SESSION['UserName'];
		$this->u_id = $_SESSION['u_id'];
		$_SESSION['homepage'] = $this->homelocationurl;	
		//$this->lf->logMessage("sessionPage Constructor: Attempting to start session.\n");
		
		$this->mysqldb = new mysql($this->system,$this->dbuser,$this->dbpwd,$this->database);
		$this->mysqldb->connect();
		$this->mysqldb->select();

		$this->sessionSetup($minpriv, $ppage);
		
		$this->addMenu($homemenulistfull[$this->getUsertype()]);

	
		return;
	}
	
	protected function sessionSetup($minpriv, $prevpage) {
	
	//$this->lf->logMessage("PresenterClass:sessionSetup.php: Started\n");
	
	if(isset($_SESSION['UserName'])) {
		
		//$this->lf->logMessage("sessionSetup.php: Already in session. usertype requested: ".$this->getUserType()." minpriv: ".$minpriv."\n");	
		
		if($_SESSION['u_priv'] < $minpriv) {

			$msg = "Access to this page requires higher privilege";
			
			//$this->lf->logMessage("sessionSetup.php: User:".$_SESSION['UserName']." ".$msg."\n");
		
			$this->setErrorStatus($msg);
			
			header( 'Location: '.$prevpage);
			exit;
		
		} else {

			//$this->lf->logMessage("sessionSetup.php: already logged in and user passed minimum privledge requirements for page requested\n");

			$this->setLoginDetailsMessage($_SESSION['loginstatemessage']);

			return;
		}
	
	} else {

		//$this->lf->logMessage("sessionSetup: Not yet in session must log in first.\n");

		session_unset();
		session_destroy();
		//echo "going to login";
		$loc = 'Location: '.HOMELOCATIONURL."login/login.php";
		//$this->lf->logMessage("sessionSetup: returning to: ".$loc."\n");
		header($loc);
		exit;
	}
}

	
	
	
	public function getFname() {
		$namecomponents = explode(" ",$this->name);
		return $namecomponents[0];
	}
	
	public function navigate() {
		header( 'Location: '.$_SESSION['nextpage']);
	}
	
	public function setPrevPage($prevpage) {
		$_SESSION['prevpage'] = $prevpage;
	}
	
	public function getPrevPage() {
		return $_SESSION['prevpage'];
	}
	
	public function addHeader($filename) {
		$this->setLoginDetailsMessage();
		//$this->lf->logMessage("session addHeader: setting login details message\n");
		parent::addHeader($filename);
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

	public function getUserID() {
		return ($_SESSION['u_id']);
	}
	
	public function getUserName() {
		return ($_SESSION['UserName']);
	}

	public function getName() {
		return ($_SESSION['Name']);
	}	
	
	
	public function getUserType() {
		return $_SESSION['u_type'];
	}

	public function getUserPriv() {
		return $_SESSION['u_priv'];
	}
	
	public function addStatsMessage($smsg) {
		$this->statsmsg .=$smsg;
		//$this->stats = '<font size="1" color="#FFFFFF"><b><i>'.$this->statsmsg.'</i></b></font>';	
		$this->stats = '<font size="1" color="#FF0000"><b><i>'.$this->statsmsg.'</i></b></font>';	

		//echo "<p>stats messages: ".$this->stats."</p>";

		//$this->stats = '<font size="1" color="#000000"><b><i>'.$this->statsmsg.'</i></b></font>';	
		//$this->stats = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'.$this->statsmsg.'</td>';	
	}
	
	public function displayStatsMessage() {
		return($this->stats);
	}
	
	public function setLoginDetailsMessage() {
		//$this->loginstatus = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'.$msg.'</td>';
		$this->loginstatus = '<font size="1" color="#FF0000"><b><i>'.$_SESSION['loginstatemessage'].'</i></b></font>';

		return;	
	}

	public function setStatusBarMessage($msg) {
		//$this->statusbar = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'.$msg.'</td>';
		$this->statusbar = '<font size="1" color="#FF0000"><b><i>'.$msg.'</i></b></font>';
		return;
	}
	
	public function setErrorStatus($msg) {
		$_SESSION['error'] = 1;
		$_SESSION['ErrorMessage'] = $msg;
	}
	
	public function clearErrorStatus() {
		//$this->lf->logMessage("clearErrorStatus: Clearing the session error message\n");
		$_SESSION['ErrorMessage'] = NULL;
	}
	
	private function getSessionErrorMsg() {
	/*
		if (isset($_SESSION['ErrorMessage'])) 
			//$this->lf->logMessage("getSessionErrorMsg: Error to be displayed: ".$_SESSION['ErrorMessage']."\n");
	*/
		return $_SESSION['ErrorMessage'];
	}
/*		
	public function clearErrorStatusBarMessage() {
			$this->errorstatusbar = '';
	}
*/		
	public function clearStatusBarMessage() {
			$this->statusbar = '';			
	}
	
	public function renderPage() {
		$this->setErrorStatusBarMessage($this->getSessionErrorMsg());
		parent::renderPage();
		$this->clearErrorStatus();
		exit;
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
