<?php 
	session_start();

	/* 
	Any logged in user can get this far.
	As a registered visitor, you are in
	session and get immediate
	access to registered users pages.
	*/
	include_once($_SERVER['DOCUMENT_ROOT']."/OSA/login/sPageClass.php");	
	include_once($_SERVER['DOCUMENT_ROOT']."/OSA/login/sessionSetup.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/OSA/logFileClass.php");
	$lf = new logFile();
	$sp = new sPage($_SERVER['DOCUMENT_ROOT']."/OSA/bma_RegionAndHeader.htm");
	sessionSetup($sp,$lf,1,1,$_SESSION['homepage']);	
		
	$sp->addMenu($_SERVER['DOCUMENT_ROOT']."/OSA/homepageMenuL.html");
	
	$_SESSION['prevpage'] = "http://www.bluemountainairpark.com/OSA/login/bmarv.php";
	$_SESSION['nextpage'] = "http://www.bluemountainairpark.com/OSA/login/bmarv.php";
	$sp->renderSessionPage();
			
	
?>
