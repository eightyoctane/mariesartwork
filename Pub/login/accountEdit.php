<?php 
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT']."/OSA/login/dbdata.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/OSA/login/mysqlClass.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/OSA/login/rFormClass.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/OSA/login/libfunctions.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/OSA/login/sPageClass.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/OSA/login/sessionSetup.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/OSA/logFileClass.php");
	
	$lf = new logFile();
	
	$lf->logMessage("OSA/accountEdit.php: Editing user account settings\n");
	
	$sp = new sPage($_SERVER['DOCUMENT_ROOT']."/OSA/bma_RegionAndHeader.htm");
	
	sessionSetup($sp,$lf,$_SESSION['u_type'],$_SESSION['u_priv'],$_SESSION['homepage']);
	
	$_SESSION['prevpage'] = 'http://www.bluemountainairpark.com/OSA/login/por.php';		
	
	
	$sp->addMenu($_SERVER['DOCUMENT_ROOT']."/OSA/homepageMenuL.html");
	$sp->addFooter($_SERVER['DOCUMENT_ROOT']."/OSA/generalFooter.htm");
	
	$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
	$mysqldb->connect();
	$mysqldb->select();


	
	$rform = new rForm($_SERVER['DOCUMENT_ROOT']."/OSA/login/rFormA.html");
	$rform->setWebsite("OSA ");
	$rform->setFormName("User Profile Editor - Password will be changed only if a new value is entered.");
	$rform->setEditCallBackPgm("http://www.bluemountainairpark.com/OSA/login/accountEdit.php");
	$rform->setSuccessCallBackPgm("http://www.bluemountainairpark.com/OSA/login/por.php");
	
	$mysqldb->query('SELECT rowID, name, username, email, add_apt, add_street, add_city, add_state, add_zip, add_country, u_type, u_priv, phone FROM user WHERE username=\''.$_SESSION['UserName'].'\'');
	
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
	
$errmsg='<font color="#FF0000">You do not have authority to change user privilege</font>';


