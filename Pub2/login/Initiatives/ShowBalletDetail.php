<?php
	require_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	
	require_once($rootlocation."login/libfunctions.php");
	include_once($rootlocation."login/mysqlClass.php");
	include_once($rootlocation."login/rFormClass.php");
	include_once($rootlocation."dbdata.php");
	include_once($rootlocation.'logFileClass.php');
	include_once($rootlocation.'PresenterClass.php');
	
	$lf = new logFile();
	
	
	/* Connect to the MySQL server and select database. */
	$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
	$mysqldb->connect();
	$mysqldb->select();

		
	$sp = new sessionPageBrowser($regionandheaderfull,$upriv['anyowner'],$homelandingurlfull[$utype['resident']]);
	$lf->logMessage("DisplayInitiative.php: root : ".$rootlocation."\n");	
	
	$lf->logMessage("ShowBalletDetail.php: opening database\n");
	
	$if = new iForm("ShowBalletDetails.htm", $lf, $mysqldb);
	
	$if->setWebsite($nameid);
	$if->setFormName('BMA Ballet Detail');
	$if->setEditCallBackPgm($homelocationpart.$homelocationsub.'login/Initiatives/ShowBalletDetail.php');
	$if->setSuccessCallBackPgm($homelandingurlfull[$utype['resident']]);
	
	$iid = $_GET['iid'];
	//echo "<p>********** iid on inseption ".$iid."</p>";
	
	$sp->addBodyText($if->returnVotingResultsTable($iid, $lf));
	$sp->renderPage();
	


	
	
	
	
	
	
?>
