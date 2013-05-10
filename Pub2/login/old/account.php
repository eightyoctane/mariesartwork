<?php 
	session_start();
	
	include_once("sPageClass.php");
	include_once("../logFileClass.php");
	
	
	$lf = new logFile();
	$sp = new sPage("../bma_RegionAndHeader.htm");
	
	
	if($sp->loggedIn()) {
	
		// Now make sure we are a por and not just a visitor
		if($sp->getUserType() != 2) {
			$lf->logMessage("por page not available, user type not 2");
			header('Location: '.$_SESSION['prevpage']);
			exit;
		}
		
		$sp->setLoginDetailsMessage($_SESSION['loginstatemessage']);
		$sp->setErrorStatusBarMessage();
		$_SESSION['prevpage']="account.php";
		$sp->addMenu("porMenu.html");
		$sp->addBodyContent("htmlfiles/accountbody.htm");
		$sp->renderSessionPage();
				
	} else {
		$sp->setLoginStatusMessage();
		$lf->logMessage("por page not available, session user name not set");
		session_unset();
		session_destroy();
		header( 'Location: http://www.bluemountainairpark.com/testpage7.php');
	}
