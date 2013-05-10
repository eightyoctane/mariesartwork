<?php 
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation."PresenterClass.php");	
	include_once($rootlocation.'logFileClass.php');
	
	$lf = new logFile();
	$lf->logMessage("news.php: Started\n");
	$sp = new sessionPageBrowser($regionandheaderfull,$upriv['anyowner'],$homelandingurlfull[$utype['resident']]);	
	$myusertype=$sp->getUserType();
		
	$sp->addBodyContent($rootlocation.$staticcontentloc."airparkcarebody.htm");

	$sp->renderPage();
	
?>
