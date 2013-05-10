<?php 
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation.'logFileClass.php');
	include_once($rootlocation."PresenterClass.php");	


	$lf = new logFile();
	$lf->logMessage("airparkWall.php: Name: ".$_SESSION['UserName']." u_id: ".$_SESSION['u_id']."\n");
			
	//$sp = new sessionPageBrowser($rootlocation.'/login/facebook_WallScript_2.0/'."Content/bma_RegionSimple2.htm", 
	$sp = new sessionPageBrowser($regionandheadersplitfull, 
		$upriv['anyowner'],$homelandingurlfull[$utype['resident']]);

	include_once($rootlocation.'/login/facebook_WallScript_2.0/'."preload_msg_ajax.php");
	
	//$sp->addHiddenValue("u_id",$_SESSION['u_id']);
	$sp->addHiddenValue("u_id",$sp->getUserID());

	$sp->addHeader($rootlocation."login/facebook_WallScript_2.0/Content/headercontent.htm");
	
	//$sp->addMenu($homemenulistfull[$sp->getUsertype()]);			


	$sp->addHeadContent($rootlocation.'/login/facebook_WallScript_2.0/'."Content/headcontent.htm");
	
	$sp->addBodyContent($rootlocation."login/facebook_WallScript_2.0/Content/fb_bodytest1.htm");
	$sp->addBodyText(preload_msg_ajax($lf));
	
	//$sp->addBodyText("SimpleBody<br>");
	
	$sp->renderPage();
	
	
?>
