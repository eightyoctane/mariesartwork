<?php
	include_once($_SERVER['DOCUMENT_ROOT'].'/gPageClass.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/login/sPageClass.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/logFileClass.php');
	
	$lf = new logFile();
	
	// this because we could come here from projects page and be logged in

	$lf->logMessage("We're in eventsPage\n");
	
	if(isset($_SESSION['UserName'])) {
		
		$lf->logMessage("Logged in and going to events page\n");		
		$usertype = $sp->getUserType();
		
		// if the usertype is 1 we want the restricted menu, otherwise we want the por menu
		// use validateSession() to catch any weird stuff (and stuff we could run into as this develops)
		if($usertype == 1) {
			$lf->logMessage("in eventsPage and displaying evbmarvMenu\n");
			$sp->validateSession(1,1,"bmarv.php");
			$sp->addMenu("bmarvMenu.html");
			$_SESSION['prevpage'] = 'login/bmarv.php';
		} else {
			$lf->logMessage("in eventsPage and displaying evporMenu\n");
			$sp->validateSession(2,1,"por.php");
			$sp->addMenu("porMenu.html");
			$_SESSION['prevpage'] = 'login/por.php';
		}
		
		$sp->addBodyContent('htmlfiles/eventsbody.htm');
		$sp->addFooter($_SERVER['DOCUMENT_ROOT']."/generalFooter.htm");
		$sp->renderSessionPage();	
	
	} else {
			
		$lf->logMessage("in eventsPage and it's thinking we're logged out\n");
		$gp = new gPage("bma_RegionAndHeader.htm");
		
		// Add a menu appropriate for this page and state
		$gp->addMenu("homepageMenu.html");
		
		$gp->addBodyContent("htmlfiles/eventsbody.htm");
		$gp->addFooter($_SERVER['DOCUMENT_ROOT']."/generalFooter.htm");
		
		$gp->renderGeneralPage();
	}
?>