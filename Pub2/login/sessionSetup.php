<?php

//FIX should be inside class PresenterClass subclass session class



function sessionSetup($spage, $lf, $usertype, $minpriv, $prevpage) {
	
	if(isset($_SESSION['UserName'])) {
		
		$lf->logMessage("sessionSetup.php: Already in session. usertype requested: ".$usertype." minpriv: ".$minpriv."\n");	
		
		if($_SESSION['u_type'] != $usertype) {

			$lf->logMessage("sessionSetup: User type for active session: ".$_SESSION['UserName'].
			" doesn't match user type expected: ".$usertype."\n");
			
			$msg = "Access to this area requires a different user type";
			
			$lf->logMessage("sessionSetup.php: ".$msg."\n");
			
			$spage->setErrorStatus($msg);
			
			$lf->logMessage("sessionSetup.php: returning to previous page: ".$prevpage."\n");
			
			// Return to previous page
			header( 'Location: '.$prevpage);
		
		} else if($_SESSION['u_priv'] < $minpriv) {

			$msg = "Access to this page requires higher privilege";
			
			$lf->logMessage("sessionSetup.php: User:".$_SESSION['UserName']." ".$msg."\n");
		
			$spage->setErrorStatus($msg);
			
			header( 'Location: '.$prevpage);
		
		} else {

			$lf->logMessage("sessionSetup.php: already logged in and user passed minimum privledge requirements for page requested\n");

			$spage->setLoginDetailsMessage($_SESSION['loginstatemessage']);

			return 1;
		}
	
	} else {

		$lf->logMessage("sessionSetup: Not yet in session must log in first.\n");

		session_unset();
		session_destroy();
		//echo "going to login";
		$loc = 'Location: '.HOMELOCATIONURL."login/login.php";
		$lf->logMessage("sessionSetup: returning to: ".$loc."\n");
		header($loc);
	}
}

?>