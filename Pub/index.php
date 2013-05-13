<?php
	include_once($_SERVER['DOCUMENT_ROOT']."/homedata.php");
	include_once($rootlocation."PresenterClass.php");
	
	$gp = new GeneralPageBrowser($rootlocation.$regionandheader);
	

	if(isset($_GET['errmessage'])) {
		$lf->logMessage("index.php: Message coming in: ".$_GET['errmessage']."\n");
		$gp->setStatusBarMessage($_GET['errmessage']);
	}
	
	if(isset($_SESSION['UserName'])) {
		$lf->logMessage("index.php: Session username, ".$_SESSION['UserName']." is set and shouldn't be.\n");
		$gp->setStatusBarMessage("In Session but shouldn't be");
	}


	$gp->addHeader($rootlocation."HeaderContent.htm");	

	$gp->addMenu($rootlocation."MenuContent.htm");
	
	$gp->addBodyContent($rootlocation."HPBodyMarquee.htm");
	
	$gp->addBodyContent($rootlocation."BodyContent.htm");
	
	$gp->addRtBodyContent($rootlocation."RtBodyContent.htm");
	
	$gp->addFooter($rootlocation."generalFooter.htm");
	
	$gp->renderPage();
?>