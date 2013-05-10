<?php
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
class sPage {
	var $htmlpage;  //a file name
	var $statusbar; //a string
	var $menu;      //a string
	var $body;      //a string
	var $username;  //a string
	var $lf;        //object pointer
	var $errorstatusbar;
	var $shiddenvalue;
	var $statsmsg;
	var $head;
	var $regioncontent;
	var $footer;
	var $templatecontent;
	var $totalcontent;
	
	function sPage($htmlpage) {
		include_once($_SERVER['DOCUMENT_ROOT'].'/logFileClass.php');
		$this->lf = new logFile();
		$this->username = $_SESSION['UserName'];

		$this->htmlpage = $htmlpage;
				
		return;
	}
	
	function navigate() {
		header( 'Location: '.$_SESSION['nextpage']);

	}
	
	function loginError() {
		if(isset($_GET['error'])) {
			return 1;
		} else return 0;
	}
	
	function addMenu($filename) {
		$this->menu = file_get_contents($filename);
		return;
	}
	
	function addHeadContent($filename) {
		$this->head .= file_get_contents($filename);
	}
	
	function clearHeadContent() {
		$this->head = '';
	}

	
	function clearBodyContent() {
		$this->body = '';
	}
	
	function addBodyContent($filename) {
		$this->body .= file_get_contents($filename);
	}

	function clearRegionContent() {
		$this->regionbody = '';
	}
	
	function addRegionContent($filename) {
		$this->regioncontent .= file_get_contents($filename);
	}
	
	
	function addHiddenValue($id, $value) {
		$this->shiddenval .="<input type=hidden name=$id value=$value>";
	}

	
	function addBodyText($text) {
		$this->body .= $text;
	}
	
	function renderRegionContent() {
		ob_start();
		include($this->htmlpage);
		$this->totalcontent = ob_get_contents();
		ob_end_clean();
	}
	
		function addFooter($htmlpage) {
		ob_start();
		include($htmlpage);
		$buffer = ob_get_contents();
		ob_end_clean();
		$this->footer .= $buffer;
	}


	
	function getUserType() {
		return $_SESSION['u_type'];
	}

	function getUserPriv() {
		return $_SESSION['u_priv'];
	}
	
	function loggedIn() {
		if(isset($_SESSION['UserName'])) {
				return 1;
		} else {
				return 0;
		} 
	}
	
	function addStatsMessage($smsg) {
		$this->statsmsg .=$smsg;
		$this->stats = '<font size="1" color="#000000"><b><i>'.$this->statsmsg.'</i></b></font>';	
		//$this->stats = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'.$this->statsmsg.'</td>';	
	}
	
	function setLoginDetailsMessage($msg) {
		//$this->loginstatus = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'.$msg.'</td>';
		$this->loginstatus = '<font size="1" color="#FF0000"><b><i>'.$msg.'</i></b></font>';

		return;	
	}

	//Notice that this only sets the page, has nothing to do with a session not greatly useful but may help detect errors
	function setLoginStatusMessage() {
		if(isset($_SESSION['UserName'])) {
				//$this->statusbar = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'."Logged In".'</td>';
				$this->statusbar = '<font size="1" color="#FF0000"><b><i>'."Logged In".'</i></b></font>';
		} else {
				//$this->statusbar = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'."Logged Out".'</td>';
				$this->statusbar = '<font size="1" color="#FF0000"><b><i>'."Logged Out".'</i></b></font>';
		} 
		return;
	}
	
		function setStatusBarMessage($msg) {
		//$this->statusbar = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'.$msg.'</td>';
		$this->statusbar = '<font size="1" color="#FF0000"><b><i>'.$msg.'</i></b></font>';
		return;
	}

		function setErrorStatusBarMessage($msg) {
		//$this->errorstatusbar = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'.$msg.'</td>';
		$this->errorstatusbar = '<font size="1" color="#FF0000"><b><i>'.$msg.'</i></b></font>';
		}
		
		function clearErrorStatusBarMessage() {
			$this->errorstatusbar = '';
		}
		
		function clearStatusBarMessage() {
			$this->statusbar = '';
			
		}

	
	function renderSessionPage()  {
		//include_once($this->htmlpage);
		//echo "filename is ".$this->htmlpage;
		/*
		Remember we are rendering our basic page which contains
		regions and variables which each have been populated with
		content text
		*/
		echo(eval('?>' . $this->totalcontent . '<?php '));
		
		return;
	}

}
?>