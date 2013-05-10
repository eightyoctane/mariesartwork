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
	include($rootlocation.'login/TableClass.php');


	
	$lf = new logFile();
	
	
	/* Connect to the MySQL server and select database. */
	$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
	$mysqldb->connect();
	$mysqldb->select();

		
	$sp = new sessionPageBrowser($regionandheaderfull,$upriv['anyowner'],$homelandingurlfull[$utype['resident']]);
	$lf->logMessage("DisplayProposalDetail.php: root : ".$rootlocation."\n");	
	
	$lf->logMessage("DisplayInitiative.php: opening database\n");
		
	// get my user name from the user database using the id associated with this session
	$myid = $sp->getUserID();
	$mysqldb->query("SELECT rowID, name, vid FROM user WHERE rowID='$myid'");
	$row = $mysqldb->fetchObject();
	$myname = $row->name;
	$vid = $row->vid;
	
	if( !isset($_GET['iid']) ) {
		$iid=0;
	} else {
		$iid=$_GET['iid'];
	}

	$pf = new pForm("DisplayProposalDetail.htm", $lf, $mysqldb);
	
	$pf->setWebsite($nameid);
	$pf->setFormName('BMA Proposals');
	$pf->setEditCallBackPgm($homelocationpart.$homelocationsub.'login/Initiatives/DisplayProposalDetail.php');
	$pf->setSuccessCallBackPgm($homelandingurlfull[$utype['resident']]);
		
	$lf->logMessage("DisplayProposalDetail.php: Rendering Table.\n");

/*		
	$dT = new Table();
	$dT->setTableWidthPcnt(100);
	$dT->setBoarderSize(1);
	$dT->setCellPaddingSize(7);		
	$dT->setCellText("arial",2,"#000000",TRUE,TRUE);
	
*/	
	$sp->addBodyText( $pf->returnProposalDetailTable($iid) );	

	$sp->renderPage();
	

?>
