<?php
  	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation."PresenterClass.php");
	include_once($rootlocation."logFileClass.php");
	require_once($rootlocation."login/validateLoginInput.php");

	session_start();

	$lf = new logFile();
	$lf->logMessage("login.php: starting\n");
	
	$lf->logMessage("login.php: Checking cookies, username: ".$_COOKIE['username']."\n");
	ob_start();
	print_r($_COOKIE);	
	$buffer .= ob_get_contents();
	ob_end_clean();
	$lf->logMessage("login.php: retrieved cookies: ".$buffer."\n");



	if (isset($_COOKIE['username']) && isset($_COOKIE['password']) ) {
	
		$lf->logMessage("login.php: Cookies are set, no login required\n");
	
		//no input required

		if (validateLoginInput($lf, $_COOKIE['username'], $_COOKIE['password'])) {
			//authenticated successfully									
			header('Location: '.$homelandingurlfull[$_SESSION['u_type']]);				
			exit();
		} else {
			$lf->logMessage("login.php: Authentication using saved cookies failed, deleting cookies from machine\n");
			//authentication failed
			/* Cookie expires when browser closes */
		    setcookie('username', "", time()-3600, '/', $cookiedomain);
		    setcookie('password', "", time()-3600, '/', $cookiedomain);
		    setcookie('errorstatus', 'Saved authentication incorrect. Exit Browser and try again.', time()+60*60*24*365, '/', $cookiedomain);

		    //setcookie('username', "", time()-3600);
		    //setcookie('password', "", time()-3600);
		    //setcookie('errorstatus', 'Saved authentication invalid. Exit Browser and try again.', time()+60*60*24*365);

			session_unset();
	
			session_destroy();

			header( 'Location: '.$homelocationpart.$homelocationsub.'login/login.php?error=y');
			exit();
		}
		
	} else {
	
		$lf->logMessage("login.php: Cookies not set, must ask for login info.\n");

		$gp = new generalPageBrowser($regionandheaderfull);	
	
		if(isset($_GET['error'])) {
			$lf->logMessage("login.php: User not found in database.\n");
			//$gp->setCookieErrorStatus("User not found");
		}
	
		$lf->logMessage("login.php: Displaying login page\n");	
		$gp->addHeader($headercontent);
	
		$gp->setFormAction($homelocationpart.$homelocationsub."login/processLogin.php");
		$gp->addBodyContent($loginbody);
	
		
		$gp->setLoginStatusMessage("Logged Out");
			
		if($gp->loginError()) {
			$gp->setStatusBarMessage("Please enter the correct username and password.");
		}
	
		$gp->renderPage();
	}
?>
