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
	$lf->logMessage("voteDB.php: Constructing vote table\n");
			
	
	$lf->logMessage("voteDB.php: opening database\n");
		
	/* Connect to the MySQL server and select database. */
	$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
	$mysqldb->connect();
	$mysqldb->select();
	
	/*
	This is a table of vote results.
	Each time we start a new vote, we make a new entry in this table.
	This table is associated with an initiative table or a proposal table.
	It is also associated with a voterlist table which contains a list of
	the allowed voters for the vote, and details for each one of their votes.
	
	Regarding the vote entry,
	1. voteid - is a unique id produced automatically when we create a new entry
	2. vstatus - is the status of the vote, open, closed
	3. vtablename - is the name of the voterlist table ("voter+initiative|proposal+descriptivename+voteid")
	4. vdesc - name of the initiative or proposal varchar(40)
	5. iorpid - initiative or proposal id smallint(5) unsigned not null
	6. votetype - initiative or proposal varchar(20)

	*/
	$query = "CREATE TABLE IF NOT EXISTS vote (
				voteid SMALLINT(5) unsigned not null primary key auto_increment,
				vstatus VARCHAR(10) not null,
				vtablename VARCHAR(80) not null,
				vdesc VARCHAR(160) not null,
				iorpid SMALLINT(5) unsigned not null,
				votetype VARCHAR(20) not null
				)";
						
	$mysqldb->query($query);

	$result = $mysqldb->getResult();
	//echo "<p>result = $result</p>";
	
	//$pdate = date('Y-m-d',$start_date);
	
	if(! $result) {
		$msg = "vote table creation failed";
	} else {
		$msg = "vote table creation succeeded";
	}
		
		
	$lf->logMessage($msg."\n");
	$sp->addBodyText($msg);
	$sp->renderPage();
	exit(0);
?>
