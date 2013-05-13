<?php

	require_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	
	require_once($rootlocation."login/libfunctions.php");
	include_once($rootlocation."login/mysqlClass.php");
	include_once($rootlocation."login/rFormClass.php");
	/* include_once($_SERVER['DOCUMENT_ROOT']."/dbdata.php"); */
	include_once($rootlocation."/dbdata.php");
	include_once($rootlocation.'logFileClass.php');
	include_once($rootlocation.'PresenterClass.php');
	include_once($rootlocation."login/Projects/project.php");
	
	
	$lf = new logFile();
	
	
	/* Connect to the MySQL server and select database. */
	$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
	$mysqldb->connect();
	$mysqldb->select();

		
	$sp = new sessionPageBrowser($regionandheaderfull,$upriv['anyowner'],$homelandingurlfull[$utype['resident']]);
	$lf->logMessage("pjForm.php: root : ".$rootlocation."\n");
		
	$if = new pjForm("project.htm", $lf, $mysqldb);
	$if->setWebsite($nameid);
	$if->setFormName('BMA Projects');
	$if->setEditCallBackPgm($homelocationpart.$homelocationsub.'login/Initiatives/pjForm.php');
	$if->setSuccessCallBackPgm($homelandingurlfull[$utype['resident']]);
	
	
	$lf->logMessage("pjForm.php: opening database\n");
		
	// get my user name from the user database using the id associated with this session
	$submittedbyid = $sp->getUserID();
	$mysqldb->query("SELECT rowID, name FROM user WHERE rowID='$submittedbyid'");
	$row = $mysqldb->fetchObject();
	$submittedbyname = $row->name;
	
	// set up to display the submittedbyname
	$if->setSimpleText("submittedby", $submittedbyname);

	if (! isset($_POST['submitbutton'])){
	
		$lf->logMessage("pjForm.php: Displaying form for first time\n");
		
		$if->initializeProjectForm();
		$sp->addBodyText($if->renderFormText());
		$sp->renderPage();
		exit(0);
	
	
	} else {

		$valuearray['dateopen'] = time();
		$valuearray['submittedbyid'] = $submittedbyid;
		$valuearray['submittedbynm'] = $submittedbyname;
		$valuearray['istatus'] = "open";
	
		if( !$if->inputComplete(&$valuearray) ){
			//echo "<p>*************</p>";
			//print_r($valuearray);
			//print("\n\n");	
			$sp->addBodyText($if->renderFormText());
			$sp->renderPage();
			exit();
		} 
		//echo "<p>******pjForm valuearray</p>";
		//print_r($valuearray);
		
		$valuearray['filesdir'] = $valuearray['descname'];
		
		$in = new Project($lf);

		// will return a 1 if $descname is already in the database	
		switch( $returnval = $in->createProject($valuearray) ) {
		
			case 1:
				// $descname is already in the database
				$if->setTextArea(1, 15, "descname", "", "duplicate descriptive name ".$valuearray['descname'].", please enter a different name");
				$lf->logMessage("pjForm.php: Displaying form again\n");
				$sp->addBodyText($if->renderFormText());
				$sp->renderPage();
				exit(0);
			
		}
		
		
		//echo "<p>*****pjForm made it this far</p>";

		header( 'Location: '.$homelandingurlfull[$utype['resident']]);
		exit(0);
		
	}
	
	exit(0);
?>