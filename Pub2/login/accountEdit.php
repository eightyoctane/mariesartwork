<?php
 
include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");

include_once($rootlocation."PresenterClass.php");	
include_once($rootlocation.'logFileClass.php');
require_once($rootlocation."login/libfunctions.php");
include_once($rootlocation."login/mysqlClass.php");
include_once($rootlocation."login/rFormClass.php");
include_once($rootlocation."dbdata.php");
	
$lf = new logFile();
	
$sp = new sessionPageBrowser($regionandheaderfull,$upriv['visitor'],$homelandingurlfull[$utype['visitor']]);

$myusertype=$sp->getUserType();

$lf->logMessage("accountEdit.php: opening database\n");

$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
$mysqldb->connect();
$mysqldb->select();

$lf->logMessage("accountEdit.php: getting current account information\n");
	
$rform = new rForm($rootlocation."login/rFormABasic.html");
$rform->setWebsite($nameid);
$rform->setFormName("User Profile Editor - Password will be changed only if a new value is entered.");
$rform->setEditCallBackPgm($homelocationpart.$homelocationsub."/login/accountEdit.php");
$rform->setSuccessCallBackPgm($homelandingurlfull[$myusertype]);

$mysqldb->query('SELECT rowID, name, username, email, add_apt, add_street, add_city, add_state, add_zip, add_country, u_type, u_priv, phone, e_notify, vid FROM user WHERE username=\''.$_SESSION['UserName'].'\'');
	
	if( $mysqldb->numRows() != 1 ) {
		//problem - sql should have returned only one row
		$lf->logMessage("mysql returned too many rows username:".$_SESSION['UserName']);
	
	} else {
	
		$row = $mysqldb->fetchObject();
		
		$rform->setHiddenValue("editid", $row->rowID);
		$rform->setHiddenValue("unamesaved", $row->username);
		$rform->setHiddenValue("u_typeh", $row->u_type);
		$rform->setHiddenValue("u_privh", $row->u_priv);	
		
	}
	
	if($row->e_notify == 1) {
		$rform->setCheckBox("notifyme", 1);
	} else {
		$rform->setCheckBox("notifyme", 0);
	}

	$rform->setVIDField($row->vid);
	
$errmsg='<font color="#FF0000">You do not have authority to change user privilege</font>';


