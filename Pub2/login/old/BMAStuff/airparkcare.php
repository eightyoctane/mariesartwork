<?php 
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT'].'/login/'."sPageClass.php");
	include_once($_SERVER['DOCUMENT_ROOT'].'/login/'."sessionSetup.php");
	include_once($_SERVER['DOCUMENT_ROOT'].'/logFileClass.php');
	$lf = new logFile();
	$sp = new sPage($_SERVER['DOCUMENT_ROOT'].'/'."bma_RegionAndHeader.htm");
	sessionSetup($sp,$lf,2,1,$_SERVER['DOCUMENT_ROOT'].'/login/'."por.php");
			
	$sp->addMenu($_SERVER['DOCUMENT_ROOT'].'/login/'."porMenu.html");	
	$_SESSION['prevpage'] = $_SESSION['homepage'];
	//chdir('facebook_WallScript_2.0');	
	$sp->addBodyContent($_SERVER['DOCUMENT_ROOT'].'/login/htmlfiles/airparkcarebody.htm');
	//$sp->addHtmlText($_SERVER['DOCUMENT_ROOT'].'/login/facebook_WallScript_2.0/'.facebookwall.php');
	//$sp->addHtmlText('facebookwall.php');
	$sp->renderSessionPage();
?>
