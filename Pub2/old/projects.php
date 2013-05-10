<?php 
	session_start();
	

	/* 
	We may not be logged in when we get here
	
	This page for projects privilege 
	anyone who's logged in can use this page
	
	we can't assume a session in progress
	if we're not logged in make them log in first
	have to check $_SESSION['UserName'] directly
	this is because we can wind up here from an un logged in events page
	
	*/
	
	include_once("sPageClass.php");
	include_once("../logFileClass.php");
	
	
	$lf = new logFile();
	
	// this because we could come here from events page
	// make them go back and log in to get to the projects page
	
	if(!$_SESSION['UserName']) {
		$lf->logMessage("Trying to access projects page but not logged in\n");
		header('Location: http://www.bluemountainairpark.com/login/login.php');
	} else {
	
		/* Ok so now we know we're in a session and it's ok to proceed,
		but because of this special case which is a "visitor only" user type
		we have to do some special to insure that a consistent set of menu
		options appear. 	
		*/
		$sp = new sPage("../bma_RegionAndHeader.htm");
		
		$usertype = $sp->getUserType();
		
		// if the usertype is 1 we want the restricted menu, otherwise we want the por menu
		// use validateSession() to catch any weird stuff (and stuff we could run into as this develops)
		if($usertype == 1) {
			$sp->validateSession(1,1,"bmarv.php");
			$sp->addMenu("bmarvMenu.html");
			$_SESSION['prevpage'] = "bmarv.php";
		} else {
			$sp->validateSession(2,1,"por.php");
			$sp->addMenu("porMenu.html");
			$_SESSION['prevpage'] = "por.php";
		}
		
		$sp->addBodyContent("projectsBody.htm");
		$sp->renderSessionPage();	
	}
?>
