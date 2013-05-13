<?php
/////////////////////////////////////////////////////////////////
// ltw_classes.php
// $Id: ltw_classes.php 28 2006-04-01 23:38:13Z mootinator $
//
// Contains the common code used by all Calendar functions
/////////////////////////////////////////////////////////////////
// Definitions for the bitmasks for account status, privledges, 
// user & event logging
/////////////////////////////////////////////////////////////////
define ("USLOCKED",	0x01);  // User Status	- Account Locked
define ("USCHGPW",	0x02);  // User Status	- Must Change Pw

define ("UPREAD",	0x01);  // User Privledge - Read Entries
define ("UPEDIT",	0x02);  // User Privledge - Edit Entries
define ("UPEMAIL",	0x04);	// User Privledge - Receive Event Change email
define ("UPLOGS",	0x40);  // User Privledge - View Logs
define ("UPADMIN",	0x80);  // User Privledge - Admin Calendar

define ("ULOG",		0x01);  // user log is type 1
define ("ULMANAGE",	0x01);  // User Logging	- User Add/Delete/Edit
define ("ULDETAIL",	0x02);  // User Logging	- User Details
define ("ULLOGIN",	0x04);  // User Logging	- Login 'events'
define ("ULCHGPW",	0x08);  // User Logging	- PW changed

define ("CLOG",		0x02);  // Cat log is type 2
define ("CLMANAGE",	0x01);  // Cat  Logging	- Category Add/Edit/Del
define ("CLDETAIL",	0x02);  // Cat  Logging	- Category Details

define ("ELOG",		0x03);  // Event log is type 3
define ("ELMANAGE",	0x01);  // Event Logging - Event Add/Edit/Delete
define ("ELDETAIL", 	0x02);  // Event Logging - Event Details

//////////////////////////////////////////////////////////////////
// Non Class functions
//////////////////////////////////////////////////////////////////

function jsClosePopupReloadMain($url=''){
	global $ltw_config;
	
	echo "
	<html><head>
	<meta http-equiv=\"refresh\" content=\"1;URL=".$url."\">
	</head>
	<body>
	";
	
	if ( $ltw_config['use_popups'] == 1 )
	echo "
	<script LANGUAGE=\"JavaScript\" type=\"text/javascript\">
	window.opener.location.href=\"".$url."\"; 
	self.close();
	</script>
	";
	
	echo "</body></html>";
} 

function jsReloadMain($url=''){
	global $ltw_config;
	
	echo "
	<html><head><meta http-equiv=\"refresh\" content=\"1;URL=".$url."\">
	</head><body>
	";
	
	if ( $ltw_config['use_popups'] == 1 )
	echo "
	<script LANGUAGE=\"JavaScript\" type=\"text/javascript\">
	window.location.href=\"".$url."\";
	</script>
	";
	
	echo "</body></html>";
} 

function jsReloadPopup($url=''){
	echo "
	<html><head><meta http-equiv=\"refresh\" content=\"1;URL=".$url."\">
	</head><body>
	";
	
	if ( $ltw_config['use_popups'] == 1 )
	echo "
	<script LANGUAGE=\"JavaScript\" type=\"text/javascript\">
	window.location.href=\"".$url."\";
	</script>
	";

	echo "</body></html>";
} 


