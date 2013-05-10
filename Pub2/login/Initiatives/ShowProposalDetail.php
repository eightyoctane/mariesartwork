<?php
	require_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	
	require_once($rootlocation."login/libfunctions.php");
	include_once($rootlocation."login/mysqlClass.php");
	include_once($rootlocation."login/rFormClass.php");
	include_once($rootlocation."dbdata.php");
	include_once($rootlocation.'logFileClass.php');
	include_once($rootlocation.'PresenterClass.php');
	require_once($rootlocation."login/VisitorDataClass.php");
	include_once($rootlocation."login/Initiatives/initiative.php");
	include($rootlocation."login/Initiatives/vote.php");


	
	$lf = new logFile();
	
	
	/* Connect to the MySQL server and select database. */
	$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
	$mysqldb->connect();
	$mysqldb->select();

		
	$sp = new sessionPageBrowser($regionandheaderfull,$upriv['anyowner'],$homelandingurlfull[$utype['resident']]);
	$lf->logMessage("ShowProposalDetail.php: root : ".$rootlocation."\n");	
	
	$lf->logMessage("ShowProposalDetail.php: opening database\n");
	
	$pf = new pForm("ShowProposalDetail.htm", $lf, $mysqldb);
	
	$pf->setWebsite($nameid);
	$pf->setFormName('BMA Proposal Detail');
	$pf->setEditCallBackPgm($homelocationpart.$homelocationsub.'login/Initiatives/ShowProposalDetail.php');
	$pf->setSuccessCallBackPgm($homelandingurlfull[$utype['resident']]);
	
	$pid = $_GET['pid'];
	echo "<p>********** pid on inseption ".$pid."</p>";
		
	$lf->logMessage("ShowProposalDetail.php: Rendering details.\n");
	
	$sp->addBodyText($pf->populateDetailPage($pid));
	
	$sp->renderPage();
?>
