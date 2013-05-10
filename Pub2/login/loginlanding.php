<?php 
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation."PresenterClass.php");
	include_once($rootlocation."logFileClass.php");
	include_once($rootlocation."logFileClass.php");
	
	$lf = new logFile();
	
	$sp = new sessionPageBrowser($rootlocation."RegionAndHeader.htm", $upriv['anyowner'],$homelandingurlfull[$utype['resident']]);	
	$sp->addHeader($rootlocation."HeaderContent.htm");	
	
	switch($_SESSION['u_priv']) {
	case 1:
	case 2:
		$sp->addBodyContent($rootlocation.$staticcontentloc."loginlandingbodylt3.htm");
		break;
		
	case 3:
	case 4:
	case 5:
	case 6:
		$sp->addBodyContent($rootlocation.$staticcontentloc."loginlandingbodygt2.htm");
		break;
	
	default:
		$lf->setLogFileName($lfname);
		$lf->logMessage("userpriv undefined: Error in ProcessLogin.php\n");
		session_unset();
		session_destroy();
		header( 'Location: '.$homelocationpart.$homelocationsub.'index.php');
}

		

	
		
	
	$sp->renderPage();
		
	
?>
