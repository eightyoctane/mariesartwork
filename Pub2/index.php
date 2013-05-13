<?php
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation."PresenterClass.php");
	include_once($rootlocation.'logFileClass.php');
    $lf = new logFile();


	
	$gp = new GeneralPageBrowser($regionandheaderfull);

	#$lf->logMessage("index.php: Started at root location $_SERVER['DOCUMENT_ROOT']\n");

	$lf->logMessage(
	"index.php: Started at doc location ".$_SERVER['DOCUMENT_ROOT']."\n".
	"                      root location: ".$rootlocation."\n");


	if(isset($_GET['errmessage'])) {
		$lf->logMessage("index.php: Message coming in: ".$_GET['errmessage']."\n");
		$gp->setStatusBarMessage($_GET['errmessage']);
	}
	
	if(isset($_SESSION['UserName'])) {
		$lf->logMessage("index.php: Session username, ".$_SESSION['UserName']." is set and shouldn't be.\n");
		$gp->setStatusBarMessage("In Session but shouldn't be");
		session_unset();
		session_destroy();
		header( "Location: ".$homelocationurl);

	}

	$isiPad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
	
	if ($isiPad) {	
		$gp->addBodyContent($mobilehomepagebody);		
	} else {	
		$gp->addBodyContent($homepagebody);	
	}
	

	$gp->renderPage();	
?>