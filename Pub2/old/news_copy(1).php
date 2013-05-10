<?php 
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation."PresenterClass.php");


	include_once($rootlocation."login/sessionSetup.php");
	include_once($rootlocation."logFileClass.php");
	
	$lf = new logFile();

	$sp = new sessionPageBrowser($rootlocation."bma_RegionAndHeader.htm");	
	
	sessionSetup($sp,$lf,$_SESSION['u_type'],$_SESSION['u_priv'],$_SESSION['homepage']);
	
	$_SESSION['prevpage'] = 'por.php';		
	
	$sp->addMenu($rootlocation."login/porMenu.html");				
	
	$sp->addBodyContent($rootlocation.$staticcontentloc."newsBody.htm");
	

	$sp->renderPage();
?>
