<?php 
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT'].'/login/'."sPageClass.php");
	include_once($_SERVER['DOCUMENT_ROOT'].'/login/'."sessionSetup.php");
	include_once($_SERVER['DOCUMENT_ROOT'].'/'."logFileClass.php");
	include_once($_SERVER['DOCUMENT_ROOT'].'/login/dirlisting/'."dirlisting.php");

	$lf = new logFile();
	$sp = new sPage($_SERVER['DOCUMENT_ROOT'].'/'."bma_RegionAndHeader.htm");
	sessionSetup($sp,$lf,2,1,$_SERVER['DOCUMENT_ROOT'].'/login/'."admin.php");
			
	$sp->addMenu($_SERVER['DOCUMENT_ROOT'].'/login/'."porMenu.html");	
	$_SESSION['prevpage'] = $_SESSION['homepage'];
	
	$text = dirlisting($lf);
	
	$sp->addBodyText($text);
	$sp->renderSessionPage();
?>
