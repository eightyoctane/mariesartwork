<?php 
	session_start();
	include_once("sPageClass.php");
	include_once("sessionSetup.php");
	include_once($_SERVER['DOCUMENT_ROOT'].'/logFileClass.php');
	$lf = new logFile();
	$sp = new sPage("../bma_RegionAndHeader.htm");
	
	sessionSetup($sp,$lf,2,4,"por.php");	
	$sp->addMenu("porMenu.html");		
	$sp->addBodyContent("htmlfiles/boardBody.htm");
	$sp->renderSessionPage();
?>