if (! isset($_POST['submitbutton'])){
	
	// First time the page has been displayed so prepopulate with info from db

		$lf->logMessage("accountEdit.php: rendering page for the first time\n");
		
		UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, $row->u_type,&$tboxhtml,&$tmsg);
		UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg, $row->u_priv,&$pboxhtml,&$pmsg);
	
		$text = $rform->renderRformText(NULL, NULL, NULL, NULL, $row->name,$row->username,$row->email,$row->add_apt,
		$row->add_street,$row->add_city,$row->add_state,$row->add_zip,$row->add_country,$row->phone,$tboxhtml,$pboxhtml,$tmsg,$pmsg);
						
		$sp->addBodyText($text);
						
		$sp->renderPage();
		
		exit();
		

} else {

	if($_POST['notifyme'] == 1) {
		$lf->logMessage("accountEdit.php: User wants email notifications\n");
		$rform->setCheckBox("notifyme", 1);
	} else {
		$lf->logMessage("accountEdit.php: User has turned off email notifications\n");
		$rform->setCheckBox("notifyme",0);
	}	

/* Insure that the requested username is not a duplicate */

	$lf->logMessage("accountEdit.php: validate input data\n");
	
	if ($_POST['uname'] == NULL) {
		/* First of all profile entries are tracked by user name and id. If the
		the person modifying this profile set the uname to blank, we already have
		a problem, so check this first */
		

		UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, $row->u_type,&$tboxhtml,&$tmsg);
		UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg, $row->u_priv,&$pboxhtml,&$pmsg);
				
		if ($row->u_priv != $_POST['u_priv']) $pmsg=$errmsg;
		
		$text = $rform->renderRformText(NULL, "Cannot change user name please try again.",NULL, NULL, $_POST['name'], $row->username, 
		$_POST['email'], $_POST['apt'], $_POST['street'],$_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'], $tboxhtml,$pboxhtml,$tmsg,$pmsg,$rform->getCheckBox()); 
					
		$sp->addBodyText($text);
						
		$sp->renderPage();

		exit();
	}
		if ($row->u_priv  != $_POST['u_priv']) {
		
			UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, $row->u_type,&$tboxhtml,&$tmsg);
			UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg, $row->u_priv,&$pboxhtml,&$pmsg);
					
			
			$pmsg=$errmsg;
					
			$text = $rform->renderRformText(NULL, NULL ,NULL, NULL, $_POST['name'], $row->username, 
			$_POST['email'], $_POST['apt'], $_POST['street'],$_POST['city'], $_POST['state'],$_POST['zip'], 
			$_POST['country'], $_POST['phone'], $tboxhtml,$pboxhtml,$tmsg,$pmsg,$rform->getCheckBox()); 
					
			$sp->addBodyText($text);
						
			$sp->renderPage();

			exit();
	}

		
	if (! CheckForDuplicates($_POST['uname'], 'username', 'user')) {
		/* Since we are editing an existing profile, the profile entry must already
		exist in the database. If it doesn't we have a problem. This mismatch tells us
		that the person editing this profile tried to change the user name which is not
		allowed. */

		UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, $row->u_type,&$tboxhtml,&$tmsg);
		UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg, $row->u_priv,&$pboxhtml,&$pmsg);
				
		if ($row->u_priv  != $_POST['u_priv']) $pmsg=$errmsg;
		
		$text = $rform->renderRformText(NULL, "Cannot change user name please try again." ,NULL, NULL, $_POST['name'], $row->username, 
		$_POST['email'], $_POST['apt'], $_POST['street'],$_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'], $tboxhtml,$pboxhtml,$tmsg,$pmsg,$rform->getCheckBox()); 
	
						
		$sp->addBodyText($text);
						
		$sp->renderPage();

		exit();
	}
		
	//echo "<p>Good, profile is in the database</p>";
		
		/* We have now met our minimum requirements for editing a profile entry */
		
		if ($_POST['pwd'] != NULL) {
			
			/* If the password is not null, the user is trying to reset
			the password so we have to make sure the verifier password matches */
			
			$lf->logMessage("accountEdit.php: Detected non-null password.\n");

			if ($_POST['pwd'] != $_POST['pwdv']){
			/* Ensure that the password and password verifier match. */
			
			UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, $row->u_type,&$tboxhtml,&$tmsg);
			UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg, $row->u_priv,&$pboxhtml,&$pmsg);

			if ($row->u_priv  != $_POST['u_priv']) $pmsg=$errmsg;
	
				$text = $rform->renderRformText(NULL, NULL,"Passwords do not match please try again", NULL, $_POST['name'], $row->username, 
				$_POST['email'], $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
				$_POST['country'], $_POST['phone'],$tboxhtml,$pboxhtml,$tmsg,$pmsg,$rform->getCheckBox());
	
				$sp->addBodyText($text);
							
				$sp->renderPage();
	
				exit();
			}
		}
		
		if (!CheckNameValidity($_POST['name'], $msg)) {
		
			UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, $row->u_type,&$tboxhtml,&$tmsg);
			UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg, $row->u_priv,&$pboxhtml,&$pmsg);
					
			if ($row->u_priv != $_POST['u_priv']) $pmsg=$errmsg;

			$text = $rform->renderRformText($msg, NULL, NULL, NULL, NULL, $row->username, 
			$_POST['email'], $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
			$_POST['country'], $_POST['phone'],$tboxhtml,$pboxhtml,$tmsg,$pmsg,$rform->getCheckBox());
						
			$sp->addBodyText($text);
						
			$sp->renderPage();

			exit();
		}
	
	
		if (!CheckEmailValidity($_POST['email'], $msg)) {
		
			UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, $row->u_type,&$tboxhtml,&$tmsg);
			UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg, $row->u_priv,&$pboxhtml,&$pmsg);
					
			if ($row->u_priv != $_POST['u_priv']) $pmsg=$errmsg;

			$text = $rform->renderRformText(NULL, NULL, NULL, $msg, $_POST['name'], $row->username, 
			NULL, $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
			$_POST['country'], $_POST['phone'], $tboxhtml,$pboxhtml,$tmsg,$pmsg,$rform->getCheckBox());
						
			$sp->addBodyText($text);
						
			$sp->renderPage();

			exit();		
		}
	
		$lf->logMessage("accountEdit.php: data input validated\n");
		
		if(isset($_POST['notifyme'])) {
		$lf->logMessage("accountEdit.php: User still wants email notifications".$_POST['notifyme']."\n");
	} else {
		$lf->logMessage("accountEdit.php: User has turned off email notifications\n");
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
					e_notify = '$_POST[notifyme]'
					WHERE rowID = '$_POST[editid]'";
			
			$pwd = "";
			
			// Might have to manipulate the password
			if( $_POST['pwd'] != NULL) {
				// They want to change the password
				$lf->logMessage("accountEdit.php: Changing password for ".$_POST['name']);				

				$pwd = MD5($_POST['pwd']);
				
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
					e_notify = '$_POST[notifyme]',
					pwd = '$pwd'
					WHERE rowID = '$_POST[editid]'";
				
			}
	
		$mysqldb->query($query);
					
		$now_date = time();
		
		// send mail to the user
		
		$lf->logMessage("accountEdit.php: mailing updated status\n");
		
		$subject = "User Profile Update @".$nameid;
		
		$msg = "\n
		Hi ".$_POST[name].",\n
		Your user profile, member name: ".$_POST[uname]."\n
		at ".$website." was updated on ".$now_date."\n";
		
		mail($_POST[email], $subject , $msg );
	
		// send mail to myself
		$msg = "\n
		Customer name: ".$_POST[name]."\n
		Login name: ".$_POST[uname]."\n\n";
		
		mail($email, $subject, $msg);
		
		$lf->logMessage("accountEdit.php: finished\n");

		header('Location: '.$homelandingurlfull[$myusertype]);

}

?>
