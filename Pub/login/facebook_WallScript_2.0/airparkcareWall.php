<?php 
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT'].'/login/'."sPageClassNew.php");
	include_once($_SERVER['DOCUMENT_ROOT'].'/login/'."sessionSetup.php");
	include_once($_SERVER['DOCUMENT_ROOT'].'/'."logFileClass.php");
	include_once($_SERVER['DOCUMENT_ROOT'].'/login/facebook_WallScript_2.0/'."facebookwall.php");

	$lf = new logFile();
	$sp = new sPage($_SERVER['DOCUMENT_ROOT'].'/'."bma_PageTemplate.htm");
	sessionSetup($sp,$lf,2,1,$_SERVER['DOCUMENT_ROOT'].'/login/'."admin.php");
			
	$sp->addHeadContent($_SERVER['DOCUMENT_ROOT'].'/login/facebook_WallScript_2.0/'."headcontent.htm");
	$sp->addRegionContent($_SERVER['DOCUMENT_ROOT'].'/'."bma_Region.htm");
	$sp->addMenu($_SERVER['DOCUMENT_ROOT'].'/login/'."porMenu.html");
	$sp->renderRegionContent();
	
	
	//$sp->addHTMLText($_SERVER['DOCUMENT_ROOT'].'/'."bma_RegionAndHeaderTest2.htm");
	
	$_SESSION['prevpage'] = $_SESSION['homepage'];
	
	$lf->logMessage("In airparkWall.php\n");
	//$sp->addBodyText('<p>airparkcarepage</p>');
	$text = facebookwall($lf);
	$sp->addBodyText("$text");
	//$sp->addBodyText("</html>");
	$sp->renderSessionPage();
?>