if (! isset($_POST['submitbutton'])){
	
	// First time the page has been displayed so prepopulate with info from db
		
		UpdateTypeBoxSelection($row->u_type,&$tboxhtml,&$tmsg);
		UpdatePrivBoxSelection($row->u_priv,&$pboxhtml,&$pmsg);				
						
		$text = $rform->renderRformText(NULL, NULL, NULL, NULL, $row->name,$row->username,$row->email,$row->add_apt,
		$row->add_street,$row->add_city,$row->add_state,$row->add_zip,$row->add_country,$row->phone,$tboxhtml,$pboxhtml,$tmsg,$pmsg);
						
		$sp->addBodyText($text);
						
		$sp->renderSessionPage();
		
		exit();
		

} else {

/* Insure that the requested username is not a duplicate */
	
	if ($_POST['uname'] == NULL) {
		/* First of all profile entries are tracked by user name and id. If the
		the person modifying this profile set the uname to blank, we already have
		a problem, so check this first */
		
		//echo "<p>Username not set!</p>";
		UpdateTypeBoxSelection($_POST['u_type'],&$tboxhtml,&$tmsg);
		UpdatePrivBoxSelection($row->u_priv,&$pboxhtml,&$pmsg);
		if ($row->u_priv != $_POST['u_priv']) $pmsg=$errmsg;
		
		$text = $rform->renderRformText(NULL, "Cannot change user name please try again.",NULL, NULL, $_POST['name'], $row->username, 
		$_POST['email'], $_POST['apt'], $_POST['street'],$_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'], $tboxhtml,$pboxhtml,$tmsg,$pmsg); 
					
		$sp->addBodyText($text);
						
		$sp->renderSessionPage();

		exit();
	}
		if ($row->u_priv  != $_POST['u_priv']) {
			UpdateTypeBoxSelection($_POST['u_type'],&$tboxhtml,&$tmsg);
			UpdatePrivBoxSelection($_POST['u_privh'],&$pboxhtml,&$pmsg);
			$pmsg=$errmsg;
					
			$text = $rform->renderRformText(NULL, NULL ,NULL, NULL, $_POST['name'], $row->username, 
			$_POST['email'], $_POST['apt'], $_POST['street'],$_POST['city'], $_POST['state'],$_POST['zip'], 
			$_POST['country'], $_POST['phone'], $tboxhtml,$pboxhtml,$tmsg,$pmsg); 
					
			$sp->addBodyText($text);
						
			$sp->renderSessionPage();

			exit();
	}

		
	if (! CheckForDuplicates($_POST['uname'], 'username', 'user')) {
		/* Since we are editing an existing profile, the profile entry must already
		exist in the database. If it doesn't we have a problem. This mismatch tells us
		that the person editing this profile tried to change the user name which is not
		allowed. */

		UpdateTypeBoxSelection($_POST['u_type'],&$tboxhtml,&$tmsg);
		UpdatePrivBoxSelection($_POST['u_privh'],&$pboxhtml,&$pmsg);
		if ($row->u_priv  != $_POST['u_priv']) $pmsg=$errmsg;
		
		$text = $rform->renderRformText(NULL, "Cannot change user name please try again." ,NULL, NULL, $_POST['name'], $row->username, 
		$_POST['email'], $_POST['apt'], $_POST['street'],$_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'], $tboxhtml,$pboxhtml,$tmsg,$pmsg); 
	
						
		$sp->addBodyText($text);
						
		$sp->renderSessionPage();

		exit();
	}
		
	//echo "<p>Good, profile is in the database</p>";
		
		/* We have now met our minimum requirements for editing a profile entry */
		
		if ($_POST['pwd'] != NULL) {
			
			/* If the password is not null, the user is trying to reset
			the password so we have to make sure the verifier password matches */

			if ($_POST['pwd'] != $_POST['pwdv']){
			/* Ensure that the password and password verifier match. */
			//echo "<p>passwords do not match please try again</p>";
			UpdateTypeBoxSelection($_POST['u_type'],&$tboxhtml,&$tmsg);
			UpdatePrivBoxSelection($_POST['u_privh'],&$pboxhtml,&$pmsg);
		if ($row->u_priv  != $_POST['u_priv']) $pmsg=$errmsg;

			$text = $rform->renderRformText(NULL, NULL,"Passwords do not match please try again", NULL, $_POST['name'], $row->username, 
			$_POST['email'], $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
			$_POST['country'], $_POST['phone'],$tboxhtml,$pboxhtml,$tmsg,$pmsg);

			$sp->addBodyText($text);
						
			$sp->renderSessionPage();

			exit();
			}
		}
		
		if (!CheckNameValidity($_POST['name'], $msg)) {
			UpdateTypeBoxSelection($_POST['u_type'],&$tboxhtml,&$tmsg);
			UpdatePrivBoxSelection($_POST['u_privh'],&$pboxhtml,&$pmsg);
		if ($row->u_priv != $_POST['u_priv']) $pmsg=$errmsg;

			$text = $rform->renderRformText($msg, NULL, NULL, NULL, NULL, $row->username, 
			$_POST['email'], $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
			$_POST['country'], $_POST['phone'],$tboxhtml,$pboxhtml,$tmsg,$pmsg);
						
			$sp->addBodyText($text);
						
			$sp->renderSessionPage();

			exit();
		}
	
	
		if (!CheckEmailValidity($_POST['email'], $msg)) {
		
			UpdateTypeBoxSelection($_POST['u_type'],&$tboxhtml,&$tmsg);
			UpdatePrivBoxSelection($_POST['u_privh'],&$pboxhtml,&$pmsg);
			if ($row->u_priv != $_POST['u_priv']) $pmsg=$errmsg;

			$text = $rform->renderRformText(NULL, NULL, NULL, $msg, $_POST['name'], $row->username, 
			NULL, $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
			$_POST['country'], $_POST['phone'], $tboxhtml,$pboxhtml,$tmsg,$pmsg);
						
			$sp->addBodyText($text);
						
			$sp->renderSessionPage();

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
					phone = '$_POST[phone]'
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
					u_type = '$_POST[D1]',
					add_apt = '$_POST[apt]',
					add_street = '$_POST[street]', 
					add_city = '$_POST[city]',
					add_state = '$_POST[state]',
					add_zip = '$_POST[zip]',
					add_country = '$_POST[country]',
					phone = '$_POST[phone]',
					pwd = '$pwd'
					WHERE rowID = '$_POST[editid]'";
				
			}
	
		$mysqldb->query($query);
					
		$now_date = time();
		
		// send mail to the new user
		mail("$_POST[email]", "OSA User Profile Update", "Hi $_POST[name],\n Your user profile, member name: $_POST[uname] \nat
		the OSA website was updated on $now_date.\n");
	
		// send mail to myself
		mail("eightyoctane@yahoo.com", "User Profile Update @OSA-bluemountainairpark.com", "customer name: $_POST[name]\nLogin name: $_POST[uname]\n\n");
		//mail("amlidata@yahoo.com",     "User Profile Update @artmarie.com", "Customer name: $_POST[name]\nLogin name: $_POST[uname]\n\n");		
		
		$lf->logMessage("Change account setting successful for ".$_POST['name']."\n");
		
		header( 'Location: http://www.bluemountainairpark.com/OSA/login/por.php');	

}

?>
