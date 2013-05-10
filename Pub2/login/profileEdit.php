<?php
include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
include_once($rootlocation."PresenterClass.php");	
include_once($rootlocation.'logFileClass.php');
require_once($rootlocation."login/libfunctions.php");
include_once($rootlocation."login/mysqlClass.php");
include_once($rootlocation."login/rFormClass.php");
include_once($rootlocation."dbdata.php");
	
$lf = new logFile();
$sp = new sessionPageBrowser($regionandheaderfull,$upriv['anyowner'],$homelandingurlfull[$utype['webadmin']]);


/* Connect to the MySQL server and select database. */
$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
$mysqldb->connect();
$mysqldb->select();

$of = new rForm($rootlocation."login/rFormABasicW.html");
$of->setWebsite($nameid);
$of->setFormName("User Profile Editor - Password will be changed only if a new value is entered");
$of->setEditCallBackPgm($homelocationpart.$homelocationsub."login/profileEdit.php");
$of->setSuccessCallBackPgm($homelocationpart.$homelocationsub."login/ListAndSelectUsers.php");



if (! isset($_POST['submitbutton'])){
	
	//get id from hidden field if page was previously displayed

	$lf->logMessage("profileEdit.php: first time entry\n");

	if (!isset($_GET['editid'])) {		
			echo "profileEdit.php: edit id was not set <br />";
			//REVISIT we really should set the screen error message and return to the landing page
			exit;
	} 
	 
	
	$mysqldb->query('SELECT rowID, name, username, email, add_apt, add_street, add_city, add_state, add_zip, add_country, u_type, u_priv, phone, e_notify FROM user WHERE rowID=\''.$_GET['editid'].'\'');

	
	if( $mysqldb->numRows() != 1 ) {
		//problem - sql should have returned only one row
		$lf->logMessage("profileEdit.php: mysql returned an incorrect number of rows\n");
		echo "profileEdit.php: mysql returned an incorrect number of rows <br />";
		exit;
	}

	$row = $mysqldb->fetchObject();
	
	
	$of->setHiddenValue("editid", $row->rowID);
	$of->setHiddenValue("unamesaved", $row->username);
	$of->setHiddenValue("u_type", $row->u_type);
	$of->setHiddenValue("u_priv", $row->u_priv);
	
	if($row->e_notify == 1) {
		//echo "<p>**********notify set in database</p>";
		$of->setCheckBox("notifyme", 1);
	} else {
		//echo "<p>******* notify not set in database</p>";
		$of->setCheckBox("notifyme", 0);
	}
	
	if($row->status == "active") {
		$of->setCheckbox("acctstatus", 1);
	} else {
		$of->setCheckbox("acctstatus", 0);
	}



	$lf->logMessage("profileEdit: u_type: ".$row->u_type." u_priv: ".$row->u_priv."\n");
	
	
	$of->UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, $row->u_type,&$tboxhtml,&$tmsg);
	$of->UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg, $row->u_priv,&$pboxhtml,&$pmsg);
					
	
	$text = $of->renderRformText(NULL, NULL, NULL, NULL, $row->name,$row->username,$row->email,$row->add_apt,
	$row->add_street,$row->add_city,$row->add_state,$row->add_zip,$row->add_country,$row->phone,$tboxhtml,$pboxhtml,$tmsg,$pmsg);
	
	$sp->addBodyText($text);
	
	$sp->renderPage();
	exit;

} else {
	$lf->logMessage("profileEdit.php: submit detected\n");
		
	$u_type = $_POST['u_type'];
	$u_priv = $_POST['u_priv'];
		
	$notifyme = $of->checkboxValue("notifyme"); 
	
	if($of->checkboxValue("acctstatus")) {
		$accountstatus="active";
	} else {
		$accountstatus="inactive";
	}
			
	$of->setHiddenValue("editid", $_POST['editid']);
	$of->setHiddenValue("unamesaved", $_POST['unamesaved']);
	$of->setHiddenValue("u_type", $_POST['u_type']);
	$of->setHiddenValue("u_priv", $_POST['u_priv']);

	
	/* Insure that the requested username is not a duplicate */
	if ($_POST['uname'] == NULL) {
		/* First of all profile entries are tracked by user name and id. If the
		the person modifying this profile set the uname to blank, we already have
		a problem, so check this first */
		
		$of->UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, $row->u_type,&$tboxhtml,&$tmsg);
		$of->UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg, $row->u_priv,&$pboxhtml,&$pmsg);
		
		$text = $of->renderRformText(NULL, "Username required please try again.",NULL, NULL, $_POST['name'], NULL, 
		$_POST['email'], $_POST['apt'], $_POST['street'],$_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'],$tboxhtml,$pboxhtml,$tmsg,$pmsg);
		
		$sp->addBodyText($text);
		//echo "<p>*********profileEdit added body text</p>";				
		$sp->renderPage();

		exit();
	}
		
	if (! CheckForDuplicates($_POST['uname'], 'username', 'user')) {
		/* Since we are editing an existing profile, the profile entry must already
		exist in the database. If it doesn't we have a problem. This mismatch tells us
		that the person editing this profile tried to change the user name which is not
		allowed. Luckily we saved the uname as a hidden value */
		
		$of->UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, $row->u_type,&$tboxhtml,&$tmsg);
		$of->UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg, $row->u_priv,&$pboxhtml,&$pmsg);
		
		
		$text = $of->renderRformText(NULL, "Cannot change user name please try again.",NULL, NULL, $_POST['name'], $_POST['unamesaved'], 
		$_POST['email'], $_POST['apt'], $_POST['street'],$_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'], $tboxhtml,$pboxhtml,$tmsg,$pmsg);

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

			$of->UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, $row->u_type,&$tboxhtml,&$tmsg);
			$of->UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg, $row->u_priv,&$pboxhtml,&$pmsg);
		
			
			$text = $of->renderRformText(NULL, NULL,"Passwords do not match please try again", NULL, $_POST['name'], $_POST['uname'], 
			$_POST['email'], $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
			$_POST['country'], $_POST['phone'],$tboxhtml,$pboxhtml,$tmsg,$pmsg);

			$sp->addBodyText($text);
						
			$sp->renderPage();
						
			exit();
			}
		}
		
		if (!CheckNameValidity($_POST['name'], $msg)) {
			
			$of->UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, $row->u_type,&$tboxhtml,&$tmsg);
			$of->UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg, $row->u_priv,&$pboxhtml,&$pmsg);
			
			$text = $of->renderRformText($msg, NULL, NULL, NULL, NULL, $_POST['uname'], 
			$_POST['email'], $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
			$_POST['country'], $_POST['phone'],$tboxhtml,$pboxhtml,$tmsg,$pmsg);
			
			$sp->addBodyText($text);
						
			$sp->renderPage();
		//echo "<p>******profileEdit.php</p>";
		
			exit();
		}
	
	
		if (!CheckEmailValidity($_POST['email'], $msg)) {
			
			$of->UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, $row->u_type,&$tboxhtml,&$tmsg);
			$of->UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg, $row->u_priv,&$pboxhtml,&$pmsg);
			
			$text = $of->renderRform(NULL, NULL, NULL, $msg, $_POST['name'], $_POST['uname'], 
			NULL, $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
			$_POST['country'], $_POST['phone'], $tboxhtml,$pboxhtml,$tmsg,$pmsg);

			$sp->addBodyText($text);
						
			$sp->renderPage();
						
			exit();		
		}
		
		$lf->logMessage("profileEdit.php: Passed all validity checks\n");
			
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
					u_priv = '$_POST[u_priv]',
					status = '$accountstatus',
					e_notify = '$notifyme'
					WHERE rowID = '$_POST[editid]'";
			
			$pwd = "";
			
			// Might have to manipulate the password
			if( $_POST['pwd'] != NULL) {
				// They want to change the password
				$lf->logMessage("Changing password for ".$_POST['name']);				
				//echo "They want to change the password pwd: $_POST[pwd] <br />";
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
					pwd = '$pwd',
					u_type = '$_POST[u_type]',
					u_priv = '$_POST[u_priv]',
					status = '$accountstatus',
					e_notify = '$notifyme'
					WHERE rowID = '$_POST[editid]'";
				
			}
	
		$mysqldb->query($query);
					
		//$now_date = time();
		$now_date = date("D M j G:i:s T Y");
		
		$lf->logMessage("profileEdit.php: updated database, mailing status update\n");
		
		//$lf->logMessage(ReturnBoxHtml($lf, "u_type", $usertypepairs, 0)."<br />\n\n" );
		//$lf->logMessage(ReturnBoxHtml("u_type", $usertypepairs, 1)."\n" );


		
		// send mail to the new user

		$title .= "User Profile Update @$nameid"; 
		$content = 
		"Hi,\n
		Your user profile, for member name: $_POST[name], user name: $_POST[uname]\n
		at $website, was updated on $now_date\n
		";
		mail($_POST[email], $title, $content);		
	
		// send mail to myself
		mail($email, $title, "customer name: ".$_POST[name]."\nLogin name: ".$_POST[uname]."\n\n");
		
		header('Location: '.$homelandingurlfull[$sp->getUserType()]);
		exit(0);
}
?>