//////////////////////////////////////////////////////////////////
// Core CalendarClasses
//////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////
// class ltwAuth provides access to some (or all) of the calendar,
//       user info, and database
// It:  validates the user & password
//      performs account locking
//      performs user activity logging 
//      performs password change
//
// It is also the 'super' class that should be used to access ltwUser & ltwDb
//////////////////////////////////////////////////////////////////
class ltwAuth{
var $user 		= '';
var $salt 		= '';
var $bad_logins 	= '';
var $login_required 	= '';
var $grace_period	= '';
var $min_pw_length	= '';
var $pw_strength	= '';
var $max_pw_age		= '';
var $loglevel		= '';
var $php_self		= '';

// constructor
function ltwAuth(){
	global $ltw_config;
	global $_POST;
	global $_SERVER;

	$this->user		= new ltwUser;
	$this->salt 		= $ltw_config['salt'];
	$this->bad_logins 	= $ltw_config['bad_logins'];
	$this->grace_period 	= $ltw_config['grace_period'];
	$this->login_required	= $ltw_config['login_required'];
	$this->min_pw_length    = $ltw_config['min_pw_length']; 
	$this->max_pw_age       = $ltw_config['max_pw_age']; 
	$this->pw_strength      = $ltw_config['pw_strength']; 
	$this->loglevel		= $ltw_config['uloglevel'];
	$this->php_self		= $_SERVER['PHP_SELF'];

} //end constructor

// checkLogin
// check the status of the ltw cookie and if it exists
// and is valid, the user is logged in and we get the info from the 
// database
function checkLogin(){
	//the following lines are added for backwards compatibility for pre 4.1.0 PHP versions..
	if ( !isset($_COOKIE) ){
		global $HTTP_COOKIE_VARS; 
		$_COOKIE = $HTTP_COOKIE_VARS;
	}
	//end backward compatibility
	
	// if the cookie is set, look up the username in the database	
	if ( isset($_COOKIE['ltw']) ){
		$array = explode(":", $_COOKIE['ltw']);
		if ( !isset($array[1]) ) $array[1] = '';
		if ( crypt($array[0], $this->salt) === $array[1] ){
			$this->user->findByName($array[0]);
			return 1;
		}else{
			return 0;
		}
	}else{
		return 0;
	}
} //end function.checkLogin()

// isLoginRequired
// returns the config parameter 'login_required'
// which will be 0 or true.
function isLoginRequired(){
	return $this->login_required;
} // end function.isLoginRequired
	
// isPasswordValid
// checks the supplied password to see if it is 'acceptable'.
// Passwords must be:
//	min_pw_length long
//      can't match username variations
//	mixed case
//	have 1 number
//	have 1 punctuation char
//
// function returns an empty string if ok, or 
// what tests failed if not
function isPasswordValid($uname = '', $pwc = '', $pw = '', $pwa = ''){
	if ( !isset($this->pw_strength) ) $this->pw_strength = 0;
	
	$rstr  = '';
	//echo "uname=$uname, pwc=$pwc, pw=$pw, pwa=$pwa<br>";

	if ( empty($uname) )
	return 'Username missing.<br>'; 

	if ( empty($pw) || empty($pwa) )
	return  "One of the new passwords missing.<br>";

	if ( $pw != $pwa )
	return "New passwords do not match.<br>";

	// convert the password to lower case and strip out the 
	// common number for letter substitutions	
	// then lowercase the username as well.
	$pw_lower = strtolower($pw);
	$pwc_lower= strtolower($pwc);
	$pw_denum = strtr($pw_lower,'5301!','seoll');
	$un_lower = strtolower($uname);

	if ( strlen($pw) < $this->min_pw_length )
	$rstr .= "Password must be at least ".$this->min_pw_length." characters.<br>";

	if ( ereg($un_lower,$pw_denum)  )		
	$rstr .= "Password can not contain username.<br>";

	if ( ereg(strrev($un_lower),$pw_denum)  )
	$rstr .= "Password can not contain username backward.<br>";

	if ( $pw_lower == $pwc_lower )
	$rstr .= "New Password can not match old<br>";

	if ( $this->pw_strength > 0 ){
		// MEDIUM STRENGTH
		if ( !ereg('[0-9]', $pw) )
		$rstr .= "New Password must contain a number<br>";

		if ( $this->pw_strength > 1 ){
			// HIGH STRENGTH
			if ( !ereg('[a-z]', $pw) )
			$rstr .= "New Password must contain a lower case letter<br>";

			if ( !ereg('[A-Z]', $pw) )
			$rstr .= "New Password must contain an UPPER CASE letter<br>";

			if ( !ereg('[^a-zA-Z0-9]', $pw) )
			$rstr .= "New Password must contain a puncutation character<br>";
		}
	}
	return $rstr;

} //end function.isPasswordValid

function pwRules(){

	switch ($this->pw_strength){
		case 0: $pws = "minimum" ; break;
		case 1: $pws = "medium" ;  break;
		case 2: $pws = "maximum" ; break;
	}

	$errors = "
	<b>The Password \"level\" on the calender is set to ".$pws."</b>.<br>
	Passwords must be at least ".$this->min_pw_length." characters long,
	and can not contain the username forward, backward, or using the numbers 
	'53011' for letters 'seoll'.";

	if ( $this->pw_strength > 0 ) {
		$errors .= "The password must also contain at least one number";
	}

	if ( $this->pw_strength > 1 ) {
		$errors .= ", one UPPER and lower case letter, and one punctuation character";
	}

	if ( $this->pw_strength > 0 ) $errors .= ".<br>";

	return $errors;

} // end function.pwRules


// login
// checks login credentials and sets cookie 'ltw' if
// accepted
function login(){
	global $_POST;

	$logged_in = 0;
	$showForm  = 0;
	$errors    = '';
	$bad_logins_now = 0;

	// display the login form if not both values in POST
	if ( empty($_POST['uname']) || empty($_POST['pword']) ){
		$showForm = 1;
	}else{
		// login process starts (assume failure)
		// look up 'uname' in the db
		if ( !$this->user->findByName($_POST['uname']) ){
			$showForm = 1;
			$errors .= "Login Failed<br>";
			if ( $this->loglevel && ULLOGIN ) $this->user->log('',"<b>Login</b>: bad username: ".htmlentities($_POST['uname']));
		}else{
			// see if account locked
			if ( ($this->user->status & USLOCKED) ){
				$errors .= "Account Locked<BR>";
				$showForm = 0;
				if ( $this->loglevel && ULLOGIN ) $this->user->log('',"<b>Login</b>: account locked: ".$_POST['uname']);
			}else{
				// test the password
				if ( crypt($_POST['pword'], $this->salt) != $this->user->password ){
					// a bad password was received.
					// See if the automated account locking is enabled
					if ( $this->bad_logins != 0 ){
						//Then look at the number of bad login attempts
						if ( !$this->user->bad_logins ){
							// 1st bad login attempt, set the counter = 1 
							// and the timestamp to the current EPOCH based seconds
							// (makes checking grace period easy)
							$this->user->bad_logins = 1;
							$this->user->bad_logins_start = date("U");
							$this->user->update();
							$errors .= "Login Failed<br>";
							$showForm = 1;
					      		if ( $this->loglevel && ULLOGIN ) 
							$this->user->log('',"<b>Login</b>(".$this->user->bad_logins."): bad password for " . $_POST['uname']);
						}else{
							// 2nd or higher bad login attempt
							// Add one to the counter
							$bad_logins_now   = $this->user->bad_logins + 1;
							$this->user->bad_logins = $bad_logins_now;
							$this->user->update();
							$errors .= "Login Failed<br>";
							$showForm = 1;
					      		if ( $this->loglevel && ULLOGIN ) 
							$this->user->log('',"<b>Login</b>(".$this->user->bad_logins."): bad password: ".$_POST['uname']."->".$_POST['pword']);
					      
							// if i've reached the lockout threshold of bad logins
							// subtract the time of the first bad login from the current time
							// and see if the result is less than the allowed "grace period"
							if ( $bad_logins_now >= $this->bad_logins ){
								$now = date("U");
					        		if ( ($now - $this->user->bad_logins_start) <= $this->grace_period ){
									// lock the account if too many logins in too short a period of time.
									$this->user->status = ($this->user->status | (USLOCKED));
									$this->user->bad_logins = 0;
									$this->user->update();
									$errors .= "Account Locked, too many bad login attempts.<br>";
									$showForm = 0;
					          			if ( $this->loglevel && ULLOGIN ) $this->user->log('',"<b>Login</b>: bad login account lock: ".$_POST['uname'] );
									echo "<html><head></head><body>$errors</body></html>";
					        		}else{
					          			//too many logins, but in longer than the "grace period" number of seconds
					          			// so reset the login counter to "1" and the bad logins start to "now"
									$this->user->bad_logins = 1;
									$this->user->bad_logins_start = $now;
									$this->user->update();
					        		}
					      		}
					    	}
					}
				}else{
					// Successful login!
					// Set up the ltw cookie and save the uname so the calendar can get it
					setcookie ('ltw','');
					$secret = crypt($_POST['uname'],$this->salt);
					setcookie('ltw', $_POST['uname'].":".$secret);			
 
					//reset the "bad login" counter
					$this->user->bad_logins = 0;
					$this->user->update();

					if ( $this->loglevel && ULLOGIN ) 
					$this->user->log('',"<b>Login</b>: User ".$_POST['uname']." login ok");
					$logged_in = 1;

		                        // if Status USCHGPW is not already set
                		        // see if password aging is enabled
		                        // (save another db access)
                		        if ( !$this->user->getStatus(USCHGPW) ){
                                		if ( isset($this->max_pw_age) && ($this->max_pw_age != 0) ){
		                                        if ( !$this->user->getStatus(USCHGPW) ){
                		                                $expire_date = time() - (86400 * $this->max_pw_age);
                                		                $lpwc_date = strtotime($this->user->last_pw_change);
                                                		if ( $expire_date > $lpwc_date ){
		                                                        $this->user->setStatus(USCHGPW);
                		                                        if ( $this->loglevel && ULLOGIN ) $this->user->log('',"<b>Login</b>: ".$_POST['uname'].", <b>password expired</b>.<br>Set USCHGPW");
                                		                }
                                        		}
                                		}
                        		}
				}
			}
		}
	}

	//show the form if needed
	if ( $showForm ){
		echo "
		<html>
		<head><title>Login</title>
		</head>
		<body><form action=\"".$this->php_self."?display=admin&task=login\" method=\"POST\" name=\"\">
		<table border=\"0\">
		<tr><td>Username:</td><td><input type=\"text\" size=20 name=\"uname\"></td></tr>
		<tr><td>Password:</td><td><input type=\"password\" size=20 name=\"pword\"></td></tr>
		<tr><td><input type=\"submit\" name=\"submit\" value=\"Login\"></td></tr>
		</table>
		</form>
		".$errors."
		</body></html>
		";
	}

	// if logged in , either close the popup or reload it with the
	// change password form
	if ( $logged_in ){ 
		if ( $this->user->getStatus(USCHGPW) ){
			jsReloadPopup($this->php_self."?display=admin&task=changepw");
		}else{
			jsClosePopupReloadMain($this->php_self);
		}
	}
} //end function.login()


// log out of the calendar
// clear the 'ltw' cookie and log the event
function logout(){
	// the following lines are added for backwards compatibility for pre 4.1.0 PHP versions..
	if ( !isset($_COOKIE) ){
		global $HTTP_COOKIE_VARS; 
		$_COOKIE = $HTTP_COOKIE_VARS;
	}
     
	// if the cookie is set with someting in it
	// we clear it to log out
	if ( isset($_COOKIE['ltw']) ){ 
		if ( $_COOKIE['ltw'] != "" ){
			$array = explode(":", $_COOKIE['ltw']);
			setcookie('ltw','',time()-3600);
			if ( $this->loglevel && ULLOGIN ){
				$this->user->findByName($array[0]);
				$this->user->log ('',"<b>Login</b>: User ".$this->user->username." logged out");
			}
		}
	}
	jsReloadMain($this->php_self);	
} //end function.logout()

	
function notLoggedIn(){
	echo "<html><head></head><body>Not logged in</body></html>";
}

function notPrivledged(){
	echo "<html><head></head><body>Insufficient privledges</body></html>";
}

} //end class.ltwAuth


