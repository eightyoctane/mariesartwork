                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             <?php
	require_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	
	include_once($rootlocation."login/mysqlClass.php");
	include_once($rootlocation."login/rFormClass.php");
	include_once($rootlocation."dbdata.php");
	include_once($rootlocation.'logFileClass.php');
	include_once($rootlocation.'PresenterClass.php');
	require_once($rootlocation."login/VisitorDataClass.php");
	include_once($rootlocation."login/Initiatives/proposal.php");
	include_once($rootlocation."login/Initiatives/initiative.php");
	
	
	$lf = new logFile();
	
	
	/* Connect to the MySQL server and select database. */
	$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
	$mysqldb->connect();
	$mysqldb->select();

		
	$sp = new sessionPageBrowser($regionandheaderfull,$upriv['anyowner'],$homelandingurlfull[$utype['resident']]);
	$lf->logMessage("iForm.php: root : ".$rootlocation."\n");
		
	$if = new pForm("proposal.htm", $lf, $mysqldb);
	$if->setWebsite($nameid);
	$if->setFormName('BMA Proposals');
	$if->setEditCallBackPgm($homelocationpart.$homelocationsub.'login/Initiatives/pForm.php');
	$if->setSuccessCallBackPgm($homelandingurlfull[$utype['resident']]);
	
	$iid = $_GET['iid'];
	//echo "<p>********** iid on inseption ".$iid."</p>";
	$in = new Initiative($lf);
	if( !($inrow = $in->getInitiative($iid)) ) {
			$sp->setErrorStatus("Sorry! This initiative is no longer available!");
			$sp->renderPage();
			exit();
	} else if ($inrow[istatus] != 'open') {
			echo "<p>******** initiative status: ".$inrow->istatus."</p>";
			$sp->setErrorStatus("Sorry! Can't upload proposals for closed initiatives!");
			$sp->renderPage();
			exit();
	}
			

	//echo "<p>***********initiative name: ".$inrow[iname]."</p>";
	
	$ptype = $_GET['ptype'];
	//echo "<p>********** ptype on inseption ".$ptype."</p>";

		
	//set hidden value
	$if->setHiddenValue("iid", $iid);

	
	$lf->logMessage("pForm.php: opening database\n");
		
	// get my user name from the user database using the id associated with this session
	$submittedbyid = $sp->getUserID();
	$mysqldb->query("SELECT rowID, name FROM user WHERE rowID='$submittedbyid'");
	$row = $mysqldb->fetchObject();
	$submittedbynm = $row->name;
	
	// set up to display the submittedbyname
	$if->setSimpleText("submittedbynm", $submittedbyname);
	$if->setSubmit('submitbutton','Upload');			
	$if->setText('submittedbynm', $submittedbynm);
	$if->setText('ptype', $ptype);
	$if->setText('iname', $inrow[iname]);
	

	if (! isset($_POST['submitbutton'])){
	
		$lf->logMessage("pForm.php: Displaying form for first time\n");

		$if->initializeForm();

		$sp->addBodyText($if->renderFormText());
		$sp->renderPage();

	
	
	} else {
	
		//echo "<p>************** Got the input! </p>";

		$valuearray['dateopen'] = time();
		$valuearray['submittedbyid'] = $submittedbyid;
		$valuearray['submittedbynm'] = $submittedbynm;
		$valuearray['pstatus'] = "open";
		$valuearray['voteid'] = 0;	
			
		if( !$if->inputComplete(&$valuearray) ){
			//echo "<p>*************</p>";
			//print_r($valuearray);
			//print("\n\n");
			
			//echo "<p>************ input incomplete! </p>";
				
			$sp->addBodyText($if->renderFormText());
			$sp->renderPage();
			exit();
		} 
		//print_r($valuearray);
		$target = $rootlocation."login/Proposals/"; 
		$valuearray['filename'] = basename( $_FILES['uploaded']['name']) ;
		$target = $target . basename( $_FILES['uploaded']['name']) ; 
		$ok=1;

		//This is our size condition 
		if ($uploaded_size > 350000000) { 
			echo "Sorry, your file is too large.<br>";
			$msg = "Sorry, your file is too large (max 350MB), please try a smaller file."; 
			$ok=0; 
		} else
		
		if (($uploaded_type=="text/php")) {
			$msg = "Sorry, PDF or DOC type files only.";
			echo "Sorry, PDF or DOC type files only.<br>";
			$ok=0;
		
		} else
		
 
		if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target)) {
			//echo "The file: ". basename( $_FILES['uploadedfile']['name']). " has been uploaded";
			$msg = "The file: ". basename( $_FILES['uploadedfile']['name']). " has been uploaded";
			$ok=1;
		} else {
			// REVISIT - in this case we should probably set error status and return
			// to another page
			// 	$sp->setErrorStatus("Duplicate voter instance entry");

			echo "Sorry, there was a problem uploading your file.";
			$msg = "Sorry, there was a problem uploading your file.";
			$ok=0;
		}

		if( !$ok ) {
			$if->setSimpleErrorText('uploaded', $msg);
			$lf->logMessage("pForm.php: Displaying form again\n");
			$sp->addBodyText($if->renderFormText());
			$sp->renderPage();

		}

		
		// The file upload went ok, so we can add the info to the database
		$ip = new Proposal($lf);

		// will return a 1 if $descname is already in the database	
		switch( ($returnval = $ip->openProposal($iid, $valuearray)) ) {
		
			case 1:
				// $descname is already in the database
				$if->setTextBox(15, "descname", "", "duplicate descriptive name ".$valuearray['descname'].", please enter a different name");
				unlink($target);
				$sp->addBodyText($if->renderFormText());
				$sp->renderPage();
				exit(0);
				break;
			case 2:
				// duplicate voter instance entry set the error and display
				$sp->setErrorStatus("Duplicate voter instance entry");
		
		}
		$lf->logMessage("pForm.php: Displaying form again\n");
		$if->setSimpleErrorText('uploaded', $msg);
		$sp->addBodyText($if->renderFormText());
		$sp->renderPage();
		exit(0);
		
	}
	
	exit(0);
?> 
