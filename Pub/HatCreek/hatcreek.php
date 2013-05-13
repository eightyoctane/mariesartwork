<?php
	include_once($_SERVER['DOCUMENT_ROOT']."/homedata.php");
	include_once($rootlocation."PresenterClass.php");
	
	$gp = new GeneralPageBrowser($rootlocation."RegionAndHeader.htm");
	
	$gp->addHeader($rootlocation."HeaderContent.htm");

	
	// Add a menu appropriate for this page and state
	$gp->addMenu($rootlocation."MenuContent.htm");
	
	$gp->addBodyContent($rootlocation."HatCreek/hatcreek.htm");
	
	$gp->addFooter($rootlocation."generalFooter.htm");
	
	$gp->renderPage();
?>