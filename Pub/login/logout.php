<?php

	include_once($_SERVER['DOCUMENT_ROOT']."/homedata.php");
	include_once($rootlocation."logFileClass.php");

	$lf = new logFile();
	$lf->setLogFileName($rootlocation.$logintracking);

	//Simply logs the user out and takes them back to the index page.
	session_start();
	$now_date = date("D M j G:i:s T Y");
	$lf->logMessage("logout.php: ".$_SESSION['Name'].'/'.$_SESSION['UserName']." logged out on ".$now_date."\n");
	
	session_unset();
	
	session_destroy();
	
	header('Location: '.$homelocationpart.$homelocationsub.'index.php');
?>
>
