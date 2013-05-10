<?php
	require_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	
	include_once($rootlocation."login/mysqlClass.php");
	include_once($rootlocation."login/rFormClass.php");
	include_once($rootlocation."dbdata.php");
	include_once($rootlocation.'logFileClass.php');
	include_once($rootlocation.'PresenterClass.php');
	include_once($rootlocation."login/Projects/project.php");
	
	
	$lf = new logFile();
	
	
	/* Connect to the MySQL server and select database. */
	$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
	$mysqldb->connect();
	$mysqldb->select();

		
	$sp = new sessionPageBrowser($regionandheaderfull,$upriv['anyowner'],$homelandingurlfull[$utype['resident']]);
	$sp->addHeadTextContent('<script type="text/javascript" src="/ckeditor/ckeditor.js"></script>');
	
	$lf->logMessage("iForm.php: root : ".$rootlocation."\n");
		
	$if = new pjForm("selectproject.htm", $lf, $mysqldb);
	$if->setWebsite($nameid);
	$if->setFormName('BMA Proposals');
	$if->setEditCallBackPgm($homelocationpart.$homelocationsub.'login/Initiatives/proposalfrominitiative.php');
	$if->setSuccessCallBackPgm($homelandingurlfull[$utype['resident']]);
	$if->setSubmit('submitbutton','Upload');
	
	$pj = new Project($lf);	

	if (! isset($_POST['submitbutton'])){
	
		$lf->logMessage("proposalfrominitiative.php: Displaying form for first time\n");

		$if->initializeCommentForm();		

		$sp->addBodyText($if->renderFormText());
		$sp->renderPage();
		exit(0);
	
	} else {
	
		$valuearray['myuserid'] = $sp->getUserID();
		$valuearray['myusername'] = $sp->getName();
				
		if( !($if->cinputComplete($sp, &$valuearray)) ) {		
			$sp->addBodyText($if->renderFormText());
			$sp->renderPage();
			exit(0);	
		}
		$sp->addBodyText($if->renderFormText());
		$sp->renderPage();
		exit(0);
		
		exit(0);
		
	}
	
	exit(0);
?> 