<?php
	include_once($_SERVER['DOCUMENT_ROOT'].'/gPageClass.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/login/sPageClass.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/logFileClass.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/login/sessionSetup.php');

	
	session_start();

	$lf = new logFile();
	
	// this because we could come here from projects page and be logged in

	$lf->logMessage("We're in eventsPage\n");
	
	if(isset($_SESSION['UserName'])) {
	
		$lf->logMessage("Logged in and going to events page\n");
		
		$sp = new sPage($_SERVER['DOCUMENT_ROOT'].'/bma_RegionAndHeader.htm');
						
		$usertype = $sp->getUserType();
		
		// if the usertype is 1 we want the restricted menu, otherwise we want the por menu
		// use validateSession() to catch any weird stuff (and stuff we could run into as this develops)
		if($usertype == 1) {
			$lf->logMessage("in eventsPage and displaying bmarvMenu\n");
			sessionSetup($sp,$lf,1,1,"bmarv.php");			
			$sp->addMenu($_SERVER['DOCUMENT_ROOT'].'/evbmarvMenu.html');
			$_SESSION['prevpage'] = $_SERVER['DOCUMENT_ROOT'].'/login/bmarv.php';

		} else if($usertype == 2) {
			$lf->logMessage("in eventsPage and displaying porMenu\n");
			
			sessionSetup($sp,$lf,2,1,"por.php");
			$sp->addMenu($_SERVER['DOCUMENT_ROOT'].'/evporMenu.html');
			$_SESSION['prevpage'] = $_SERVER['DOCUMENT_ROOT'].'/login/por.php';
		
		} else {
					$lf->logMessage("in eventsPage, logged in, and not expecting unknown usertype\n");
					session_unset();
					session_destroy();
					header( 'Location: '.$_SESSION['homepage']);
		}
		
		$sp->addBodyContent('eventsbody.htm');
		$sp->renderSessionPage();	
	
	} else {
			
		$lf->logMessage("in eventsPage and it's thinking we're logged out\n");
		$gp = new gPage("bma_RegionAndHeader.htm");
		
		// Add a menu appropriate for this page and state
		$gp->addMenu("homepageMenu.html");
		
		$gp->addBodyContent("eventsbody.htm");
		$gp->addFooter("generalFooter.htm");
		
		$gp->renderGeneralPage();
	}
?>