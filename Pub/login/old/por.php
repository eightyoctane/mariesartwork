<?php 
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT']."/OSA/login/sPageClass.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/OSA/login/VisitorDataClass.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/OSA/login/sessionSetup.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/OSA/logFileClass.php");
	
	$lf = new logFile();
	$sp = new sPage($_SERVER['DOCUMENT_ROOT']."/OSA/bma_RegionAndHeader.htm");
	
	
	sessionSetup($sp,$lf,$_SESSION['u_type'],$_SESSION['u_priv'],$_SESSION['homepage']);

	$vd = new VisitorData();
	$nonresidentusers = $vd->getNumNonOwnerResidents();
	$residentusers = $vd->getNumOwnerResidents();
	$sp->addStatsMessage("Registered members: ");
	$sp->addStatsMessage('Academic/'.$nonresidentusers);
	$sp->addStatsMessage(' Power Industry/'.$residentusers);	
	$_SESSION['prevpage'] = $_SERVER['DOCUMENT_ROOT'].'/OSA/login/por.php';		
	$sp->addMenu($_SERVER['DOCUMENT_ROOT']."/OSA/homepageMenuL.html");	
	$sp->addBodyContent($_SERVER['DOCUMENT_ROOT']."/OSA/login/htmlfiles/porbody.htm");
	$sp->addFooter($_SERVER['DOCUMENT_ROOT']."/OSA/generalFooter.htm");
				
	$sp->renderSessionPage();
		
	
?>
