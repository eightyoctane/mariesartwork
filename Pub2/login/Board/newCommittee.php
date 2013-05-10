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
		
	$sp = new sessionPageBrowser($regionandheadersplitfull,$upriv['boardpres'],$homelandingurlfull[$utype['resident']]);
	$lf->logMessage("iForm.php: root : ".$rootlocation."\n");
		
	$cf = new cForm("newcommittee.htm");
	$cf->setWebsite($nameid);
	$cf->setFormName('BMA Committees');
	$cf->setEditCallBackPgm($homelocationpart.$homelocationsub.'login/Board/newCommittee.php');
	$cf->setSuccessCallBackPgm($homelandingurlfull[$utype['resident']]);
	
	
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
		cf->UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, 0,&$tboxhtml,&$tmsg);
		cf->UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg, 0,&$pboxhtml,&$pmsg);
		$cf->setCheckBox("notifyme",1);
	*/
		
		$sp->addBodyText($cf->renderFormText());
		$sp->renderPage();
	
	
	} else {
	
		$lf->logMessage("iForm.php: got the input\n");
		
		$cf->testFields($lf);

		exit(0);
	}
	
	exit(0);
?>