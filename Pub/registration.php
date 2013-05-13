<?php
	include_once($_SERVER['DOCUMENT_ROOT']."/OSA/PresenterClass.php");
	
	$gp = new generalPageBrowser($_SERVER['DOCUMENT_ROOT']."/OSA/RegionAndHeader.htm");
	
		$gp->addHeader($_SERVER['DOCUMENT_ROOT']."/OSA/HeaderContent.htm");
	
	// Add a menu appropriate for this page and state
	$gp->addMenu($_SERVER['DOCUMENT_ROOT']."/OSA/MenuContent.htm");
	
	$gp->addBodyContent($_SERVER['DOCUMENT_ROOT']."/OSA/regPreamble.htm");
	$gp->addFooter($_SERVER['DOCUMENT_ROOT']."/OSA/generalFooter.htm");
	
	$gp->renderPage();
?>