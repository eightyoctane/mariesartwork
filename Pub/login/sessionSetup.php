<?php

//FIX should be inside class PresenterClass subclass session class

include_once($_SERVER['DOCUMENT_ROOT']."/homedata.php");

function sessionSetup($spage, $lf, $usertype, $minpriv, $prevpage) {
	
	if(isset($_SESSION['UserName'])) {
			
		if($_SESSION['u_type'] != $usertype) {
			
			$lf->logMessage("sessionSetup: User: ".$_SESSION['UserName']." has incorrect user type");
			
			$_SESSION['error'] = "Access to this area requires a different user type";
			$spage->setErrorStatus($_Session['error']);
			
			// Return to previous page
			header( 'Location: '.$prevpage);
		
		} else if($_SESSION['u_priv'] < $minpriv) {
			
			$lf->logMessage("User:".$_SESSION['UserName']." Access to this page requires higher privilege\n");
		
			$_SESSION['error'] = 'Access to this area requires higher privilege';
			$spage->setErrorStatus($_Session['error']);
			
			header( 'Location: '.$prevpage);
		
		} else {

			$spage->setLoginDetailsMessage($_SESSION['loginstatemessage']);
/*
			$spage->setErrorStatusBarMessage($_SESSION['error']);
			$_SESSION['error'] = '';
*/	
			return 1;
		}
	
	} else {
		
		$lf->logMessage("sessionSetup: Not in session but should be\n");
		session_unset();
		session_destroy();
		header( 'Location: '.$homelocationpart.$homelocationsub."login/login.php");
	}
}

?>