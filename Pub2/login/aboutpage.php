<?php
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation."PresenterClass.php");
	
	$gp = new GeneralPageBrowser($rootlocation.$regionandheader);
	$gp->addHeader($rootlocation."HeaderContent.htm");
	
	$gp->addBodyContent($rootlocation."aboutpage.htm");
	
	$gp->renderPage();
?>
