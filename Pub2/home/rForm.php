?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT']."/OSA/login/dbdata.php");
include_once($_SERVER['DOCUMENT_ROOT']."/OSA/login/mysqlClass.php");
include_once($_SERVER['DOCUMENT_ROOT']."/OSA/login/rFormClass.php");
require_once($_SERVER['DOCUMENT_ROOT']."/OSA/login/libfunctions.php");
include_once($_SERVER['DOCUMENT_ROOT']."/OSA/logFileClass.php");

$lf = new logFile();

include_once($_SERVER['DOCUMENT_ROOT']."/OSA/gPageClass.php");
	
$gp = new gPage($_SERVER['DOCUMENT_ROOT']."/bma_RegionAndHeader.htm");
	
// Add a menu appropriate for this page and state
$gp->addMenu($_SERVER['DOCUMENT_ROOT']."/OSA/homepageMenu.html");
	
$rf = new rForm($_SERVER['DOCUMENT_ROOT']."/OSA/login/rFormA.html");
$rf->setWebsite('http://www.bluemountainairpark.com');
$rf->setFormName('Visitor Registration Form');
$rf->setEditCallBackPgm('rForm.php');
$rf->setSuccessCallBackPgm('http://www.bluemountainairpark.com/index-visitor.php');

/*
Has the user submitted data?
If not, display the registration form.
*/

