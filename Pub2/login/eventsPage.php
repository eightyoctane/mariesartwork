<?php
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation."PresenterClass.php");
	include_once($rootlocation."logFileClass.php");
	include_once($rootlocation."/login/sessionSetup.php");
	
	
	$lf = new logFile();
	//$lf->logMessage("eventsPage.php: We're in eventsPage\n");

	//$lf->logMessage("Logged in and going to events page\n");
	
	$sp = new sessionPageBrowser($regionandheaderfull,$upriv['anyowner'],$homelandingurlfull[$utype['resident']]);
	$sp->addBodyContent($rootlocation.$staticcontentloc.'eventsbody.htm');

	$sp->renderPage();	
	
/*		
	$gp = new generalPageBrowser($regionandheaderfull);

	// this because we could come here from projects page and be logged in


	if( $gp->validateInSession() ) {
	
		$lf->logMessage("Logged in and going to events page\n");
		
		$sp = new sessionPageBrowser($regionandheaderfull,$upriv['anyowner'],$homelandingurlfull[$utype['resident']]);
		$sp->addBodyContent($rootlocation.$staticcontentloc.'eventsbody.htm');

		$sp->renderPage();	
	
	} else {
			
		$lf->logMessage("eventsPage.php: it's thinking we're logged out\n");
		$gp->addBodyContent($rootlocation.$staticcontentloc."eventsbody.htm");
		$gp->renderPage();
	

	}
	
*/		
	
?>
