<?php
include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
include_once($rootlocation."PresenterClass.php");	
include_once($rootlocation.'logFileClass.php');
include_once($rootlocation."login/sessionSetup.php");

$gp = new generalPageBrowser($regionandheaderfull);
	
	$gp->addHeader($headercontent);
	//$gp->addMenu($homemenufull);
	
	$gp->addBodyContent($rootlocation."login/htmlfiles/regPreamble.htm");
	
	$gp->renderPage();
?>
