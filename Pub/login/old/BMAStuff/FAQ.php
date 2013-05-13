<?php 
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT'].'/login/'."sPageClass.php");
	include_once($_SERVER['DOCUMENT_ROOT'].'/login/'."sessionSetup.php");
	include_once($_SERVER['DOCUMENT_ROOT'].'/'."logFileClass.php");
	$lf = new logFile();
	$sp = new sPage("../bma_RegionAndHeader.htm");
	sessionSetup($sp,$lf,2,1,"por.php");
			
	$sp->addMenu("porMenu.html");	
	$_SESSION['prevpage'] = $_SESSION['homepage'];	
	$sp->addBodyContent("htmlfiles/FAQBody.htm");
	$sp->renderSessionPage();
?>
