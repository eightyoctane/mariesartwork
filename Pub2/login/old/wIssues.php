<?php 
	session_start();
	include_once("sPageClass.php");
	include_once("sessionSetup.php");
	include_once("../logFileClass.php");
	$lf = new logFile();
	$sp = new sPage("../bma_RegionAndHeader.htm");
	sessionSetup($sp,$lf,$_SESSION['u_type'],$_SESSION['u_priv'],$_SESSION['homepage']);		
	$sp->addMenu("porMenu.html");	
	$_SESSION['prevpage'] = $_SESSION['homepage'];	
	$sp->addBodyContent("htmlfiles/wIssuesBody.htm");
	$sp->renderSessionPage();
?>
