<?php 
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation.'logFileClass.php');	
	include_once($rootlocation."PresenterClass.php");
	require_once($rootlocation."dbdata.php");
	require_once($rootlocation."login/mysqlClass.php");
	require_once($rootlocation."login/VisitorDataClass.php");
	
	$lf = new logFile();
	$lf->logMessage("por.php: Started\n");
	$sp = new sessionPageBrowser($regionandheaderfull,$upriv['anyowner'],$homelandingurlfull[$utype['nobody']]);
	
	if($sp->getUserType() != $utype['resident']) {
		$lf->logMessage("por.php: User type not right for this page\n");
		header( 'Location: '.$homelandingurlfull[$sp->getUserType()]);	
	}
	
	displayUserData($sp,$lf,$system,$dbuser,$dbpwd,$database);
	
	$sp->addBodyContent($rootlocation.$staticcontentloc."porBody.htm");
				
	$sp->renderPage();


function displayUserData($sp,$lf,$system,$dbuser,$dbpwd,$database) {
	$vd = new VisitorData($lf);
	$nonresidentusers = $vd->getNumNonOwnerResidents();
	$residentusers = $vd->getNumOwnerResidents();
	$sp->addStatsMessage("BMA registered visitors: ");
	$sp->addStatsMessage('Nonresident/'.$nonresidentusers);
	$sp->addStatsMessage(' Resident/'.$residentusers);	
}
?>
