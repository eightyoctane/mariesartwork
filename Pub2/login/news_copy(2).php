<?php 
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/dbdata.php");
	include_once($rootlocation.'logFileClass.php');
	include_once($rootlocation."PresenterClass.php");
	include_once($rootlocation."login/mysqlClass.php");
	
	
	$lf = new logFile();
	$lf->logMessage("news.php: Started\n");
	$sp = new sessionPageBrowser($regionandheadersplitfull,$upriv['anyowner'],$homelandingurlfull[$utype['resident']]);
	//$sp->setNoMenu();	

	
	$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
	$mysqldb->connect();
	$mysqldb->select();

	// get my user name from the user database using the id associated with this session
	$myid = $sp->getUserID();
	$mysqldb->query("SELECT rowID, name, vid FROM user WHERE rowID='$myid'");
	$row = $mysqldb->fetchObject();
	$myname = $row->name;
	$vid = $row->vid;
	//echo "<p>*********voter id  ".$vid."</p>";


	include_once($rootlocation."login/preload_msg_ajax.php");

	$sp->addHiddenValue("u_id",$sp->getUserID());

	$isiPad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
	
	if ($isiPad) {	
		$sp->addPageTitleContent($rootlocation.$staticcontentloc."newspagetitle.htm");
		
		$sp->addHeadContent($rootlocation.$staticcontentloc."headcontent.htm");
		
		$sp->addBodyContent($rootlocation."login/fb_bodytest1.htm");
		$sp->addBodyText(preload_msg_ajax($lf));
		
	} else {	
		$sp->addPageTitleContent($rootlocation.$staticcontentloc."newspagetitle.htm");
		//$sp->addBodyContent($rootlocation.$staticcontentloc."newsBody.htm");
		
		$sp->addHeadContent($rootlocation.$staticcontentloc."headcontent.htm");
	
		//$sp->addBodyContent($rootlocation."login/Wall/maintenanceMessage.htm");
	
		$sp->addBodyContent($rootlocation."login/htmlfiles/photostablebody.htm");
		$sp->addBodyLeftContent($rootlocation."login/fb_bodytest1.htm");
		$sp->addBodyLeftText(preload_msg_ajax($lf));

	}

	
	

	$sp->renderPage();
?>
