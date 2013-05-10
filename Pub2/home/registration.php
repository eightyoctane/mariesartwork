<?php
include_once($_SERVER['DOCUMENT_ROOT']."/homedataV0.php");
include_once($rootlocation."PresenterClass.php");	
include_once($rootlocation.'logFileClass.php');
include_once($rootlocation."login/sessionSetup.php");

$gp = new generalPageBrowser($regionandheaderfull);
	
	// Add a menu appropriate for this page and state
	$gp->addMenu($homemenufull);
	
	$gp->addBodyContent($rootlocation."regPreamble.htm");
	
	$gp->renderPage();
?>