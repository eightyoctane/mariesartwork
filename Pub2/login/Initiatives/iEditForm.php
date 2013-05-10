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
	
	
	$lf = new logFile();
	
	
	/* Connect to the MySQL server and select database. */
	$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
	$mysqldb->connect();
	$mysqldb->select();

		
	$sp = new sessionPageBrowser($regionandheaderfull,$upriv['anyowner'],$homelandingurlfull[$utype['resident']]);
	$lf->logMessage("iForm.php: root : ".$rootlocation."\n");
		
	$if = new iForm("einitiatives.htm", $lf, $mysqldb);
	$if->setWebsite($nameid);
	$if->setFormName('BMA Initiatives');
	$if->setEditCallBackPgm($homelocationpart.$homelocationsub.'login/Initiatives/iForm.php');
	$if->setSuccessCallBackPgm($homelandingurlfull[$utype['resident']]);
	
	
	$lf->logMessage("iForm.php: opening database\n");
		
	// get my user name from the user database using the id associated with this session
	$myid = $sp->getUserID();
	//echo "<p>*******myid: ".$myid."</p>";
	$mysqldb->query("SELECT rowID, name FROM user WHERE rowID='$submittedbyid'");
	$row = $mysqldb->fetchObject();
	$submittedbyname = $row->name;
	
	// set up to display the submittedbyname
	$if->setSimpleText("submittedby", $submittedbyname);
	
	$iid = $_GET['iid'];
	//echo "<p>********** iid on inseption ".$iid."</p>";
	
	$in = new Initiative($lf);
	
	$inrow = $in->getInitiative($iid);
	//echo "<p>******submittedbyid: ".$inrow[submittedbyid]."</p>";
		
	//echo "<p>****my id: ".$myid." submittedbyid: ".$inrow[submittedbyid]."</p>";
	
	if( !$inrow ) {
			$sp->setErrorStatus("Sorry! This initiative is no longer available!");
			$sp->renderPage();
			exit();
	} else if( $inrow[istatus] != 'open' ) {
			//echo "<p>******** initiative status: ".$inrow->istatus."</p>";
			$sp->setErrorStatus("Sorry! Can't edit closed initiatives!");
			$sp->renderPage();
			exit();
	} else if( ($inrow[submittedbyid] != $myid) && ($sp->getUserPriv() < $upriv[webadmin]) ) {
			//echo "<p>******** iEditForm submitter must edit initiative</p>";
			$sp->setErrorStatus("Sorry! Initiative must be edited by submitter!");
			$sp->renderPage();
			exit();	
	} 	
	
	
	

	if (! isset($_POST['submitbutton'])){
	
		$lf->logMessage("iEditForm.php: Displaying form for first time\n");

		$if->initializeEditForm(&$inrow);

		$sp->addBodyText($if->renderFormText());
		$sp->renderPage();
		exit(0);
	
	
	} else {
	
		if( !$if->editInputComplete(&$valuearray) ){
			//echo "<p>*************editInputComplete refreshing</p>";
			//print_r($valuearray);
			//print("\n\n");	
			$sp->addBodyText($if->renderFormText());
			$sp->renderPage();
			exit(0);
		}
		
		$valuearray['iid'] = $iid; 

		$in->updateInitiative(&$valuearray);
		
		//echo "<p>*****iEditForm made it this far</p>";

		header( 'Location: '.$homelandingurlfull[$utype['resident']]);
		exit(0);
		
	}
	
	exit(0);
?>
