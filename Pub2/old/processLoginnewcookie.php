<?php

	//Starts a new session.
	session_start();
	
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation."login/mysqlClass.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/dbdata.php");
	include_once($rootlocation."logFileClass.php");
	//include_once($rootlocation."login/libfunctions.php");
	
	//include_once($rootlocation."login/connect.php");
	
	$lf = new logFile();
	
	$lf->logMessage("processLogin.php: From directory ".$rootlocation."login/\n");

	//Checks to see where a username has already been set for the session variable UserName. If not then check
	//if the submit button has been pushed. If it has then get the values from the html form and strip them of
	//any unwanted characters. Put them into a query and use mysql_real_escape_string to stop sql injections. This
	//is for security reasons.
	if (!isset($_SESSION['UserName'])) {

		//echo("session UserName not set yet");
		$lf->logMessage("processLogin: Not yet logged in.\n");

		if(isset($_POST['submitButton'])) {
		
		
			//Make the connection to the database	
			//$lf->logMessage("processLogin: Opening database: ".$system." ".$dbuser." ".$dbpwd." ".$database);
			$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
			$mysqldb->connect();
			$mysqldb->select();
	
			$uname = $_POST['userid'];
			$pwd = $_POST['password'];
			
	        if (isset($_POST['rememberme'])) {
	            /* Set cookie to last 1 year */
	            $lf->logMessage("processLogin.php: Setting cookie\n");
	            setcookie('username', $_POST['username'], time()+60*60*24*365, '/account', 'www.bluemountainairpark.com');
	            setcookie('password', md5($_POST['password']), time()+60*60*24*365, '/account', 'www.bluemountainairpark.com');
	        
	        } else {
	            /* Cookie expires when browser closes */
	            setcookie('username', $_POST['username'], false, '/account', 'www.bluemountainairpark.com.com');
	            setcookie('password', md5($_POST['password']), false, '/account', 'www.bluemountainairpark.com');
	        }			
			
	        //Now let us look for the user in the database.
	        //$query = sprintf("SELECT name, username, u_type, pwd FROM user WHERE username = '$uname' AND Password = '$pwd'",
	        //mysql_real_escape_string($uname), mysql_real_escape_string($pwd));

			$query = "SELECT rowID, name, username, u_type, u_priv, start_date, pwd, login_type FROM user
				WHERE username= '$uname' AND
				pwd=MD5('$pwd') AND
				login_type= '$utabletype'";
	        
	        try {
	        	$mysqldb->query($query);
	        } catch (Exception $e) {
	        	echo 'Caught exception: ',  $e->getMessage(), "\n";
	        	//nothing we can do (would have to put in a cookie
	        	//go to home page, and send error there
	        	//TODO
			}
        
			$rows = $mysqldb->numRows();
		
	        if ($rows != 1) {
				
				//echo "number of rows not equal to 1";
	        	header( 'Location: '.$homelocationpart.$homelocationsub.'login/login.php?error=y');
	        
	        } else {
	        	
	        	//Else if there was only one result returned then set that username value to the sessions value and take
	        	//the user to the secret page.        
        		
        		unset($_SESSION['submit']);
        		unset($_SESSION['user']);
        		
        		$_SESSION['u_id'] = $mysqldb->getItem("rowID");
				
				$name = $mysqldb->getItem("name");
				$username = $mysqldb->getItem("username");
	        	$_SESSION['Name'] = $name;
	        	$_SESSION['UserName'] = $username;
	        	
				$u_type = $mysqldb->getItem("u_type");
				$u_priv = $mysqldb->getItem("u_priv");
				$_SESSION['u_type'] = $u_type;
				$_SESSION['u_priv'] = $u_priv;
				
				$lf->logMessage("processLogin.php: User Name: ".$_SESSION['UserName']." User ID: ".$_SESSION['u_id']." logged in.\n");
				$lf->logMessage("processLogin.php: u_type: ".$_SESSION['u_type']." u_priv:".$_SESSION['u_priv']."\n");
				
				$_SESSION['loginstatemessage'] = "$name logged in as $username";
							
				$_SESSION['homepage'] = $rootlocation.'index.php';
				
				$lfname = $lf->getLogFileName();
				$lf->setLogFileName($rootlocation.$logintracking);				
				$now_date = date("D M j G:i:s T Y");
				$lf->logMessage('processLogin.php: '.$name.'/'.$username.' user type '.$u_type.', user priv '.$u_priv." logged in on ".$now_date."\n");
				$lf->setLogFileName($lfname);				
				
				
				$_SESSION[loginstatemessage] .= " ".$utypemsg[$u_type]." ".$uprivmsg[$u_priv];
				
				//$lf->logMessage("processLogin.php: landingpath: ".$homelandingurlfull[$u_type]."\n");
				
				header('Location: '.$homelandingurlfull[$u_type]);
				exit(0);

			}
		}	
	//If you already have a logged in you will get this message.
	} else {
		echo "You are already logged in! <br /> <a href='logout.php'>Click here</a> to log out";
		$lf->logMessage('Already logged in event for '.$name.'/'.$username.' user type '.$u_type.' \n');
	}
?>