/////////////////////////////////////////////////////////////////////////////
// class: ltwCategory - -get/set information about a catergory in the database.
// 1) instantiate the class   $C = new ltwCategory;
//
// 2) see if category exists  $C->findbyid($id);
//                         or $C->findbyname($name);
// 3) set parameters          $C->fgcolor = "0x111111';
// 4) update (existing)       $C->update();
//         or
// 5) create new category     $C->create();
//
// 6) delete a category       $C->deletebyname($name);
//   											 or $C->deletebyid($id);
//
// NOTE: Changes to the database are accomplished by calling
//       the update() or create() method.  This allows you 
//       to set multiple parameters and commit them all with
//       one db call.
/////////////////////////////////////////////////////////////////////////////
class ltwCategory{
var $db 	= '';
var $table	= '';
var $log   	= '';

// db columns
var $id 	= '';
var $name	= '';
var $fgcolor	= '';
var $bgcolor	= '';

// constructor 
function ltwCategory(){
	global $ltw_config;

	$this->db	= new ltwDb;
	$this->table 	= $ltw_config['db_table_category'];
	$this->log   	= $ltw_config['db_table_log'];

} // end constructor

// create - use this function to create a category
function create(){
	$query  = "INSERT INTO $this->table (name,fgcolor,bgcolor) ";
	$query .= "VALUES ('".addslashes($this->name)."','".addslashes($this->fgcolor)."','".addslashes($this->bgcolor)."' ";
	$query .= ")";
	$result = $this->db->db_query($query);
} //end function.create

// deleteById - use this function to delete a category
function deleteById($id = ''){
	$query  = "DELETE FROM ".$this->table." WHERE id = '".$id."'";
	$result = $this->db->db_query($query);
} //end function.deletebyid

// deleteByName - use this function to delete a category
function deleteByName($name = ''){
	$query  = "DELETE FROM ".$this->table." WHERE name = '".addslashes($name)."'";
	$result = $this->db->db_query($query);
} //end function.deletebyname

// findById - looks up a category in the db and retrieves their information
function findById($id = ''){
	// see if the user exists
	$query  = "SELECT * FROM $this->table WHERE id = '" . $id . "'";
	$result = $this->db->db_query($query);

	// exactly one row found ( a good thing!)
	if ( $this->db->db_numrows($result) == 1){
		$row = $this->db->db_fetch_array($result);
		$this->id     = $row['id'];
		$this->name   = stripslashes($row['name']);
		$this->fgcolor= stripslashes($row['fgcolor']);
		$this->bgcolor= stripslashes($row['bgcolor']);
		return 1;
	}
	return 0;
} // end function.findbyid

// findByName - looks up a category in the db and retrieves their information
function findByName($name = ''){
	// see if the user exists
	$query  = "SELECT * FROM $this->table WHERE name = '" . $name . "'";
	$result = $this->db->db_query($query);

	// exactly one row found ( a good thing!)
	if ( $this->db->db_numrows($result) == 1){
		$row = $this->db->db_fetch_array($result);
		$this->id     = $row['id'];
		$this->name   = stripslashes($row['name']);
		$this->fgcolor= stripslashes($row['fgcolor']);
		$this->bgcolor= stripslashes($row['bgcolor']);
		return 1;
	}
	return 0;
} // end function.findbyname

// log - handles catlog inserts
function log ($admin='',$info=''){
	$occured= date("YmdHis");
	$query  = "INSERT into $this->log (type,occured,admin,info) ";
	$query .= "VALUES ('".CLOG."','$occured','$admin','".addslashes($info)."')";
	$result = $this->db->db_query($query);
} //end function.log()

// update - use this function to update an existing category
function update(){
	$query  = "UPDATE $this->table ";
	$query .= "SET name = '"   .addslashes($this->name)."', ";
	$query .=     "fgcolor = '".addslashes($this->fgcolor)."', ";
	$query .=     "bgcolor = '".addslashes($this->bgcolor)."' ";
	$query .= " WHERE id = '".$this->id."'";
	$result = $this->db->db_query($query);
} //end function.update

}// end class.ltwCategory


