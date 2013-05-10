<?php 
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation.'logFileClass.php');
	include_once($rootlocation."PresenterClass.php");	
	include("preload_messages.php");
	
	$lf = new logFile();
	$lf->logMessage("news.php: Started\n");
	$sp = new sessionPageBrowser("bma_RegionAndHeader.htm",$upriv['anyowner'],$homelandingurlfull[$utype['resident']]);
	//$sp->setNoMenu();	



	$sp->addHiddenValue("u_id",$sp->getUserID());

	
	$sp->addPageTitleContent("newspagetitle.htm");
	//$sp->addBodyLeftContent("newsBody.htm");
	
	$sp->addHeadContent("headcontent1.htm");
	
	$text = preload_messages($lf);
	$lf->logMessage($text);

	//$lf->logMessage(preload_messages($lf));

	$sp->addBodyContent("bodyform.htm");

	$sp->renderPage();
?>
