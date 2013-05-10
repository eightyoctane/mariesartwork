<?php

	//Starts a new session.
	session_start();
	
	require_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	require_once($rootlocation."logFileClass.php");	
	require_once($rootlocation."login/validateLoginInput.php");

	$lf = new logFile();
	
	$lf->logMessage("processLogin.php: From directory ".$rootlocation."login/\n");

	if (!isset($_SESSION['UserName'])) {
		$lf->logMessage("processLogin: Good, not yet logged in. Checking submit button...\n");

		if(isset($_POST['submitButton'])) {
		
			$lf->logMessage("processLogin: Submit button was set...\n");
		
			$uname = $_POST['userid'];
			
			//REVISITuse mysql_real_escape_string to stop sql injections. This
			//is for security reasons.
			
			$pwd = MD5($_POST['password']);
					
			if (validateLoginInput($lf, $uname, $pwd)) {
			
				$lf->logMessage("processLogin.php: Authentication successful.\n");
				
				//authenticated successfully			
		        if (isset($_POST['rememberme'])) {
		            /* Set cookie to last 1 year */
		            $lf->logMessage("processLogin.php: Remember me set, setting cookie to username: ".$uname." for domain: $cookiedomain\n");
		            
		            if(!setcookie('username', $uname, time()+60*60*24*365, '/', $cookiedomain)) echo "setcookie failed";
		            if(!setcookie('password', $pwd, time()+60*60*24*365, '/', $cookiedomain)) echo "setcookie failed";
		            
		            //if(!setcookie('username', $uname, time()+60*60*24*365)) echo "setcookie failed";
		            //if(!setcookie('password', $pwd, time()+60*60*24*365)) echo "setcookie failed";

		        
		        } else {
		        	$lf->logMessage("processLogin.php: Remember me not set, so clearing login cookies for domain: $cookiedomain\n");
		            /* Cookie expires when browser closes */
		            setcookie('username', "", 0, '/', $cookiedomain);
		            setcookie('password', "", 0, '/', $cookiedomain);
		            
		            //setcookie('username', "", 0);
		            //setcookie('password', "", 0);
		            
		            
		        }
		        $lf->logMessage("processLogin.php: User type: ".$_SESSION['u_type']." Going to: ".$homelandingurlfull[$_SESSION['u_type']]."\n");													
				header('Location: '.$homelandingurlfull[$_SESSION['u_type']]);				
				exit();
			
			} else {
			
				$lf->logMessage("processLogn.php: Authentication Failed.\n");
				//authentication failed
				session_unset();	
				session_destroy();
				header( 'Location: '.$homelocationpart.$homelocationsub.'login/login.php?error=y');
				exit();
			}
				

		} else {
			$lf->logMessage("processLogin: Error, no submitbutton.\n");
			exit();
		}
			
	//If you already have a logged in you will get this message.
	} else {
		echo "You are already logged in! <br /> <a href='logout.php'>Click here</a> to log out";
		$lf->logMessage('processLogin.php: Error, Already logged in event for '.$name.'/'.$username.' user type '.$u_type.' \n');
	}
?>

