<?php

	//Starts a new session.
	session_start();
	
	include_once($_SERVER['DOCUMENT_ROOT']."/homedata.php");
	include_once($rootlocation."login/mysqlClass.php");
	include_once($rootlocation."login/dbdata.php");
	include_once($rootlocation."logFileClass.php");
	include_once($rootlocation."login/connect.php");
	include_once($rootlocation."login/libfunctions.php");

	
	$lf = new logFile();
	
	//Make the connection to the database



	//Checks to see where a username has already been set for the session variable UserName. If not then check
	//if the submit button has been pushed. If it has then get the values from the html form and strip them of
	//any unwanted characters. Put them into a query and use mysql_real_escape_string to stop sql injections. This
	//is for security reasons.
	if (isset($_SESSION['UserName'])) {

		echo "You are already logged in! <br /> <a href='logout.php'>Click here</a> to log out";
		$lf->logMessage('Already logged in event for '.$name.'/'.$username.' user type '.$u_type.' \n');
		$msg = "Logging user out";
		session_unset();
		session_destroy();
		header('Location: '.$homelocationpart.$homelocationsub.'resetpassword.php?errmessage='.$msg);

	} else {

		//echo("session UserName not set yet");

		if(isset($_POST['submitButton'])) {
			//echo("submitButton received");
			//echo("userid:  ");
			//echo $_POST['userid'];
			//echo("pwd:  ");
			//echo $_POST['password'];

	        //$uname = strip_tags($_POST['userid']);
	        //$pwd = md5(strip_tags($_POST['password']));
			$uname = $_POST['userid'];
			$pwd = $_POST['password'];

	        //Now let us look for the user in the database.
	        //$query = sprintf("SELECT name, username, u_type, pwd FROM user WHERE username = '$uname' AND Password = '$pwd'",
	        //mysql_real_escape_string($uname), mysql_real_escape_string($pwd));
			$query = "SELECT name, username, u_type, u_priv, start_date, pwd, login_type FROM user
				WHERE username= '$uname' AND
				pwd=MD5('$pwd') AND
				login_type= 'artmarie'";
	        
	        $result = mysql_query($query);
	        //$row = mysql_fetch_assoc($result);
	        
	        //An if statment which see if the the result returned more than 1 result. If it did return more 
	        //than one or none at all, then take use back to the login.php and give the html an error message.
	        
		$rows = mysql_num_rows($result);
		//echo "    $rows";
	        if (mysql_num_rows($result) != 1) {
			//echo "number of rows not equal to 1";
	        	header( 'Location: '.$homelocationpart.$homelocationsub.'login/login.php?error=y');
	        //Else if there was only one result returned then set that username value to the sessions value and take
	        //the user to the secret page.
	        } else {
        		unset($_SESSION['submit']);
        		unset($_SESSION['user']);
			$name = mysql_result($result, 0, "name");
			$username = mysql_result($result, 0, "username");
        		//$_SESSION['UserName'] = $row['uname'];
        	$_SESSION['UserName'] = $username;
			$u_type = mysql_result($result, 0, "u_type");
			$u_priv = mysql_result($result, 0, "u_priv");
			$_SESSION['u_type'] = $u_type;
			$_SESSION['u_priv'] = $u_priv;
			
			$lf->logMessage("processLogin.php: ".$_SESSION['UserName']." logged in.\n");
			$lf->logMessage("processLogin.php: u_priv:".$_SESSION['u_priv']."\n");
			
			//$name = $row['uname'];
			//$username = $row['username'];
			//$u_type = $row['u_type'];
			$_SESSION['loginstatemessage'] = "$name logged in as $username";
			
			
			$_Session['homepage'] = $homelocationpart.'index.php';
			
			$lf->getLogFileName($lfname);
			$lf->setLogFileName($rootlocation.'login/maLoginTracking.txt');
			
			$usertypemessage = getUserTypeMessage($u_type);
			$addpriv = getUserPrivMessage($u_priv);
			
				
			switch($u_type) {
				case 1:
				case 2:
					$_SESSION[loginstatemessage] = $usertypemessage;
					$lf->logMessage('Logged in: '.$name.'/'.$username.' user type '.$u_type." \n");
					header('Location: '.$homelocationpart.$homelocationsub.'login/loginlanding.php');
					break;
				
				default:
					$lf->setLogFileName($lfname);
					$lf->logMessage("usertype undefined: Error in ProcessLogin.php\n");
					session_unset();
					session_destroy();
					header( 'Location: '.$homelocationpart.$homelocationsub.'index.php');
			}
		}
		}
			
		//Close the mysql connection.
		mysql_close($conn);
} 	
?>