/////////////////////////////////////////////////////////////////////////////
// class: ltwDb - common DB interface
//
// Currently, only MySql (type = 1) is supported.
// Adding other DB's should be a matter of adding cases to the
// "switch" statement for each function.
/////////////////////////////////////////////////////////////////////////////
class ltwDb{
var $db_type 		= ''; 
var $db_server 		= '';
var $db_name 		= '';
var $db_user 		= '';
var $db_pass 		= '';
var $db_persistent	= '';
var $dbh 		= '';

// constructor	
function ltwDb(){
	global $ltw_config;

	$this->db_type 		= $ltw_config['db_type'];
	$this->db_server	= $ltw_config['db_server'];
	$this->db_name 		= $ltw_config['db_name'];
	$this->db_user 		= $ltw_config['db_user'];
	$this->db_pass 		= $ltw_config['db_pass'];
	$this->db_persistent	= $ltw_config['db_persistent'];
	$this->db_connect();
}// end constructor


function db_connect(){
	switch($this->db_type){
	case 1: //mysql
		if ( $this->db_persistent ){
			$this->dbh = @mysql_pconnect($this->db_server, $this->db_user, $this->db_pass);
		}else{
			$this->dbh = @mysql_connect($this->db_server, $this->db_user, $this->db_pass);
		}
		if ( !$this->dbh ){
			echo "Error: Connection to MySQL server ".$this->db_server." failed.<BR>\n";
			return;
		}
		if ( !@mysql_select_db($this->db_name, $this->dbh) ){
			echo "
			Error: Connection to MySQL database ".$this->db_server." failed.<BR>
			------ Code:".@mysql_errno($this->dbh).",<BR>
			------ Message:".@mysql_error($this->dbh)."<BR>
			";
			die ("Error: Fatal db Error.<br>\n");
		}//end mySQL
		 break;
	}//end switch
}// end function.db_connect()

function db_query($query = ''){
	switch( $this->db_type ){
	case 1: //mySql
		$result = mysql_query($query, $this->dbh);
		if ( !$result ){
			echo "
			Error: A problem was encountered while executing this query.<br>
     			\$query<BR><HR>$query<br>
			";
          		die ("Error: Fatal db Error.<br>\n");
        	}
		break;
	}// end switch	
	return $result;
}// end function.db_query()

function db_numrows($result){
	switch( $this->db_type ){
	case 1: //mySQL
		return mysql_num_rows($result);
		break;
	}// end switch
}// end function.db_numrows()

	
function db_fetch_array(&$result){
	switch( $this->db_type ){
	case 1: //mySQL
		return mysql_fetch_array($result);
		break;
	}//end switch
}//end function.db_fetch_array()
	
}//end class ltwDb

