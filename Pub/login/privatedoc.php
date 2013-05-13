<?php 
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT']."/homedata.php");
	include_once($rootlocation."PresenterClass.php");
	include_once($rootlocation."logFileClass.php");
	include_once($rootlocation."login/sessionSetup.php");
	include_once($rootlocation."logFileClass.php");
	
	$lf = new logFile();
	
	$sp = new sessionPageBrowser($rootlocation."RegionAndHeader.htm");	
	$sp->addHeader($rootlocation."HeaderContent.htm");	
	$sp->addMenu($rootlocation."login/MenuContentAtLogin.htm");

	sessionSetup($sp,$lf,1,6,$homelocationpart.$homelocationsub."login/loginlanding.php");
		

	$_SESSION['prevpage'] = $homelocationpart.$homelocationsub."login/personaldoc.php";
	$sp->addBodyContent($rootlocation."login/htmlfiles/privatedocbody.htm");	
	
	$sp->renderPage();
		
	
?>
