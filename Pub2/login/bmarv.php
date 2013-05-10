<?php 
	/* 
	Any logged in user can get this far.
	As a registered visitor, you are in
	session and get immediate
	access to registered users pages.
	*/
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation.'logFileClass.php');	
	include_once($rootlocation."PresenterClass.php");

	$lf = new logFile();
	$lf->logMessage("bmarv.php: Started\n");

	$sp = new sessionPageBrowser($regionandheaderfull,$upriv['anyowner'],$homelandingurlfull[$utype['nobody']]);	
	
	$sp->addBodyContent($rootlocation.$staticcontentloc."bmarv.html");
				
	$sp->renderPage();
?>
