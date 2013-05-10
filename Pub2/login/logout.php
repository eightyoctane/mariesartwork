<?php

	//Simply logs the user out and takes them back to the index page.
	session_start();

	require_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	require_once($rootlocation.'logFileClass.php');
	
	$lf = new logFile();
	$lf->logMessage("logout.php: Started, going to: ".$homelandingurlfull[$utype['nobody']]."\n");
	
	//Simply logs the user out and takes them back to the index page.
	$now_date = date("D M j G:i:s T Y");
	$lf->logMessage("logout.php: ".$_SESSION['Name'].'/'.$_SESSION['UserName']." logged out on ".$now_date."\n");



	session_unset();
	
	session_destroy();
	
	header( 'Location: '.$homelandingurlfull[$utype['nobody']]);
?>
>
