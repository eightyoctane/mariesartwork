<?php 
	//session_start();
	
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation."PresenterClass.php");	
	//include_once($rootlocation."login/sessionSetup.php");
	include_once($rootlocation.'logFileClass.php');
	
	$lf = new logFile();
	$lf->logMessage("news.php: Started\n");
	$sp = new sessionPageBrowser($regionandheaderfull,$upriv['board'],$homelandingurlfull[$utype['resident']]);	
		
	$sp->addBodyContent($rootlocation.$staticcontentloc."boardBody.htm");

	$sp->renderPage();

?>
