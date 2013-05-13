<?php

/* the profile editor is always invoked from another program, meaning
the rForm class was instantiated there the first time*/
session_start();

include_once($_SERVER['DOCUMENT_ROOT']."/homedata.php");
include_once($rootlocation."login/dbdata.php");
require_once($rootlocation."login/libfunctions.php");
include_once($rootlocation."login/mysqlClass.php");
include_once($rootlocation."login/rFormClass.php");
include_once($rootlocation."logFileClass.php");
include_once($rootlocation."PresenterClass.php");
include_once($rootlocation."login/sessionSetup.php");
	
	
$lf = new logFile();
	
$sp = new sessionPageBrowser($rootlocation."RegionAndHeader.htm");
$sp->addHeader($rootlocation."HeaderContent.htm");	
$sp->addMenu($rootlocation."login/MenuContentAtLogin.htm");
	
sessionSetup($sp,$lf,1,1,$homelocationpart.$homelocationsub."admin.php");	

$_SESSION['prevpage'] = $_SESSION['homepage'];	

/* Connect to the MySQL server and select database. */

$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
$mysqldb->connect();
$mysqldb->select();

if (! isset($_POST['submitbutton'])){

    $lf->logMessage("profileEdit.php submit button not set\n");
	
	echo "profileEdit.php: software error (please contact $sysadminmail <br />";

} else {

	$lf->logMessage("profileEdit.php: Displaying screen\n");

	$rform = new rForm($_SERVER['DOCUMENT_ROOT']."/OSA/login/rFormA.html");
	$rform->setWebsite("Mariesartwork ");
	$rform->setFormName("User Profile Editor - Password will be changed only if a new value is entered.");
	$rform->setEditCallBackPgm($homelocationpart.$homelocationsub."login/profileEdit.php");
	$rform->setSuccessCallBackPgm($homelocationpart.$homelocationsub."login/ListAndSelectUsers.php");
	$rform->setHiddenValue("editid", $_POST['editid']);
	$rform->setHiddenValue("unamesaved", $_POST['unamesaved']);
	$rform->setHiddenValue("u_type", $_POST['u_type']);
	$rform->setHiddenValue("u_priv", $_POST['u_priv']);
	//echo "profileEdit: utype: $_POST[u_type] <br />";	
	$u_type = $_POST['u_type'];
	$u_priv = $_POST['u_priv'];
	
	UpdateTypeBoxSelection($u_type,&$tboxhtml,&$tmsg);
	UpdatePrivBoxSelection($u_priv,&$pboxhtml,&$pmsg);
		

	/* Insure that the requested username is not a duplicate */
	
	if ($_POST['uname'] == NULL) {
		/* First of all profile entries are tracked by user name and id. If the
		the person modifying this profile set the uname to blank, we already have
		a problem, so check this first */
		
		
		
		$text = $rform->renderRformText(NULL, "Username required please try again.",NULL, NULL, $_POST['name'], NULL, 
		$_POST['email'], $_POST['apt'], $_POST['street'],$_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'],$tboxhtml,$pboxhtml,$tmsg,$pmsg);
		
		$sp->addMenu($rootlocation."login/MenuContentAtLogin.htm");
						
		$sp->addBodyText($text);
						
		$sp->renderPage();

		exit();
	}
		
	if (! CheckForDuplicates($_POST['uname'], 'username', 'user')) {
		/* Since we are editing an existing profile, the profile entry must already
		exist in the database. If it doesn't we have a problem. This mismatch tells us
		that the person editing this profile tried to change the user name which is not
		allowed. Luckily we saved the uname as a hidden value */
		
				
		$text = $rform->renderRformText(NULL, "Cannot change user name please try again.",NULL, NULL, $_POST['name'], $_POST['unamesaved'], 
		$_POST['email'], $_POST['apt'], $_POST['street'],$_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'], $tboxhtml,$pboxhtml,$tmsg,$pmsg);

		$sp->addMenu($rootlocation."login/MenuContentAtLogin.htm");
						
		$sp->addBodyText($text);
						
		$sp->renderPage();
		
		exit();
	}
		
	//echo "<p>Good, profile is in the database</p>";
		
		/* We have now met our minimum requirements for editing a profile entry */
		
		if ($_POST['pwd'] != NULL) {
			
			/* If the password is not null, the user is trying to reset
			the password so we have to make sure the verifier password matches */

			if ($_POST['pwd'] != $_POST['pwdv']){
			/* Ensure that the password and password verifier match. */		
			
			$text = $rform->renderRformText(NULL, NULL,"Passwords do not match please try again", NULL, $_POST['name'], $_POST['uname'], 
			$_POST['email'], $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
			$_POST['country'], $_POST['phone'],$tboxhtml,$pboxhtml,$tmsg,$pmsg);

			$sp->addMenu($rootlocation."login/MenuContentAtLogin.htm");
						
			$sp->addBodyText($text);
						
			$sp->renderPage();
						
			exit();
			}
		}
		
		if (!CheckNameValidity($_POST['name'], $msg)) {
			
						
			$rform->renderRformText($msg, NULL, NULL, NULL, NULL, $_POST['uname'], 
			$_POST['email'], $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
			$_POST['country'], $_POST['phone'],$tboxhtml,$pboxhtml,$tmsg,$pmsg);
			
			$sp->addMenu($rootlocation."login/MenuContentAtLogin.htm");
						
			$sp->addBodyText($text);
						
			$sp->renderPage();
			
			exit();
		}
	
	
		if (!CheckEmailValidity($_POST['email'], $msg)) {
			
			
			$text = $rform->renderRform(NULL, NULL, NULL, $msg, $_POST['name'], $_POST['uname'], 
			NULL, $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
			$_POST['country'], $_POST['phone'], $tboxhtml,$pboxhtml,$tmsg,$pmsg);

			$sp->addMenu($rootlocation."login/MenuContentAtLogin.htm");
						
			$sp->addBodyText($text);
						
			$sp->renderPage();
						
			exit();		
		}
			
			// Everything so far looks good for the database update

			$query = "UPDATE user SET
					name = '$_POST[name]',
					email = '$_POST[email]',
					u_type = '$_POST[u_type]',
					add_apt = '$_POST[apt]',
					add_street = '$_POST[street]', 
					add_city = '$_POST[city]',
					add_state = '$_POST[state]',
					add_zip = '$_POST[zip]',
					add_country = '$_POST[country]',
					phone = '$_POST[phone]',
					u_type = '$_POST[u_type]',
					u_priv = '$_POST[u_priv]'
					WHERE rowID = '$_POST[editid]'";
			
			$pwd = "";
			$pwdmessage = "";
			
			// Might have to manipulate the password
			if( $_POST['pwd'] != NULL) {
				// They want to change the password
				$lf->logMessage("Changing password for ".$_POST['name']);				
				//echo "They want to change the password pwd: $_POST[pwd] <br />";
				$pwd = MD5($_POST['pwd']);
				$pwdmessage = "Password was changed to $_POST[pwd].";
				
					$query = "UPDATE user SET
					name = '$_POST[name]',
					email = '$_POST[email]',
					u_type = '$_POST[D1]',
					add_apt = '$_POST[apt]',
					add_street = '$_POST[street]', 
					add_city = '$_POST[city]',
					add_state = '$_POST[state]',
					add_zip = '$_POST[zip]',
					add_country = '$_POST[country]',
					phone = '$_POST[phone]',
					pwd = '$pwd',
					u_type = '$_POST[u_type]',
					u_priv = '$_POST[u_priv]'
					WHERE rowID = '$_POST[editid]'";
				
			}
	
		try {
			$mysqldb->query($query);
		} catch (Exception $e) {
			$lf->logMessage($e);
			$lf->setErrorStatus($e);
			header( 'Location: '.$homelocationpart.$homelocationsub.'login/loginlanding.php');
			exit();
		}
					
		//$now_date = time();
		$now_date = date("D M j G:i:s T Y");
		
		$lf->logMessage("profileEdit.php: Profile edit successful for ".$_POST['name']."\n");
		
		// send mail to the new user
		mail("$_POST[email]", "$nameid User Profile Update", 
		"Hi,\n"."
		Your user profile, for member name: ".$_POST[name].", user name: ".$_POST[uname].
		"\n"."at the $nameid Website, was updated on ".$now_date.
		"\n".$pwdmessage."\n".$website."\n");
	
		// send mail to myself
		mail("eightyoctane@yahoo.com", "$nameid User Profile Update", "customer name: $_POST[name]\nLogin name: $_POST[uname]\n\n");
		
		header( 'Location: '.$homelocationpart.$homelocationsub.'login/loginlanding.php');	

}
?>