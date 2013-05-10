<?php 
		
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation."PresenterClass.php");	
	include_once($rootlocation.'logFileClass.php');
	
	$lf = new logFile();
	$lf->logMessage("chairman.php: Started\n");
	$sp = new sessionPageBrowser($regionandheaderfull,$upriv['commchair'],$homelandingurlfull[$utype['resident']]);	
		
	$sp->addBodyContent($rootlocation.$staticcontentloc."chairmanBody.htm");

	$sp->renderPage();

	
	
?>