/////////////////////////////////////////////////////////////////////////////
// class: ltwEvent - -get/set information about an event in the database.
// 1) instantiate the class    $E = new ltwEvent;
// 2) see if event exists      $E->findbyid($id);
// 3) set parameters           $E->dayevent = 1;
// 4) update (existing) event  $E->update();
//         or
// 5) create new event         $E->create()
//
// 6) delete event		$E->deletebyid($id);
//
// NOTE: Changes to the database are accomplished by calling
//       the update() or create() method.  This allows you 
//       to set multiple parameters and commit them all with
//       one db call.
/////////////////////////////////////////////////////////////////////////////
class ltwEvent{
var $db 	= ''; 
var $table	= '';
var $log 	= '';

// db columns
var $id 		= '';
var $name 		= '';
var $event_date 	= '';
var $event_end		= '';
var $start_time 	= '';
var $end_time 		= '';
var $description 	= '';
var $recurring 		= '';
var $recur_dayofweek	= '';
var $day_event 		= '';
var $cat_id 		= '';
var $location		= '';

//constructor
function ltwEvent(){
	global $ltw_config;

	$this->db	= new ltwDb;
	$this->table 	= $ltw_config['db_table_calendar'];
	$this->log   	= $ltw_config['db_table_log'];

}// end constructor

// create - use this function to create a new event
function create(){
	$query  = "INSERT INTO $this->table ";
	$query .= "(name,event_date,start_time,end_time,description,recurring,recur_dayofweek,";
	$query .= "day_event,cat_id,event_end,location) ";
	$query .= "VALUES (";
	$query .= "'".addslashes($this->name)."',";
	$query .= "'".$this->event_date."',";
	$query .= "'".$this->start_time."',";
	$query .= "'".$this->end_time."',";
	$query .= "'".addslashes($this->description)."',";
	$query .= $this->recurring.",";
	$query .= $this->recur_dayofweek.",";
	$query .= $this->day_event.",";
	$query .= $this->cat_id.",";
	$query .= "'".$this->event_end."',";
	$query .= "'".addslashes($this->location)."'";;
	$query .= ")";
	$result = $this->db->db_query($query);
}//end function.create

// deleteById - use this function to delete an event
function deletebyid($id = ''){
	$query  = "DELETE FROM $this->table  WHERE id = '".$id."'";
	$result = $this->db->db_query($query);
}//end function.deletebyid

// findById - looks up an event in the db and retrieves the information
function findbyid($id = ''){
	// see if the user exists
	$query  = "SELECT * FROM $this->table WHERE id = '" . $id . "'";
	$result = $this->db->db_query($query);

	// exactly one row found ( a good thing!)
	if ( $this->db->db_numrows($result) == 1){
		$row = $this->db->db_fetch_array($result);
		$this->id         	= $row['id'];
		$this->name   		= stripslashes($row['name']);
		$this->event_date   	= $row['event_date'];
		$this->start_time 	= $row['start_time'];
		$this->end_time 	= $row['end_time']; 
		$this->description	= stripslashes($row['description']);
		$this->recurring 	= $row['recurring'];
		$this->recur_dayofweek	= ($row['recur_dayofweek']) ? $row['recur_dayofweek'] : 'NULL';
		$this->day_event 	= $row['day_event'];
		$this->cat_id		= $row['cat_id'];
		$this->event_end   	= $row['event_end'];
		$this->location   	= stripslashes($row['location']);
		return 1;
	}
	return 0;
}// end function.findbyid

// log - handles eventlog inserts
function log ($admin='',$info=''){
	$occured= date("YmdHis");
	$query  = "INSERT into $this->log (type,occured,admin,info) ";
	$query .= "VALUES ('".ELOG."','$occured','$admin','".addslashes($info)."') ";
	$result = $this->db->db_query($query);
}//end function.log()

// update - use this function to update an existing event
function update(){
	$query  = "UPDATE $this->table ";
	$query .= "SET name = '"        .addslashes($this->name)."', ";
	$query .=     "event_date = '"  .$this->event_date."', ";
	$query .=     "start_time = '"	.$this->start_time."', ";
	$query .=     "end_time = '"	.$this->end_time."', ";
	$query .=     "description = '"	.addslashes($this->description)."', ";
	$query .=     "recurring = "	.$this->recurring.", ";
	$query .=     "recur_dayofweek = " .$this->recur_dayofweek.", ";
	$query .=     "day_event = "	.$this->day_event.", ";
	$query .=     "cat_id = "	.$this->cat_id.", ";
	$query .=     "event_end = '"	.$this->event_end."', ";
	$query .=     "location = '"	.addslashes($this->location)."' ";
	$query .= " WHERE id = '".$this->id."'";
	$result = $this->db->db_query($query);
}//end function.update

} //end class.ltwEvent


