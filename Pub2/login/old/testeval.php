<?php

	session_start();
	include_once("sPageClass.php");
	include_once("sessionSetup.php");
	include_once($_SERVER['DOCUMENT_ROOT'].'/logFileClass.php');
	$lf = new logFile();
	$sp = new sPage("../bma_RegionAndHeader.htm");
	sessionSetup($sp,$lf,2,6,"admin.php");
			
	$sp->addMenu("porMenu.html");	
	$_SESSION['prevpage'] = "admin.php";
	
	$text = evaltest("hello there","testpage.htm");

	$sp->addBodyText($text);
	$sp->renderSessionPage();



function evaltest($field, $htmlpagefilename) {
	ob_start();
	//$str = file_get_contents($htmlpagefilename);
	$str = "hello there".$field;
	echo($str);
	$buffer = ob_get_contents();

	return ($buffer);
}

?>

