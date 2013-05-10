<?php
function validateLoginInput ($lf, $uname, $pwd) {

	include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include($rootlocation."login/mysqlClass.php");
	include($_SERVER['DOCUMENT_ROOT']."/dbdata.php");


	//Make the connection to the database	
	//$lf->logMessage("validateLoginInput: Opening database: ".$system." ".$dbuser." ".$dbpwd." ".$database);
	$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
	$mysqldb->connect();
	$mysqldb->select();


    //Now let us look for the user in the database.
    //$query = sprintf("SELECT name, username, u_type, pwd FROM user WHERE username = '$uname' AND Password = '$pwd'",
    //mysql_real_escape_string($uname), mysql_real_escape_string($pwd));

	$query = "SELECT rowID, name, username, u_type, u_priv, start_date, pwd, login_type FROM user
		WHERE username= '$uname' AND
		pwd='$pwd' AND
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
		
		$lf->logMessage("validateLoginInput.php: Unique user not found, authentication failed.\n");
		
		return(0);
    
    } else {
    
    	$lf->logMessage("validateLoginInput.php: Unique user found...continuing...\n");
    	
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
		
		$lf->logMessage("validateLoginInput.php: User Name: ".$_SESSION['UserName']." User ID: ".$_SESSION['u_id']." logged in.\n");
		$lf->logMessage("validateLoginInput.php: u_type: ".$_SESSION['u_type']." u_priv:".$_SESSION['u_priv']."\n");
		
		$_SESSION['loginstatemessage'] = "$name logged in as $username";
					
		$_SESSION['homepage'] = $homelandingfull['$u_type'];
		
		$lfname = $lf->getLogFileName();
		$lf->setLogFileName($logintracking);				
		$now_date = date("D M j G:i:s T Y");
		$lf->logMessage('validateLoginInput.php: '.$name.'/'.$username.' user type '.$u_type.', user priv '.$u_priv." logged in on ".$now_date."\n");
		$lf->setLogFileName($lfname);				
		
		
		$_SESSION[loginstatemessage] .= " ".$utypemsg[$u_type]." ".$uprivmsg[$u_priv];
		
		//$lf->logMessage("validateLoginInput.php: landingpath: ".$homelandingurlfull[$u_type]."\n");
		
		return(1);

	}
}
?>