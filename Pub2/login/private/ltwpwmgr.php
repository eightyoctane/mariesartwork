<?php
////////////////////////////////////////////////////////////////////////////
// ltw_pwmgr.php
// $Id: ltwpwmgr.php 5 2006-03-12 08:15:51Z mootinator $
//
// ltwCalendar Password Manager (change)
////////////////////////////////////////////////////////////////////////////


class ltwPwMgr {
var $auth		= '';
var $salt		= '';
var $loglevel		= '';
var $php_self		= '';

// constructor
function ltwPwMgr(){
	global $ltw_config;
	global $_POST;
	global $_SERVER;
			
	$this->auth 		= new ltwAuth;
	$this->salt 		= $ltw_config['salt'];
	$this->loglevel		= $ltw_config['uloglevel'];
	$this->php_self		= $_SERVER['PHP_SELF'];

} // end constructor

function manage(){
	global $_POST;
	
	$showForm = 1;
	$errors   = '';

	if ( !$this->auth->checkLogin() ){
		$this->auth->notLoggedIn();
		return 0;
	}

	// process the form
	if ( !empty($_POST['Submit']) ){

		if ( crypt($_POST['pwnow'], $this->salt) != $this->auth->user->password ){
			$errors .= "Current password invalid<br>";
		}

		$rstr = $this->auth->isPasswordValid($this->auth->user->username,$_POST['pwnow'],$_POST['pw'],$_POST['pwa'] );
		if ( !empty($rstr) ) $errors .= $rstr;

		if ( empty($errors) ){
			$this->auth->user->password = crypt($_POST['pw'], $this->salt);
			$this->auth->user->last_pw_change = date("Y-m-d H:I:s");
			$this->auth->user->clrStatus(USCHGPW);
			$this->auth->user->update();
			$this->notifier("<b>User:</b> ".$this->auth->user->username." changed password");
			jsClosePopupReloadMain($this->php_self);
			return;
		}
	}
			  
	if ( $showForm == 1 ){
		
		$errors .=  $this->auth->pwRules();
		
		echo "
		<html>
		<head><title>Change Password</title>
		</head>	
		<body>
		
		<form action=\"".$this->php_self."?display=admin&task=changepw\" method=\"POST\" name=\"changepw\">
  		<table border=\"1\">
		<tr><td colspan=\"2\">Change password for user <b>".$this->auth->user->username."</b></td></tr>
		<tr><td>Password:</td><td><input type=\"password\" size=20 name=\"pwnow\"></td></tr>
		<tr><td>New Password:</td><td><input type=\"password\" size=20 name=\"pw\"></td></tr>
		<tr><td>Again:</td><td><input type=\"password\" size=20 name=\"pwa\"></td></tr>
		<tr><td colspan=\"2\"><input type=\"Submit\" name=\"Submit\" value=\"Update\"></td></tr>
		</table>
		<input type=\"hidden\" name=\"uname\" value=\"".$this->auth->user->username."\">
		</form>
		".$errors."
		</body></html>
		";
	}			
} // end function.manage

function notifier($logm=''){
	if ( $this->loglevel && ULCHGPW ){
		$this->auth->user->log($this->auth->user->username,$logm);
	}

} // end function.notifier

} // end class.PwMgr

?>