/////////////////////////////////////////////////////////////////////////////
// class: ltwMail - common eMail interface
//
// 
/////////////////////////////////////////////////////////////////////////////
class ltwMail{
var $db		= '';
var $from 	= '';
var $reply_to	= '';
var $to_list	= '';
var $recips     =  0;
var $subject	= '';
var $msg	= '';
var $headers	= '';
var $table	= '';
var $email_enabled= 0;


function ltwMail(){
	global $ltw_config;

	$this->db 		= new ltwDb;
	$this->table		= $ltw_config['db_table_users'];
	$this->email_enabled	= $ltw_config['email_enabled'];
	$this->from 		= $ltw_config['email_from'];
	$this->reply_to		= $ltw_config['email_reply_to'];
	$this->host 		= $ltw_config['email_host'];

	// if a host different than localhost should be used
	if ( !empty($this->host) )  ini_set('SMTP',$this->host);

	// read the user table for those to recieve email
	$query = "SELECT email from ".$this->table." WHERE (privledges & ".UPEMAIL.") AND email IS NOT NULL";
	$result = $this->db->db_query($query);
	$this->recips = $this->db->db_numrows($result);
	if ( $this->recips > 0 ){
		while ( $row = $this->db->db_fetch_array($result) ){
			$this->to_list .= $row['email'].",";
		}
		$this->to_list[strlen($this->to_list)-1]= ""; //drop last comma
	}

	// set the headers
	$this->headers 	= "MIME-Version: 1.0\r\n";
	$this->headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$this->headers .= "From: ".$this->from."\r\n";
	if ( !empty($this->reply_to) ) $this->headers .= "Reply-To: ".$this->reply_to."\r\n";
	$this->headers .= "To: ".$this->to_list."\r\n";
	$this->headers .= "X-Priority: 1\r\n";
	$this->headers .= "X-MSMail-Priority: High\r\n";
	$this->headers .= "X-Mailer: ltwCalendar\r\n";
	$this->headers .= "Cc: \r\n";


} // end constructor

function send(){
	if ( $this->email_enabled == '0' ) return;
	if ( $this->recips == 0          ) return;

	$result = mail($this->to_list, $this->subject, $this->msg, $this->headers);
	echo "Email Sent";
} // end function.send

}  // end class.ltwMail


