<?php 
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation."PresenterClass.php");	
	include_once($rootlocation."login/sessionSetup.php");
	include_once($rootlocation.'logFileClass.php');
	
	$lf = new logFile();
	$lf->logMessage("news.php: Started\n");
	$sp = new sessionPageBrowser($regionandheaderfull);	
	$myusertype=$sp->getUserType();
	// even the lowest priv user can see the porpage
	sessionSetup($sp,$lf,$myusertype,$upriv['anyowner'],$homelandingurlfull[$myusertype]);

	$sp->addMenu($homemenulistfull[$myusertype]);	
		
	$sp->addBodyContent($rootlocation.$staticcontentloc."eventcalendarbody.htm");
	$sp->addFooter($rootlocation."generalFooter.htm");

	$sp->renderPage();
	
	
	
	
	
?>
