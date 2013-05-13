<?php

if (isset($_POST['B1'])) {
//	$mailtext = $_POST['S1'];
//	echo $mailtext;
	mail("eightyoctane@yahoo.com", "New msg: mariesartwork.com", "$_POST[S1]\n\n");
	mail("mariesartwork@yahoo.com", "New msg: mariesartwork.com", "$_POST[S1]\n\n");
} else {

	include_once($_SERVER['DOCUMENT_ROOT']."/homedata.php");
	include_once($rootlocation."PresenterClass.php");
	
	$gp = new GeneralPageBrowser($rootlocation."RegionAndHeader.htm");
	
	$gp->addHeader($rootlocation."HeaderContent.htm");

	
	// Add a menu appropriate for this page and state
	$gp->addMenu($rootlocation."MenuContent.htm");
	
	$gp->addBodyContent($rootlocation."Contact/contact1.html");
	
	$gp->addFooter($rootlocation."generalFooter.htm");
	
	$gp->renderPage();
}
?>