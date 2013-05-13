<?php
	include_once($_SERVER['DOCUMENT_ROOT']."/homedata.php");
	include_once($rootlocation."PresenterClass.php");
	
	$gp = new GeneralPageBrowser($rootlocation.$regionandheader);
	$gp->addHeader($rootlocation."HeaderContent.htm");
	$gp->addMenu($rootlocation."MenuContent.htm");
	
	$gp->addBodyContent($rootlocation."aboutpage.htm");
	
	$gp->addFooter($rootlocation."generalFooter.htm");
	
	$gp->renderPage();
?>