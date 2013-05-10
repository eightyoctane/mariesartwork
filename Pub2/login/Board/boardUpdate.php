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
		
	$bf = new bForm("boardf.htm");
	$bf->setWebsite($nameid);
	$bf->setFormName('BMA Initiatives');
	$bf->setEditCallBackPgm($homelocationpart.$homelocationsub.'login/Initiatives/boardUpdate.php');
	$bf->setSuccessCallBackPgm($homelandingurlfull[$utype['resident']]);
	
	
	$lf->logMessage("boardUpdate.php: opening database\n");
		
	/* Connect to the MySQL server and select database. */
	$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
	$mysqldb->connect();
	$mysqldb->select();
	
	/*
	Has the user submitted data?
	If not, display the registration form.
	*/
	
	if (! isset($_POST['submitbutton'])){
	
		$lf->logMessage("boardUpdate.php: Displaying first time\n");
	/*
		if->UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, 0,&$tboxhtml,&$tmsg);
		if->UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg, 0,&$pboxhtml,&$pmsg);
		$if->setCheckBox("notifyme",1);
	*/
		
		$sp->addBodyText($bf->renderFormText());
		$sp->renderPage();
	
	
	} else {
	
		$lf->logMessage("boardUpdate.php: got the input\n");
		
		$bf->testFields($lf);

		exit(0);
	}
	
	exit(0);
?>