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
	$lf->logMessage("DisplayInitiative.php: root : ".$rootlocation."\n");	
	
	$lf->logMessage("ShowInitiativeDetail.php: opening database\n");
	
	$if = new iForm("ShowInitiativeDetails.htm", $lf, $mysqldb);
	
	$if->setWebsite($nameid);
	$if->setFormName('BMA Initiative Detail');
	$if->setEditCallBackPgm($homelocationpart.$homelocationsub.'login/Initiatives/ShowInitiativeDetail.php');
	$if->setSuccessCallBackPgm($homelandingurlfull[$utype['resident']]);
	
	$iid = $_GET['iid'];
	//echo "<p>********** iid on inseption ".$iid."</p>";
		
	$lf->logMessage("ShowInitiativeDetail.php: Rendering details.\n");
	
	$sp->addBodyText($if->populateDetailPage($iid));
	
	$sp->renderPage();
	


	
	
	
	
	
	
?>
