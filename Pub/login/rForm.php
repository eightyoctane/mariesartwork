<?php
session_start();

include_once($_SERVER['DOCUMENT_ROOT']."/homedata.php");
include_once($rootlocation."PresenterClass.php");


include_once ($rootlocation."login/dbdata.php");
include_once($rootlocation."login/mysqlClass.php");
include_once($rootlocation."login/rFormClass.php");
require_once($rootlocation."/login/libfunctions.php");
include_once($rootlocation."logFileClass.php");

$lf = new logFile();
//$lf->logMessage("rForm.php - In ".$nameid." rForm\n");
	
$gp = new generalPageBrowser($rootlocation.'RegionAndHeader.htm');
	
$gp = new GeneralPageBrowser($rootlocation."RegionAndHeader.htm");
$gp->addHeader($rootlocation."HeaderContent.htm");
$gp->addMenu($rootlocation.'MenuContent.htm');
$gp->addFooter($rootlocation."generalFooter.htm");

	
$rf = new rForm($rootlocation."login/rFormA.html");
//$rf->setWebsite($rootlocation."index.php");

$rf->setWebsite($nameid);
$rf->setFormName('Member Registration Form');
$rf->setEditCallBackPgm($homelocationpart.$homelocationsub.'login/rForm.php');
$rf->setSuccessCallBackPgm($homelocationpart.$homelocationsub.'index.php');

/*
Has the user submitted data?
If not, display the registration form.
*/

