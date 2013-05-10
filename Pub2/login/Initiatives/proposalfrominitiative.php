<?php
	require_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	
	include_once($rootlocation."login/mysqlClass.php");
	include_once($rootlocation."login/rFormClass.php");
	include_once($rootlocation."dbdata.php");
	include_once($rootlocation.'logFileClass.php');
	include_once($rootlocation.'PresenterClass.php');
	require_once($rootlocation."login/VisitorDataClass.php");
	include_once($rootlocation."login/Initiatives/proposal.php");
	
	
	$lf = new logFile();
	
	
	/* Connect to the MySQL server and select database. */
	$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
	$mysqldb->connect();
	$mysqldb->select();

		
	$sp = new sessionPageBrowser($regionandheaderfull,$upriv['anyowner'],$homelandingurlfull[$utype['resident']]);
	$lf->logMessage("iForm.php: root : ".$rootlocation."\n");
		
	$if = new iForm("selectinitiative.htm", $lf, $mysqldb);
	$if->setWebsite($nameid);
	$if->setFormName('BMA Proposals');
	$if->setEditCallBackPgm($homelocationpart.$homelocationsub.'login/Initiatives/proposalfrominitiative.php');
	$if->setSuccessCallBackPgm($homelandingurlfull[$utype['resident']]);	

	if (! isset($_POST['submitbutton'])){
	
		$lf->logMessage("proposalfrominitiative.php: Displaying form for first time\n");
		
		$if->setInitiativeNameSelectionBox("initiatives", 0);
	
		$sp->addBodyText($if->renderFormText());
		$sp->renderPage();
		exit(0);
	
	} else {
				
		if ( ($iid = $if->getInitiativeIDFromSelectionBox()) == 0 ) {
			$this->setSimpleErrorText("inititives", "must be set");
			$sp->addBodyText($if->renderFormText());
			$sp->renderPage();
			exit(0);		
		}
		
		//echo "<p>proposalfrominitiative: iid: ".$iid."</p>";
		
		$ptype = "Initiative Proposal";
		
		// now bounce to the proposal page using the iid
		header('Location: '.$homelocationpart.$homelocationsub."login/Initiatives/pForm.php?iid=".$iid."&ptype=".$ptype);				
		//header('Location: '.$homelocationpart.$homelocationsub."login/Initiatives/pForm.php");				

		exit(0);
		
	}
	
	exit(0);
?> 
