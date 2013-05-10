<?php 
	require_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation."PresenterClass.php");
	include_once($rootlocation."login/sessionSetup.php");
	include_once($rootlocation.'/logFileClass.php');
	$lf = new logFile();
	$sp = new sessionPageBrowser($rootlocation."bma_RegionAndHeader.htm",$upriv['contractor'],$homelandingurlfull[$utype['contractor']]);
	//sessionSetup($sp, $lf, $utype['contractor'],$upriv['submit'],$rootlocation."login/login.php");
		
	$sp->addBodyContent("htmlfiles/contractorlandingbody.htm");
	
	$sp->renderPage();
		
	
?>
