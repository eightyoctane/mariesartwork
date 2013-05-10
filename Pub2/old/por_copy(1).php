<?php 
	session_start();
	
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation."PresenterClass.php");

	include_once($rootlocation."login/VisitorDataClass.php");
	include_once($rootlocation."login/sessionSetup.php");
	include_once($rootlocation.'logFileClass.php');
	
	$lf = new logFile();

	$lf->logMessage("por.php: starting regionandheaderpath: ".$regionandheaderfull."\n");
	
	$sp = new sessionPageBrowser($regionandheaderfull);
	
	sessionSetup($sp,$lf,$utype['resident'],$upriv['anyowner'],$homelocationpart.$homelocationsub."index.php");
	
	$vd = new VisitorData();
	$nonresidentusers = $vd->getNumNonOwnerResidents();
	$residentusers = $vd->getNumOwnerResidents();
	$sp->addStatsMessage("BMA registered visitors: ");
	$sp->addStatsMessage('Nonresident/'.$nonresidentusers);
	$sp->addStatsMessage(' Resident/'.$residentusers);	
	
	$_SESSION['prevpage'] = $homelocationpart.$homelocationsub.'login/por.php';		
	$lf->logMessage("por.php: adding menu\n");
	$sp->addMenu($homemenulistfull[$sp->getUserType()]);
	$lf->logMessage("por.php: adding body\n");		
	
	$sp->addBodyContent($rootlocation.$staticcontentloc."porbody.htm");
	$lf->logMessage("por.php: rendering page\n");
	
	$sp->renderPage();
		
	
?>
