<?php

	require_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	
	//require_once($rootlocation."login/libfunctions.php");
	include_once($rootlocation."login/mysqlClass.php");
	//include_once($rootlocation."login/rFormClass.php");
	include_once($rootlocation."dbdata.php");
	include_once($rootlocation.'logFileClass.php');
	include_once($rootlocation.'PresenterClass.php');
	require_once($rootlocation."login/VisitorDataClass.php");
	
	
	$lf = new logFile();
		
	$sp = new sessionPageBrowser($regionandheadersplitfull,$upriv['webadmin'],$homelandingurlfull[$utype['resident']]);
	$lf->logMessage("initiativeDB.php: Constructing initiatives table\n");
			
	
	$lf->logMessage("initiativeDB.php: opening database\n");
		
	/* Connect to the MySQL server and select database. */
	$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
	$mysqldb->connect();
	$mysqldb->select();
	
	/*
	iid - initiative id
	iname - initiative name (obtained from screen)
	istatus - open, closed, reopened, approved
	shortdesc - one liner description of the initiative (obtained from screen)
	abstract - detailed description of the initiative (obtained from screen)
	submittedbynm - member name who submitted initiative
	submittedbyid - member id who submitted initiative
	volunteerc - submitter volunteers to chair the committee
	chairrec - submitter recommends this person to chair the committee
	resdecide - residents decide if this is a good initiative
	voteid - hash of initiative name used for associated vote
	duration - number of days we have to vote
	treopened - the number of times this initiative has be reopened
	descname - single word identifier for this initiative
	proposalreq - description of minimal proposal requirements for proposals
					that go with this initiative
	datetimeopened - the unix time that this initiative was opened
					to get the human readable time in php: 
					echo time(); //unix time
					echo date('r', time() ); // Local time for humans
	datetimereopened  - the unix time that this inititive was reopened
	*/

	
	$query = "CREATE TABLE IF NOT EXISTS initiatives (
				iid SMALLINT(5) unsigned not null primary key auto_increment,
				istatus VARCHAR(10) not null,
				iname VARCHAR(40) not null,
				shortdesc VARCHAR(160) not null,
				abstract VARCHAR(2000) not null,
				submittedbynm VARCHAR(40) not null,
				submittedbyid SMALLINT(5) not null,
				volunteerc TINYINT(4) not null,
				chairrec VARCHAR(40) not null,
				resdecide TINYINT(4) not null,
				voteid SMALLINT(5) not null,
				istatus VARCHAR(10) not null,
				duration SMALLINT(5) not null,
				treopened SMALLINT(5) not null,
				whoreopened VARCHAR(40) not null,
				descname VARCHAR(20) not null,
				proposalreq VARCHAR(400) not null,
				datetimeopened INT(10) unsigned,
				datetimereopened INT(10) unsigned
				)";
						
	$mysqldb->query($query);

	$result = $mysqldb->getResult();
	//echo "<p>result = $result</p>";
	
	//$pdate = date('Y-m-d',$start_date);
	
	if(! $result) {
		$msg = "initiatives table creation failed";
	} else {
		$msg = "initiatives table creation succeeded";
	}
		
		
	$lf->logMessage($msg."\n");
	$sp->addBodyText($msg);
	$sp->renderPage();
	exit(0);
?>
