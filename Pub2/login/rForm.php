<?php

require_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");

//require_once($rootlocation."login/libfunctions.php");
include_once($rootlocation."login/mysqlClass.php");
include_once($rootlocation."login/rFormClass.php");
include_once($rootlocation."dbdata.php");
include_once($rootlocation.'logFileClass.php');
include_once($rootlocation.'PresenterClass.php');
require_once($rootlocation."login/VisitorDataClass.php");


$lf = new logFile();
	
$gp = new generalPageBrowser($regionandheaderfull);
$lf->logMessage("rForm.php: root : ".$rootlocation."\n");
	
$rf = new rForm($rootlocation."login/rFormA.html");
$rf->setWebsite($nameid);
$rf->setFormName('Visitor Registration Form');
$rf->setEditCallBackPgm($homelocationpart.$homelocationsub.'login/rForm.php');
$rf->setSuccessCallBackPgm($homelocationpart.$homelocationsub."index.php");


$lf->logMessage("rForm.php: opening database\n");
	
/* Connect to the MySQL server and select database. */
$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
$mysqldb->connect();
$mysqldb->select();


$vd = new VisitorData($mysqldb,$lf);
$vd->setDisplayFile($rootlocation."login/htmlfiles/landingagreement.htm");
 

/*
Has the user submitted data?
If not, display the registration form.
*/

