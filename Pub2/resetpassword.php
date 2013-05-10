<?php

function make_seed() {
  		list($usec, $sec) = explode(' ', microtime());
  		return (float) $sec + ((float) $usec * 100000);
}

function get_random_token(&$token) {

	$password_length = 9;


	srand(make_seed());

	$alfa = "1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
	$token = "";
	for($i = 0; $i < $password_length; $i ++) {
		$token .= $alfa[rand(0, strlen($alfa))];
	}
	return(MD5($token));

}

function search_by_name($mysqldb, $gp, $lf) {

	$lf->logMessage("resetpassword.php: Trying to reset password for username: ".$_POST['T2']."\n");
	
	$uname = $_POST['T2'];
	$query = "SELECT rowID, name, email, add_apt, add_street, add_city, add_state, add_zip, add_country, u_type, u_priv, phone FROM user WHERE username='$uname'";
	$mysqldb->query($query);
	
	if( $mysqldb->numRows() == 0 ) {
		return("no user entries for this username");
	} else if ($mysqldb->numRows() > 1) {	
		return("multiple (".$mysqldb->numRows()." user accounts associated with this username see webadmin");
	} else {
		//return("just right only one entry found");

		$row = $mysqldb->fetchObject();
		
		$pwd = get_random_token(&$token);

		$query = "UPDATE user SET pwd = '$pwd' WHERE rowID = '$row->rowID'";
		$mysqldb->query($query);
	
		$mailstring = "A BMA user (hopefully you), has reset the password for username: ".
		$uname."\n"."The new password is: ".$token."\n";
		
		mail($row->email, "BMA Password Reset", $mailstring);
		
		$lf->logMessage("resetpassword.php: Random password emailed to: ".$row->email."\n");
		return("resetting your password and emailing new password to your email account");
	
	}

}

function search_by_email($mysqldb, $gp, $lf) {
	
	$lf->logMessage("resetpassword.php: Trying to reset password for email: ".$_POST['T1']."\n");
	
	$em = $_POST['T1'];
	
	$query = "SELECT rowID, name, username, email, add_apt, add_street, add_city, add_state, add_zip, add_country, u_type, u_priv, phone FROM user WHERE email='$em'";
	$mysqldb->query($query);
	
	if( $mysqldb->numRows() == 0 ) {
		return("no user entries for this email address");
	} else if ($mysqldb->numRows() > 1) {	
		return("multiple (".$mysqldb->numRows().") user accounts associated with this email address try recovering by username");
	} else {
		//echo ("just right only one entry found");
		$row = $mysqldb->fetchObject();
		
		$pwd = get_random_token(&$token);
		
		$query = "UPDATE user SET pwd = '$pwd' WHERE rowID = '$row->rowID'";
		$mysqldb->query($query);
	
		$mailstring = "A BMA user (hopefully you), has reset the password for username: ".
		$row->username.'\n'."The new password is: ".$token.'\n';

		mail($row->email, "BMA Password Reset", $mailstring);
		$lf->logMessage("resetpassword.php: Random password emailed to: ".$row->email."\n");
		return("resetting your password and emailing new password to your email account");
	}
}

include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
include_once($rootlocation.'logFileClass.php');
include_once($rootlocation."PresenterClass.php");
include_once($rootlocation."login/mysqlClass.php");
include($rootlocation."dbdata.php");



$lf = new logFile();

$gp = new generalPageBrowser($regionandheaderfull);

$lf->logMessage("resetpassword.php: Starting and connecting to database.\n");
$lf->logMessage("resetpassword.php system: ".$system." dbuser: ".$dbuser." dbpwd: ".$dbpwd." database: ".$database."\n");

$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
$mysqldb->connect();
$mysqldb->select();

$lf->logMessage("resetpassword.php: Database connection successful.\n");

if ( !isset($_POST['B1']) ) {

		$lf->logMessage("resetpassword.php: B1 not set, rendering page\n");
		
		$gp->setStatusBarMessage($_GET['message']);
		
		//haven't displayed page yet
		$gp->addBodyContent($rootlocation.$staticcontentloc."resetpassword.htm");
		$gp->renderPage();
		
} else {

		$lf->logMessage("resetpassword.php: Received input. Processing... \n");

		if ( $_POST['T2'] != '') {
		
			$lf->logMessage("resetpassword.php: Received T2...searching by name\n");
		
			$msg = search_by_name($mysqldb, $gp, $lf);	
		} else {
		
			$lf->logMessage("resetpassword.php: No T2...searching by email\n");
		
			$msg = search_by_email($mysqldb, $gp, $lf);
		}	
		header( 'Location: '.$homelocationpart.$homelocationsub.'resetpassword.php?message='.$msg);
}

?>
