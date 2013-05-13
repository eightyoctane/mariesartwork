<?php 
	session_start();
	
	include_once($_SERVER['DOCUMENT_ROOT']."/homedata.php");
	include_once($rootlocation."PresenterClass.php");
	include_once($rootlocation."logFileClass.php");
	include_once($rootlocation."login/sessionSetup.php");
	include_once($rootlocation."logFileClass.php");
	
	$lf = new logFile();
	
	$sp = new sessionPageBrowser($rootlocation."RegionAndHeader.htm");	
	$sp->addHeader($rootlocation."HeaderContent.htm");	
	$sp->addMenu($rootlocation."login/MenuContentAtLogin.htm");

	sessionSetup($sp,$lf,1,1,$rootlocation."login/loginlanding.php");
	$_SESSION['prevpage'] = $homelocationpart.$homelocationsub."login/loginlanding.php";
	
	switch($_SESSION['u_priv']) {
	case 1:
	case 2:
		$sp->addBodyContent($rootlocation."login/htmlfiles/loginlandingbodylt3.htm");
		break;
		
	case 3:
	case 4:
	case 5:
		$sp->addBodyContent($rootlocation."login/htmlfiles/loginlandingbodygt2.htm");
		break;
	case 6:
		$sp->addBodyContent($rootlocation."login/htmlfiles/loginlandingbodygt5.htm");
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
