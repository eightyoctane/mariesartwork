<?php

// The htmlpage should have a body definition

class gPage {
	var $htmlpage;
	var $statusbar;
	var $menu;
	var $body;
	
	//get region and header content
	//$htmlpage is a file name	
	function gPage($htmlpage) {
		$this->htmlpage = $htmlpage;		
		return;
	}
	
	function addFooter($filename) {
		$this->footer = file_get_contents($filename);
		return;
	}

	function addMenu($filename) {
		$this->menu = file_get_contents($filename);
		return;
	}
	
	function addBodyContent($filename) {
		$this->body .= file_get_contents($filename);
	}
	
	function addBodyText($text) {
		$this->body .= $text;
	}
	
			function setStatusBarMessage($msg) {
		$this->statusbar = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'.$msg.'</td>';
		return;
	}

		function setErrorStatusBarMessage() {
		$this->statusbar = '<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold">'.$_SESSION['error'].'</td>';
		}
		
		function clearStatusBarMessage() {
		$this->statusbar = '&nbsp';
		}


	function renderGeneralPage()  {
		//include_once($this->htmlpage);
		echo(include($this->htmlpage));
		return;
	}
}
?>