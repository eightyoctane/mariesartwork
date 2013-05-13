<?php
/*
We wound up here because "someone" needs to log in.
We don't have an explicit log in button, rather, if
someone trys to access a website location which requires
login first, this screen will appear. This means that
we must track the current login state "behind the scene".
Also "logged in" assumes we are in session so the 
login state is handled by the session page class,
sPageClass.


*/ 
  	session_start(); 
  	
  	include_once($_SERVER['DOCUMENT_ROOT']."/homedata.php");
	include_once($rootlocation."PresenterClass.php");
	include_once($rootlocation."logFileClass.php");

	$lf = new logFile();
	
	$sp = new sessionPageBrowser($rootlocation.$regionandheader);

	if(isset($_GET['error'])) {
		$lf->logMessage("login.php: User not found in database.\n");
		$sp->setErrorStatus("User not found");
	}

		
	$sp->addHeader($rootlocation."HeaderContent.htm");

	$sp->addMenu($rootlocation."MenuContent.htm");	
	$sp->setFormAction($homelocationpart.$homelocationsub."login/processLogin.php");
	$sp->addBodyContent($rootlocation."login/login.html");

	$sp->addFooter($rootlocation."generalFooter.htm");
	


	
	if($sp->loggedIn()) {
		//echo "help";
		$lf->logMessage("login.php: ".$_SESSION['user'].'/'.$_SESSION['UserName']." already logged in.\n");
		//$sp->setLoginStatusMessage("Logged In");
		// Already logged in, set page to the location they requested
		//$sp->clearStatusBarMessage();
		//$sp->navigate();
		session_unset();
		session_destroy();
		header( 'Location: '.$homelocationpart.$homelocationsub.'index.php');

	} else {
		$lf->logMessage("login.php: ".$_SESSION['user'].'/'.$_SESSION['UserName']." logged out.\n");
		$sp->setLoginStatusMessage("Logged Out");
		
	}
	
	if($sp->loginError()) {
		$lf->logMessage("login.php: ".$_SESSION['user'].'/'.$_SESSION['UserName']." incorrect username or password.\n");
		$sp->setStatusBarMessage("Please enter the correct username and password.");
	}

	$sp->renderPage();

?>