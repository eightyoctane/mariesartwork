<?php 
	session_start();
	
	

	
	/* 
	This page for projects privilege at the website anyone who's logged in can get here
	*/
	include_once("sPageClass.php");
	include_once("sessionSetup.php");
	include_once("../logFileClass.php");
	$lf = new logFile();
	$sp = new sPage("../bma_RegionAndHeader.htm");
	sessionSetup($sp,$lf,$_SESSION['u_type'],$_SESSION['u_priv'],$_SESSION['homepage']);		
	$sp->addMenu("porMenu.html");	
	$_SESSION['prevpage'] = $_SESSION['homepage'];	
	$sp->addBodyContent("htmlfiles/linksBody.htm");
	$sp->renderSessionPage();
?>