/////////////////////////////////////////////////////////////////////////////
// class: ltwUser - -get/set information about a user in the database.
// 1) instantiate the class   $U = new ltwUser;
// 2) see if user exists      $U->findbyname($name);
//                         or $U->findbyid($id);
// 3) set parameters          $U->password = "newpw';
// 4) update (existing) user  $U->update()
//         or
// 5) create new user         $U->create()
//
// 6) delete user	      $U->deletebyname($name);
//			   or $U->deletebyid($id);
//
// NOTE: Changes to the database are accomplished by calling
//       the update() or create() method.  This allows you 
//       to set multiple parameters and commit them all with
//       one db call.
/////////////////////////////////////////////////////////////////////////////
class ltwUser{
var $db 	= '';
var $table  	= '';
var $log    	= '';
var $salt 	= '';
	
// db columns
var $username		= '';
var $password 		= '';
var $email		= '';
var $status 		= '';
var $privledges 	= '';
var $bad_logins 	= '';
var $bad_logins_start	= '';
var $last_pw_change 	= '';

// constructor 
function ltwUser(){
	global $ltw_config;

	$this->db	= new ltwDb;
	$this->table 	= $ltw_config['db_table_users'];
	$this->log   	= $ltw_config['db_table_log'];
	$this->salt 	= $ltw_config['salt'];

}// end constructor

//clrPrivledge = unsets a privledge
function clrPrivledge($bitmask){
	$this->privledges = $this->privledges & (~$bitmask);
}

//clrStatus = unsets a Status
function clrStatus($bitmask){
	$this->status = $this->status & (~$bitmask);
}

// create - use this function to create a new user
function create(){
	$query  = "INSERT INTO $this->table ";
	$query .= " (username,password,email,status,privledges,bad_logins,bad_logins_start,last_pw_change) ";
	$query .= "VALUES ('".addslashes($this->username)."','".$this->password."',";
	$query .= "'".addslashes($this->email)."','".$this->status."','".$this->privledges."',";
	$query .= "'".$this->bad_logins."','".$this->bad_logins_start."',";
	$query .= "'".$this->last_pw_change."'";
	$query .= ")";
	$result = $this->db->db_query($query);
}

// deleteByName - use this function to delete a user by name
function deleteByName($name = ''){
	$query  = "DELETE FROM ".$this->table." WHERE username = '".addslashes($name)."'";
	$result = $this->db->db_query($query);
}//end function.deletebyname

// findByName - looks up a user in the db and retrieves their information
function findByName($name = ''){
	$rc = 0;
	// validate the name
	if (!preg_match('/^\w{1,20}$/',$name))
		return 0;
	// see if the user exists
	$query  = "SELECT * FROM $this->table ";
	$query .= "WHERE username = '" . $name . "'";
	$result = $this->db->db_query($query);

	// exactly one row found ( a good thing!)
	if ( $this->db->db_numrows($result) == 1){
		$row = $this->db->db_fetch_array($result);
		$this->username   	= stripslashes($row['username']);
		$this->password   	= $row['password'];
		$this->email		= stripslashes($row['email']);
		$this->status     	= $row['status'];
		$this->privledges 	= $row['privledges']; 
		$this->bad_logins       = $row['bad_logins'];
		$this->bad_logins_start = $row['bad_logins_start'];
		$this->last_pw_change   = $row['last_pw_change'];
		$rc = 1;
	}
	return $rc;
}// end function.findbyname

//getPrivledge = returns TRUE if a privledge is set
function getPrivledge($bitmask){
	if ( $this->privledges & $bitmask ) return 1; else return 0;
}

//getStatus = returns TRUE if a status is set
function getStatus($bitmask){
	if ( $this->status & $bitmask ) return 1; else return 0;
}

// log - handles userlog inserts
function log ($admin='',$info=''){
	$occured= date("YmdHis");
	$query  = "INSERT into $this->log (type,occured,admin,info) ";
	$query .= "VALUES ('".ULOG."','$occured','$admin','".addslashes($info)."')";
	$result = $this->db->db_query($query);
}//end function.log()

//setPrivledge = sets a privledge
function setPrivledge($bitmask){
	$this->privledges = $this->privledges | $bitmask;
}

//setStatus = sets a Status
function setStatus($bitmask){
	$this->status = $this->status | $bitmask;
}

// update - use this function to update an existing user
function update(){
	$query  = "UPDATE $this->table ";
	$query .= "SET username = '"            .addslashes($this->username)."', ";
	$query .=     "password = '"            .$this->password."', ";
	$query .=     "email    = '"            .addslashes($this->email)."', ";
	$query .=     "status   = '"            .$this->status."', ";
	$query .=     "privledges = '"          .$this->privledges."', ";
	$query .=     "bad_logins = '"          .$this->bad_logins."', ";
	$query .=     "bad_logins_start = '"    .$this->bad_logins_start."', ";
	$query .=     "last_pw_change = '"      .$this->last_pw_change."' ";
	$query .= " WHERE username = '".$this->username."'";
	$result = $this->db->db_query($query);
}

}// end class ltwUser

?>