if (! isset($_POST['submitbutton'])){
	UpdateTypeBoxSelection(0,&$tboxhtml,&$tmsg);
	UpdatePrivBoxSelection(0,&$pboxhtml,&$pmsg);	
	//                             1                            2    3    4    5    6    7    8    9    0    1    2    3    4    5           6           7      8
	$text = $rf->renderRformText("FIRST LAST or FIRST MI LAST",NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL, $tboxhtml, $pboxhtml, NULL, NULL);
	$gp->addBodyText($text);
	$gp->renderGeneralPage();
	/* Form data has been submitted. */
} else {
	
	//echo "opening the database <br />";
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
		$gp->renderGeneralPage();
		
	} else if (CheckForDuplicates($_POST['uname'], 'username', 'user')) {
		//echo "<p>duplicate username</p>";
		UpdateTypeBoxSelection($_POST['u_type'],&$tboxhtml,&$tmsg);
		UpdatePrivBoxSelection($_POST['u_priv'],&$pboxhtml,&$pmsg);
		$text = $rf->renderRformText(NULL, "Duplicate username please try again.",NULL, NULL, $_POST['name'], NULL, 
		$_POST['email'], $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'],&$tboxhtml, &$pboxhtml, &$tmsg, &$pmsg);
		$gp->addBodyText($text);
		$gp->renderGeneralPage();
		
	} else if ($_POST['pwd'] == NULL) {
		//echo "<p>Password must be set.</p>";
		UpdateTypeBoxSelection($_POST['u_type'],&$tboxhtml,&$tmsg);
		UpdatePrivBoxSelection($_POST['u_priv'],&$pboxhtml,&$pmsg);
		$text = $rf->renderRformText(NULL, NULL,"Password must be set. Please try again.", NULL, $_POST['name'], $_POST['uname'], 
		$_POST['email'], $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'],&$tboxhtml, &$pboxhtml, &$tmsg, &$pmsg);
		$gp->addBodyText($text);
		$gp->renderGeneralPage();

	} else if ($_POST['pwd'] != $_POST['pwdv']){
		/* Ensure that the password and password verifier match. */
		//echo "<p>passwords do not match please try again</p>";		
		UpdateTypeBoxSelection($_POST['u_type'],&$tboxhtml,&$tmsg);
		UpdatePrivBoxSelection($_POST['u_priv'],&$pboxhtml,&$pmsg);
		$text = $rf->renderRformText(NULL, NULL,"Passwords do not match please try again", NULL, $_POST['name'], $_POST['uname'], 
		$_POST['email'], $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'],&$tboxhtml, &$pboxhtml, &$tmsg, &$pmsg);
		$gp->addBodyText($text);
		$gp->renderGeneralPage();
		
	} else if (!CheckNameValidity($_POST['name'], &$msg) ) {
		//echo "<p>Name required</P>";		
		UpdateTypeBoxSelection($_POST['u_type'],&$tboxhtml,&$tmsg);
		UpdatePrivBoxSelection($_POST['u_priv'],&$pboxhtml,&$pmsg);
		$text = $rf->renderRformText($msg, NULL, NULL, NULL, NULL, $_POST['uname'], 
		$_POST['email'], $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'],&$tboxhtml, &$pboxhtml, &$tmsg, &$pmsg);
		$gp->addBodyText($text);
		$gp->renderGeneralPage();
	
	} else if (!CheckEmailValidity($_POST['email'], &$msg) ) {
		UpdateTypeBoxSelection($_POST['u_type'],&$tboxhtml,&$tmsg);
		UpdatePrivBoxSelection($_POST['u_priv'],&$pboxhtml,&$pmsg);
		$text = $rf->renderRformText(NULL, NULL, NULL, $msg, $_POST['name'], $_POST['uname'], 
		NULL, $_POST['apt'], $_POST['street'], $_POST['city'], $_POST['state'],$_POST['zip'], 
		$_POST['country'], $_POST['phone'],&$tboxhtml, &$pboxhtml, &$tmsg, &$pmsg);
		$gp->addBodyText($text);
		$gp->renderGeneralPage();
	
	} else {
	
		//echo "<p>Type: $_POST[D1]</p>";
		//email=$_POST['email;], username=$_POST['uname'],
		//add_apt=$_POST['apt'], add_street=$_POST['street'], add_city=$_POST['city'],
		//add_country=$_POST['country'], add_zip=$_POST['zip'],
		//start_date=time();
		$start_date = time();
		$logintype = 'bluemountain';
		$md5pwd = MD5('$_POST[pwd]');

		$mailstringwa = $_POST['name'].":Login name: ".$_POST['uname'].": ";
		$mailstring = 
			"You are now registered at http://www.bluemountainairpark.com . \n".
			"Login name: ".$_POST['name']."\n".
			"Password:   ".$_POST['pwd']."\n";
		
		// 1=Blue Mountain Visitor
		// 2=Blue Mountain Owner/Resident
		if ($_POST[u_type] == 2) {
			$temporaryUType = 1;

			$mailstringwa .= "Type Change Needed\n";
			
			$mailstring .= 
			"You have requested a Property Owner/Resident type ID.\n".
			"If these privileges are appropriate, we will usually establish\n".
			"your final account privileges within a day or two.\n";
			
		} else {
			$temporaryUType = $_POST['u_type'];
		}

		$temporaryPType = 1;	

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
			$lf->logMessage("rForm registration failed");
			mail("eightyoctane@yahoo.com", "BMA User Registration Failed", 
			"Login name: ".$_POST['name']."\n".
			"Password:   ".$_POST['pwd']."\n");
			echo "<p> Registration failed.</p>";
			session_unset();
			session_destroy();
			header( 'Location: http://www.bluemountainairpark.com/index.php');
		} else {
			//echo "<p> $pdate: Registration was successful!</p>";
			$lf->logMessage($mailstringwa);
			// send mail to the new user
		
			mail("$_POST[email]", "Newly Registered User @bluemountainairpark.com", $mailstring);
		
			// send mail to myself
			mail("eightyoctane@yahoo.com", "New BMA User", $mailstringwa);
			//mail("mariesartwork@yahoo.com", "Newly Registered User @bluemountainairpark.com", $mailstringwa");		
		
			session_unset();
			session_destroy();
			header( 'Location: http://www.bluemountainairpark.com/index.php');

		
			//$_SESSION['UserName'] = $_POST[name];
			//header('Location: http://www.bluemountainairpark.com/index.php');
		}
		

	} #endIF
	
}
?>
