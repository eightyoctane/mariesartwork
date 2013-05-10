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
		
	$if = new iForm("initiatives.htm", $lf, $mysqldb);
	$if->setWebsite($nameid);
	$if->setFormName('BMA Initiatives');
	$if->setEditCallBackPgm($homelocationpart.$homelocationsub.'login/Initiatives/iForm.php');
	$if->setSuccessCallBackPgm($homelandingurlfull[$utype['resident']]);
	
	
	$lf->logMessage("iForm.php: opening database\n");
		
	// get my user name from the user database using the id associated with this session
	$submittedbyid = $sp->getUserID();
	$mysqldb->query("SELECT rowID, name FROM user WHERE rowID='$submittedbyid'");
	$row = $mysqldb->fetchObject();
	$submittedbyname = $row->name;
	
	// set up to display the submittedbyname
	$if->setSimpleText("submittedby", $submittedbyname);
	

	if (! isset($_POST['submitbutton'])){
	
		$lf->logMessage("iForm.php: Displaying form for first time\n");
		$if->initializeForm();
		$sp->addBodyText($if->renderFormText());
		$sp->renderPage();
		exit(0);
	
	
	} else {

		$valuearray['dateopen'] = time();
		$valuearray['submittedbyid'] = $submittedbyid;
		$valuearray['submittedbynm'] = $submittedbyname;
		$valuearray['istatus'] = "open";
		$valuearray['voteid'] = 0;	
	
		if( !$if->inputComplete(&$valuearray) ){
			//echo "<p>*************</p>";
			//print_r($valuearray);
			//print("\n\n");	
			$sp->addBodyText($if->renderFormText());
			$sp->renderPage();
			exit();
		} 

		$in = new Initiative($lf);

		// will return a 1 if $descname is already in the database	
		switch( $returnval = $in->openInitiative($valuearray) ) {
		
			case 1:
				// $descname is already in the database
				$if->setTextArea(1, 15, "descname", "", "duplicate descriptive name ".$valuearray['descname'].", please enter a different name");
				$lf->logMessage("iForm.php: Displaying form again\n");
				$sp->addBodyText($if->renderFormText());
				$sp->renderPage();
				exit(0);
			
			case 2:
				// duplicate voter instance entry set the error and display
				$sp->setErrorStatus("Duplicate voter instance entry");
				$lf->logMessage("iForm.php: Displaying form again\n");
				$sp->addBodyText($if->renderFormText());
				$sp->renderPage();
				exit(0);
				
		}
		//echo "<p>*****iForm made it this far</p>";

		if($valuearray['suggchairman']) {
			$mailtext = 
			"You have been recommended as a chairman to lead\n".
			"BMA Initiative: ".$valuearray['iname'].". Please\n".
			"check the status of this initiative at the website.\n\n";
						
			$subject = "Re: BMA Initiative:".$valuearray['iname'];
			
			mail($valuearray['schairemail'], $subject, $mailtext);

		}
		
		if($valuearray['doproposal']) {
		
			$ptype = "Initiative Proposal";
		
			// now bounce to the proposal page using the iid
			header('Location: '.$homelocationpart.$homelocationsub."login/Initiatives/pForm.php?iid=".$valuearray['iid']."&ptype=".$ptype);				
			exit(0);
		}

		header( 'Location: '.$homelandingurlfull[$utype['resident']]);
		exit(0);
		
	}
	
	exit(0);
?>
