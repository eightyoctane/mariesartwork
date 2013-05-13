<?php 
	session_start();
	include_once("sPageClass.php");
	include_once("sessionSetup.php");
	include_once($_SERVER['DOCUMENT_ROOT'].'/logFileClass.php');
	include_once("rFormClass.php");
	
	$lf = new logFile();
	$sp = new sPage("../bma_RegionAndHeader.htm");
	sessionSetup($sp,$lf,2,1,"por.php");
			
	$sp->addMenu("porMenu.html");	
	$_SESSION['prevpage'] = $_SESSION['homepage'];
	
	$f = new Form("htmlfiles/CreateDocumentBody.htm");
	$f->setWebsite('http://www.bluemountainairpark.com');
	$f->setFormName('BMA Document Editor');
	$f->setEditCallBackPgm('createDocument.php');
	
	if (! isset($_POST['submitbutton'])){

		$text = $f->renderFormText();
		$sp->addBodyText($text);
		$sp->renderSessionPage();
	} else {
	
		echo "Document collected.";
		sleep(15);
		header( 'Location: http://www.bluemountainairpark.com/login/por.php');
	}
?>
