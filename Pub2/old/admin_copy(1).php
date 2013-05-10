<?php 
	session_start();
	
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation."PresenterClass.php");
	include_once($rootlocation."login/sessionSetup.php");
	include_once($rootlocation.'logFileClass.php');
	
	$lf = new logFile();
	$lf->logMessage("admin.php: starting\n");
	$sp = new sessionPageBrowser($regionandheaderfull);
	$sp->setPrevPage($adminauthinfo['adminhomelandingurl']);

	sessionSetup($sp,$lf,$adminauthinfo['adminutype'],$adminauthinfo['adminreqpriv'],$adminauthinfo['adminhomelandingurl']);
	
	$lf->logMessage("admin.php: returned from sessionSetup\n");
		
	$sp->addMenu($adminauthinfo['adminmenufull']);
	
	$lf->logMessage("admin.php: adding body content\n");
	
	$sp->addBodyContent($adminauthinfo['adminhtmlpage']);
	
	$lf->logMessage("admin.php: rendering page\n");
	
	$sp->renderPage();
		
	
?>
