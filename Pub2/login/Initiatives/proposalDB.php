<?php

	require_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	
	include_once($rootlocation."login/mysqlClass.php");
	//include_once($rootlocation."login/rFormClass.php");
	include_once($rootlocation."dbdata.php");
	include_once($rootlocation.'logFileClass.php');
	include_once($rootlocation.'PresenterClass.php');
	require_once($rootlocation."login/VisitorDataClass.php");
	
	
	$lf = new logFile();
		
	$sp = new sessionPageBrowser($regionandheaderfull,$upriv['webadmin'],$homelandingurlfull[$utype['resident']]);
	$lf->logMessage("proposalDB.php: Constructing proposals table\n");
			
	
	$lf->logMessage("proposalDB.php: opening database\n");
		
	/* Connect to the MySQL server and select database. */
	$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
	$mysqldb->connect();
	$mysqldb->select();
	
	/*
	pid - proposal id autoincrement
	iid - initiative id
	pname - pname name (obtained from screen)
	pstatus - open, accepted, rejected
	shortdesc - one liner description  (obtained from screen)
	submittedbynm - member name who submitted 	
	submittedbyid - member id who submitted 	
	datetimeopened - the unix time that this initiative was opened
					to get the human readable time in php: 
					echo time(); //unix time
					echo date('r', time() ); // Local time for humans
	*/

	
	$query = "CREATE TABLE IF NOT EXISTS proposals (
				pid SMALLINT(5) unsigned not null primary key auto_increment,
				iid SMALLINT(5) unsigned not null,
				pstatus VARCHAR(10) not null,
				pname VARCHAR(40) not null,
				shortdesc VARCHAR(160) not null,
				submittedbynm VARCHAR(40) not null,
				submittedbyid SMALLINT(5) not null,
				voteid SMALLINT(5) not null,
				duration SMALLINT(5) not null,
				descname VARCHAR(20) not null
				)";
						
	$mysqldb->query($query);

	$result = $mysqldb->getResult();
	//echo "<p>result = $result</p>";
	
	//$pdate = date('Y-m-d',$start_date);
	
	if(! $result) {
		$msg = "proposal table creation failed";
	} else {
		$msg = "proposal table creation succeeded";
	}
		
		
	$lf->logMessage($msg."\n");
	$sp->addBodyText($msg);
	$sp->renderPage();
	exit(0);
?>
