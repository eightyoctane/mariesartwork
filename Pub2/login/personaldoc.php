<?php 
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation."PresenterClass.php");
	include_once($rootlocation."logFileClass.php");
	include_once($rootlocation."logFileClass.php");
	
	$lf = new logFile();
	
	$sp = new sessionPageBrowser($rootlocation."RegionAndHeader.htm", $upriv['anyowner'],$homelandingurlfull[$utype['resident']]);	
	$sp->addHeader($rootlocation."HeaderContent.htm");	

	$sp->addBodyContent($rootlocation.$staticcontentloc."personaldocbody.htm");	
	
	$sp->renderPage();
?>
