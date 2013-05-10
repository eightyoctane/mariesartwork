<?php 
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation.'logFileClass.php');
	include_once($rootlocation."PresenterClass.php");	


	$lf = new logFile();
		
	//$sp = new sessionPageBrowser($rootlocation.'/login/facebook_WallScript_2.0/'."Content/bma_RegionSimple.htm", 
	$sp = new sessionPageBrowser($rootlocation.'/login/facebook_WallScript_2.0/'."Content/bma_RegionAndHeader.htm", 
			$upriv['anyowner'],$homelandingurlfull[$utype['resident']]);

	include_once($rootlocation.'/login/facebook_WallScript_2.0/'."preload_msg_ajax.php");

	
	$lf->logMessage("airparkWall.php: Name: ".$_SESSION['UserName']." u_id: ".$sp->getUserID()."\n");

	$sp->addHiddenValue("u_id",$sp->getUserID());
	//$sp->addBodyContent($rootlocation."login/facebook_WallScript_2.0/Content/headercontent.htm");

	$sp->addHeadContent($rootlocation.'/login/facebook_WallScript_2.0/'."Content/headcontent.htm");
	
	$sp->addMenu($homemenulistfull[$sp->getUsertype()]);			

	$sp->addBodyContent($rootlocation."login/facebook_WallScript_2.0/Content/fb_bodytest.htm");
	$sp->addBodyText(preload_msg_ajax($lf));
	
	//$sp->addBodyText("SimpleBody<br>");
	
	$sp->renderPage();
?>