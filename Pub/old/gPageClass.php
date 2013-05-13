<?php

// The htmlpage should have a body definition

class gPage {
	var $htmlpage;
	var $statusbar;
	var $menu;
	var $body;
	
	//get region and header content
	//$htmlpage is a file name	
	public function __construct($htmlpage) {
		$this->htmlpage = $htmlpage;		
		return;
	}
	
	public function addHeader($filename) {
		$this->header = file_get_contents($filename);
		return;
	}
	
	public function addFooter($filename) {
		$this->footer = file_get_contents($filename);
		return;
	}

	public function addMenu($filename) {
		$this->menu = file_get_contents($filename);
		return;
	}
	
	public function addBodyContent($filename) {
		$this->body .= file_get_contents($filename);
	}
	
	public function addBodyText($text) {
		$this->body .= $text;
	}
	
	public function setStatusBarMessage($msg) {
		$this->statusbar = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'.$msg.'</td>';
		return;
	}

	public function setErrorStatusBarMessage() {
		$this->statusbar = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'.$_SESSION['error'].'</td>';
	}
		
	public function clearStatusBarMessage() {
		$this->statusbar = '&nbsp';
	}


	public function renderGeneralPage()  {
		//include_once($this->htmlpage);
		echo(include($this->htmlpage));
		return;
	}
}
?>