if (! isset($_POST['submitbutton'])){

	//$lf->logMessage("rForm.php - Displaying registration form\n");

	UpdateTypeBoxSelection(0,&$tboxhtml,&$tmsg);
	UpdatePrivBoxSelection(0,&$pboxhtml,&$pmsg);	
	//                             1                            2    3    4    5    6    7    8    9    0    1    2    3    4    5           6           7      8
	$text = $rf->renderRformText("FIRST LAST or FIRST MI LAST",NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL, $tboxhtml, $pboxhtml, NULL, NULL);
	$gp->addBodyText($text);
	$gp->renderPage();
	//echo file_get_contents("./Templates/registrationa.html");
	/* Form data has been submitted. */
} else {
	
	//$lf->logMessage("rForm.php - Opening database ".$database." to register new user\n");

	$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
	$mysqldb->connect();
	$mysqldb->select();


	/* Insure that the requested username is not a duplicate */
	
	if ($_POST['uname'] == NULL) {
		//echo "<p>Username not set!</p>";
		UpdateTypeBoxSelection($_POST['u_type'],&$tboxhtml,&$tmsg);
		UpdatePrivBoxSelection($_POST['u_priv'],&$pboxhtml,&$pmsg);
		$text = $rf->renderRformText(NULL, "Username required please try again.",NULL, NULL, $_POST['name'], NULL, 
		$_POST['email'], $_POST['apt'], $_POST['street'],$_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'], &$tboxhtml, &$pboxhtml, &$tmsg, &$pmsg);
		$gp->addBodyText($text);
		$gp->renderPage();
		
	} else if (CheckForDuplicates($_POST['uname'], 'username', 'user')) {
		//echo "<p>duplicate username</p>";
		UpdateTypeBoxSelection($_POST['u_type'],&$tboxhtml,&$tmsg);
		UpdatePrivBoxSelection($_POST['u_priv'],&$pboxhtml,&$pmsg);
		$text = $rf->renderRformText(NULL, "Duplicate username please try again.",NULL, NULL, $_POST['name'], NULL, 
		$_POST['email'], $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'],&$tboxhtml, &$pboxhtml, &$tmsg, &$pmsg);
		$gp->addBodyText($text);
		$gp->renderPage();
		
	} else if ($_POST['pwd'] == NULL) {
		//echo "<p>Password must be set.</p>";
		UpdateTypeBoxSelection($_POST['u_type'],&$tboxhtml,&$tmsg);
		UpdatePrivBoxSelection($_POST['u_priv'],&$pboxhtml,&$pmsg);
		$text = $rf->renderRformText(NULL, NULL,"Password must be set. Please try again.", NULL, $_POST['name'], $_POST['uname'], 
		$_POST['email'], $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'],&$tboxhtml, &$pboxhtml, &$tmsg, &$pmsg);
		$gp->addBodyText($text);
		$gp->renderPage();

	} else if ($_POST['pwd'] != $_POST['pwdv']){
		/* Ensure that the password and password verifier match. */
		//echo "<p>passwords do not match please try again</p>";		
		UpdateTypeBoxSelection($_POST['u_type'],&$tboxhtml,&$tmsg);
		UpdatePrivBoxSelection($_POST['u_priv'],&$pboxhtml,&$pmsg);
		$text = $rf->renderRformText(NULL, NULL,"Passwords do not match please try again", NULL, $_POST['name'], $_POST['uname'], 
		$_POST['email'], $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'],&$tboxhtml, &$pboxhtml, &$tmsg, &$pmsg);
		$gp->addBodyText($text);
		$gp->renderPage();
		
	} else if (!CheckNameValidity($_POST['name'], &$msg) ) {
		//echo "<p>Name required</P>";		
		UpdateTypeBoxSelection($_POST['u_type'],&$tboxhtml,&$tmsg);
		UpdatePrivBoxSelection($_POST['u_priv'],&$pboxhtml,&$pmsg);
		$text = $rf->renderRformText($msg, NULL, NULL, NULL, NULL, $_POST['uname'], 
		$_POST['email'], $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'],&$tboxhtml, &$pboxhtml, &$tmsg, &$pmsg);
		$gp->addBodyText($text);
		$gp->renderPage();
	
	} else if (!CheckEmailValidity($_POST['email'], &$msg) ) {
		UpdateTypeBoxSelection($_POST['u_type'],&$tboxhtml,&$tmsg);
		UpdatePrivBoxSelection($_POST['u_priv'],&$pboxhtml,&$pmsg);
		$text = $rf->renderRformText(NULL, NULL, NULL, $msg, $_POST['name'], $_POST['uname'], 
		NULL, $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'],&$tboxhtml, &$pboxhtml, &$tmsg, &$pmsg);
		$gp->addBodyText($text);
		$gp->renderPage();
	
	} else {
	
		//echo "<p>Type: $_POST[D1]</p>";
		//email=$_POST['email;], username=$_POST['uname'],
		//add_apt=$_POST['apt'], add_street=$_POST['street'], add_city=$_POST['city'],
		//add_country=$_POST['country'], add_zip=$_POST['zip'],
		//start_date=time();
		$start_date = time();
		$logintype = $utabletype;
		$md5pwd = MD5('$_POST[pwd]');

		$mailstringwa = "Name: ".$_POST['name']."\nUser name: ".$_POST['uname']."\n ";
		
		$mailstring = 
			"You are now registered at ".$nameid.". \n".
			"User name: ".$_POST['uname']."\n".
			"Password:   ".$_POST['pwd']."\n\n";
		
		// 1=Academic
		// 2=Power Industry
		
		
		$temporaryUType = 1;
		if ($_POST[u_type] != 1) {
			

			$mailstringwa .= "Type change level ".$_POST[u_type]." type requested.\n";
			
			$mailstring .= 
			"You have requested an elevated User Type.\n".
			"If this type request is appropriate, we can usually establish\n".
			"your final account type privilege level within a day or two.\n";
			
		}
		
		$temporaryPType = 1;
		if ($_POST[u_priv] != 1) {
			

			$mailstringwa .= "Privilege change level ".$_POST[u_priv]." privilege requested.\n";
			
			$mailstring .= 
			"You have requested an elevated User Privilege.\n".
			"If this privilege request is appropriate, we can usually establish\n".
			"your final account privilege level within a day or two.\n";
			
		}
	

		$query = "INSERT INTO user (rowID, name, username, email, pwd, u_type,
					add_apt, add_street, add_city, add_state,
					add_zip, add_country, phone, start_date, login_type, u_priv) 
				  VALUES (NULL, '$_POST[name]', '$_POST[uname]', '$_POST[email]', MD5('$_POST[pwd]'), '$temporaryUType',
				  '$_POST[apt]', '$_POST[street]', '$_POST[city]', '$_POST[state]',
				  '$_POST[zip]', '$_POST[country]', '$_POST[phone]', '$start_date', '$logintype', '$temporaryPType')";
		
		//$query = "select * from user";
		
		//echo "query > $query";
		
		$mysqldb->query($query);
		
		//echo "<p> query = $query</p>";
		
		$result = $mysqldb->getResult();
		//echo "<p>result = $result</p>";
		
		$pdate = date('Y-m-d',$start_date);
		
		if(! $result) {
			$lf->logMessage("rForm.php: registration failed\n");
			mail($mailloc2, $nameid."Registration Failed", 
			"Login name: ".$_POST['name']."\n".
			"Password:   ".$_POST['pwd']."\n");
			echo "<p> Registration failed. Please contact ".$nameid." webadmin.</p>";
			session_unset();
			session_destroy();
			header('Location: '.$homelocationpart.$homelocationsub.'index.php');
		} else {
			//echo "<p> $pdate: Registration was successful!</p>";
			$lf->logMessage($mailstringwa."\n");
			// send mail to the new user
		
			mail("$_POST[email]", "Newly Registered ".$nameid." User", $mailstring);
		
			// send mail to myself
			
			$mailsub = $nameid." New Registration";
			
			mail($mailloc2, $mailsub, $mailstringwa);		
		
			session_unset();
			session_destroy();
			header('Location: '.$homelocationpart.$homelocationsub.'index.php');
		}
		

	} #endIF
	
}
?>
