<?php 
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation.'logFileClass.php');
	include_once($rootlocation."PresenterClass.php");	
	
	$lf = new logFile();
	$lf->logMessage("news.php: Started\n");
	$sp = new sessionPageBrowser($regionandheaderfull,$upriv['anyowner'],$homelandingurlfull[$utype['resident']]);	
	$sp->addBodyContent($rootlocation.$staticcontentloc."newsBody.htm");
	$sp->renderPage();
?>