if (! isset($_POST['submitbutton'])){

	$lf->logMessage("rForm.php: Displaying first time\n");

	$rf->UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, 0,&$tboxhtml,&$tmsg);
	$rf->UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg, 0,&$pboxhtml,&$pmsg);
	$rf->setCheckBox("notifyme",1);
	//                             1                            2    3    4    5    6    7    8    9    0    1    2    3    4    5           6           7      8
	$text = $rf->renderRformTextD("FIRST LAST or FIRST MI LAST",NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL, $tboxhtml, $pboxhtml, NULL, NULL,$vd);
	$gp->addBodyText($text);
	$gp->renderPage();

	/* Form data has been submitted. */
} else {

	$lf->logMessage("rForm.php: checking form validity\n");
	
	
	$notifyme = $rf->checkboxValue("notifyme"); 
	
	/* Insure that the requested username is not a duplicate */
	
	if ($_POST['uname'] == NULL) {

		$rf->UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, $_POST['u_type'],&$tboxhtml,&$tmsg);
		$rf->UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg,$_POST['u_priv'],&$pboxhtml,&$pmsg);
	
		$text = $rf->renderRformTextD(NULL, "Username required please try again.",NULL, NULL, $_POST['name'], NULL, 
		$_POST['email'], $_POST['apt'], $_POST['street'],$_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'], &$tboxhtml, &$pboxhtml, &$tmsg, &$pmsg, $rf->getCheckBox(),$vd);
		$gp->addBodyText($text);
		$gp->renderPage();
		
	} else if ($mysqldb->CheckForDuplicates($_POST['uname'], 'username', 'user')) {

		$rf->UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, $_POST['u_type'],&$tboxhtml,&$tmsg);
		$rf->UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg,$_POST['u_priv'],&$pboxhtml,&$pmsg);
		$text = $rf->renderRformTextD(NULL, "Duplicate username please try again.",NULL, NULL, $_POST['name'], NULL, 
		$_POST['email'], $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'],&$tboxhtml, &$pboxhtml, &$tmsg, &$pmsg, $rf->getCheckBox(),$vd);
		$gp->addBodyText($text);
		$gp->renderPage();
		
	} else if ($_POST['pwd'] == NULL) {
		
		$rf->UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, $_POST['u_type'],&$tboxhtml,&$tmsg);
		$rf->UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg,$_POST['u_priv'],&$pboxhtml,&$pmsg);
		$text = $rf->renderRformTextD(NULL, NULL,"Password must be set. Please try again.", NULL, $_POST['name'], $_POST['uname'], 
		$_POST['email'], $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'],&$tboxhtml, &$pboxhtml, &$tmsg, &$pmsg, $rf->getCheckBox(),$vd);
		$gp->addBodyText($text);
		$gp->renderPage();

	} else if ($_POST['pwd'] != $_POST['pwdv']){
		/* Ensure that the password and password verifier match. */

		$rf->UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, $_POST['u_type'],&$tboxhtml,&$tmsg);
		$rf->UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg,$_POST['u_priv'],&$pboxhtml,&$pmsg);
		$text = $rf->renderRformTextD(NULL, NULL,"Passwords do not match please try again", NULL, $_POST['name'], $_POST['uname'], 
		$_POST['email'], $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'],&$tboxhtml, &$pboxhtml, &$tmsg, &$pmsg, $rf->getCheckBox(),$vd);
		$gp->addBodyText($text);
		$gp->renderPage();
		
	} else if (!$rf->CheckNameValidity($_POST['name'], &$msg) ) {

		$rf->UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, $_POST['u_type'],&$tboxhtml,&$tmsg);
		$rf->UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg,$_POST['u_priv'],&$pboxhtml,&$pmsg);
		$text = $rf->renderRformTextD($msg, NULL, NULL, NULL, NULL, $_POST['uname'], 
		$_POST['email'], $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'],&$tboxhtml, &$pboxhtml, &$tmsg, &$pmsg, $rf->getCheckBox(),$vd);
		$gp->addBodyText($text);
		$gp->renderPage();
	
	} else if (!$rf->CheckEmailValidity($_POST['email'], &$msg) ) {
		$rf->UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, $_POST['u_type'],&$tboxhtml,&$tmsg);
		$rf->UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg,$_POST['u_priv'],&$pboxhtml,&$pmsg);
		$text = $rf->renderRformTextD(NULL, NULL, NULL, $msg, $_POST['name'], $_POST['uname'], 
		NULL, $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'],&$tboxhtml, &$pboxhtml, &$tmsg, &$pmsg, $rf->getCheckBox(),$vd);
		$gp->addBodyText($text);
		$gp->renderPage();
	
	} else {
	
		$lf->logMessage("rForm.php: validity checks complete\n");
		
		$lf->logMessage("rForm.php: Posted from form: u_type: ".$_POST['u_type']." p_type: ".$_POST['u_priv']."\n");
	
		$start_date = time();
		//$logintype = 'bluemountain';
		$md5pwd = MD5('$_POST[pwd]');

		$mailstringwa = "User name: ".$_POST['name'].": Login name: ".$_POST['uname']." ";
		$mailstring = 
			"You are now registered at ".$website."\n".
			"Name: ".$_POST['name']."\n".
			"Login name: ".$_POST['uname']."\n".
			"Password:   ".$_POST['pwd']."\n\n";
		
		// resident type requires manual setting by admin
		
		switch($_POST['u_type']) {

		case $utype['visitor']:
			$lf->logMessage("rForm.php: ".$utypemsg[$utype['resident']]." requested which gives immediate access to projects\n");
			$temporaryUType = $utype['visitor'];
			$temporaryPType = $upriv['anyowner'];
			break;

		case $utype['resident']:
			$lf->logMessage("rForm.php: resident type requested temporarily setting to visitor\n");
		
			$temporaryUType = $utype['visitor'];

			$mailstringwa .= "Type Change Needed\n";
			$mailstringwa .= "Requested type ".$utypemsg[$utype['resident']]." temporarily setting to ".$utypemsg[$utype['visitor']]."\n";
			
			$mailstring .= 
			"You have requested a ".$utypemsg[$utype['resident']]." user type.\n".
			"If these privileges are appropriate, we will usually establish\n".
			"your final account type within a day or two.\n";
			
			$temporaryPType = $upriv['anyowner'];
			
			$lf->logMessage("rForm.php: temporarily setting privilege to ".$uprivmsg[$upriv['anyowner']]."\n");

			break;

		case $utype['contractor']:
			$lf->logMessage("rForm.php: contractor type requested\n");
			$temporaryUType = $utype['contractor'];

			$lf->logMessage("rForm.php: contractor privilege must temporarily be limited to ".$uprivmsg[$upriv['submit']]."\n");		
		
			$temporaryPType = $upriv['submit'];
		
			if($_POST['u_priv'] == $upriv['staff']) {
				$mailstring .= 
				"You have requested a Contractor staff privilege.\n".
				"If this privilege is appropriate, we will usually establish\n".
				"your final account privileges within a day or two.\n";

				$mailstringwa .= "Contractor privilege change to ".$uprivmsg[$upriv['staff']]."\n";
				
				$temporaryPType = $upriv['submit'];
			}
			break;
		default:
			$lf->logMessage("rForm.php: unknown user type and privilege\n");
			$temporaryUType = $utype['visitor'];
			$temporaryPType = $upriv['anyowner'];
		}

/*		
		if ($_POST['u_type'] == $utype['resident']) {
		
			$lf->logMessage("rForm.php: resident type requested temporarily setting to visitor\n");
		
			$temporaryUType = $utype['visitor'];

			$mailstringwa .= "Type Change Needed\n";
			$mailstringwa .= "Requested type ".$utypemsg[$utype['resident']]." temporarily setting to ".$utypemsg[$utype['visitor']]."\n";
			
			$mailstring .= 
			"You have requested a ".$utypemsg[$utype['resident']]." user type.\n".
			"If these privileges are appropriate, we will usually establish\n".
			"your final account type within a day or two.\n";
			
		} else {
			$temporaryUType = $_POST['u_type'];
		}

		$temporaryPType = $upriv['anyowner'];
		
		if($_POST['u_type'] == $utype['contractor']) {
		
			if($_POST['u_priv'] == $upriv['staff']) {
				$mailstring .= 
				"You have requested a Contractor staff privilege.\n".
				"If this privilege is appropriate, we will usually establish\n".
				"your final account privileges within a day or two.\n";

				$mailstringwa .= "Contractor privilege change to staff needed\n";
				
				$temporaryPType = $upriv['submit'];
				
			}
		}
*/

		$query = "INSERT INTO user (rowID, name, username, email, pwd, u_type,
					add_apt, add_street, add_city, add_state,
					add_zip, add_country, phone, start_date, login_type, u_priv, e_notify, status) 
				  VALUES (NULL, '$_POST[name]', '$_POST[uname]', '$_POST[email]', MD5('$_POST[pwd]'), '$temporaryUType',
				  '$_POST[apt]', '$_POST[street]', '$_POST[city]', '$_POST[state]',
				  '$_POST[zip]', '$_POST[country]', '$_POST[phone]', '$start_date', '$logintype', '$temporaryPType', 
				  '$notifyme', 'active')";
		
		$mysqldb->query($query);
	
		$result = $mysqldb->getResult();
		//echo "<p>result = $result</p>";
		
		$pdate = date('Y-m-d',$start_date);
		
		if(! $result) {

			$lf->logMessage("rForm registration failed");

			mail($email, $nameid." User Registration Failed", 
			"Login name: ".$_POST['uname']."\n".
			"Password:   ".$_POST['pwd']."\n");
			echo "<p> Registration failed. Please contact ".$nameid." webadmin.</p>";
			session_unset();
			session_destroy();
			header( "Location: ".$homelocationpart.$homelocationsub.'index.php');
		} else {
			$lf->logMessage($mailstringwa.$mailstring);
			// send mail to the new user
		
			$subject = "Newly Registered User @".$nameid;
			mail($_POST[email],$subject,$mailstring);
		
			// send mail to myself
			$subject = "New ".$nameid." User";
			mail($email, $subject, $mailstringwa);
		
			session_unset();
			session_destroy();
			header( "Location: ".$homelocationpart.$homelocationsub.'index.php');
		}
		

	} #endIF
	
}

	
?>
