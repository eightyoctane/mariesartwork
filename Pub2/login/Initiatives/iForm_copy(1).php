<?php

	require_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	
	require_once($rootlocation."login/libfunctions.php");
	include_once($rootlocation."login/mysqlClass.php");
	include_once($rootlocation."login/rFormClass.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/dbdata.php");
	include_once($rootlocation.'logFileClass.php');
	include_once($rootlocation.'PresenterClass.php');
	require_once($rootlocation."login/VisitorDataClass.php");
	
	
	$lf = new logFile();
		
	$sp = new sessionPageBrowser($regionandheadersplitfull,$upriv['anyowner'],$homelandingurlfull[$utype['resident']]);
	$lf->logMessage("iForm.php: root : ".$rootlocation."\n");
		
	$if = new iForm("initiatives.htm");
	$if->setWebsite($nameid);
	$if->setFormName('BMA Initiatives');
	$if->setEditCallBackPgm($homelocationpart.$homelocationsub.'login/Initiatives/iForm.php');
	$if->setSuccessCallBackPgm($homelandingurlfull[$utype['resident']]);
	
	
	$lf->logMessage("iForm.php: opening database\n");
		
	/* Connect to the MySQL server and select database. */
	$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
	$mysqldb->connect();
	$mysqldb->select();
	
	/*
	Has the user submitted data?
	If not, display the registration form.
	*/
	
	if (! isset($_POST['submitbutton'])){
	
		$lf->logMessage("iForm.php: Displaying first time\n");
		/*
			if->UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, 0,&$tboxhtml,&$tmsg);
			if->UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg, 0,&$pboxhtml,&$pmsg);
			$if->setCheckBox("notifyme",1);
		*/

		// get my user name and id from the database
	
		$uid = $sp->getUserID();

		$mysqldb->query("SELECT rowID, name FROM user WHERE rowID='$uid'");

		$row = $mysqldb->fetchObject();
		$if->setSubmittedBy($row->name);

	
		// get all the member names from the database
		
		$query = "SELECT u_type, u_priv, name, status FROM user WHERE u_type=".$utype['resident']." AND status='active' AND u_priv<".$upriv['webadmin'];
		//$query = "SELECT u_type, name, status FROM user";
		$mysqldb->query($query);

		$unamekey = 0;
		while($row = $mysqldb->fetchObject()) {
			$uname[$unamekey] = array($unamekey, $row->name);
			$lf->logMessage("name: ".$uname[$unamekey]."\n");
			$unamekey = $unamekey +1;
		}
		
		$if->setTextBox($lf, $uname, 0);

		//$if->UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, 0,&$tboxhtml,&$tmsg);
		//if->UpdateBoxSelection($lf, "schairname", $uname, $usertypemsg, 0,&$tboxhtml,&$tmsg);
		
	
		$sp->addBodyText($if->renderFormText());
		$sp->renderPage();

	
	
	} else {
	
		$lf->logMessage("iForm.php: got the input\n");
		
		$if->testFields($lf);
		
		$initiative= new Initiative($lf, $mysqldb);

		exit(0);
	}
	
	exit(0);
?>
