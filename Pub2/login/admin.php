<?php 
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation.'logFileClass.php');
	include_once($rootlocation."PresenterClass.php");	
	
	$lf = new logFile();
	$minimumpriv = $upriv['webadmin'];
	//$lf->logMessage("admin.php: before session page minimumpriv: ".$minimumpriv."\n");
	$sp = new sessionPageBrowser($regionandheaderfull,$minimumpriv,$homelandingurlfull[$utype['resident']]);	
	//$lf->logMessage("admin.php: after session page mypriv: ".$sp->getUserPriv()." upriv-webadmin: ".$upriv['webadmin']."\n");

	$sp->addBodyContent($rootlocation.$staticcontentloc."adminbody.htm");

	$sp->renderPage();
		
?>
