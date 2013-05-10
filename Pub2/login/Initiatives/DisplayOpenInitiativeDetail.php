<?php
	require_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	
	require_once($rootlocation."login/libfunctions.php");
	include_once($rootlocation."login/mysqlClass.php");
	include_once($rootlocation."login/rFormClass.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/dbdata.php");
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
	$lf->logMessage("DisplayInitiativeDetail.php: root : ".$rootlocation."\n");	
	
	$lf->logMessage("DisplayInitiativeDetail.php: opening database\n");
		
	// get my user name from the user database using the id associated with this session
	$myid = $sp->getUserID();
	$mysqldb->query("SELECT rowID, name, vid FROM user WHERE rowID='$myid'");
	$row = $mysqldb->fetchObject();
	$myname = $row->name;
	$vid = $row->vid;
	//echo "<p>*********voter id  ".$vid."</p>";
	
	$if = new iForm("votingtable.htm", $lf, $mysqldb);
	
	$if->setWebsite($nameid);
	$if->setFormName('BMA Initiatives');
	$if->setEditCallBackPgm($homelocationpart.$homelocationsub.'login/Initiatives/DisplayInitiativeDetail.php');
	$if->setSuccessCallBackPgm($homelandingurlfull[$utype['resident']]);
	
	if (! ($iid = $if->iSubmitButton('submitbutton') ) ) {
		
		
		$lf->logMessage("DisplayInitiativeDetail.php: Rendering Table.\n");
		
		
		$sp->addBodyText( $if->returnInitiativeDetailTable($myid, $sp->getUserPriv(), $vid, 0, $if, 'open', 'short') );
		
		$sp->renderPage();
	
	} else {
	
		$lf->logMessage("DisplayOpenInitiativeDetail.php: Received input.\n");
		$sp->addBodyText($if->returnInitiativeDetailTable($myid, $sp->getUserPriv(), $vid, $iid, $if, 'open', 'short'));	
		$sp->renderPage();
		exit(0);

	}

?>