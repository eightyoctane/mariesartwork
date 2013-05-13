<?php
////////////////////////////////////////////////////////////////////////////
// ltw_usermgr.php
// $Id: ltwusermgr.php 28 2006-04-01 23:38:13Z mootinator $
//
// ltwCalendar User Manager (add, edit, delete)
////////////////////////////////////////////////////////////////////////////


class ltwUserMgr{
var $auth 		= '';
var $user		= '';
var $php_self		= '';
var $table		= '';
var $salt		= '';
var $loglevel		= '';
var $min_pw_length	= '';
var $pw_strength	= '';


// constructor
function ltwUserMgr(){

	global $ltw_config;
  	global $_POST;
	global $_SERVER;

	$this->auth		= new ltwAuth;
	$this->user 		= new ltwUser;
  	$this->table		= $ltw_config['db_table_users'];
	$this->salt		= $ltw_config['salt'];
	$this->loglevel     	= $ltw_config['uloglevel'];
	$this->min_pw_length	= $ltw_config['min_pw_length'];
	$this->pw_strength	= $ltw_config['pw_strength'];
	$this->php_self		= $_SERVER['PHP_SELF'];
} // end constructor

function manage() {
	global $_POST;
	
	if ( !$this->auth->checkLogin() ){
		$this->auth->notLoggedIn();
		return 0;
	}

	if ( !$this->auth->user->getPrivledge(UPADMIN) ){
		$this->auth->notPrivledged();
		return 0;
	}

	// if "Done" was clicked on the "top" formA
	if ( isset($_POST['Done']) ){ 
  		jsClosePopupReloadMain($this->php_self);
  		return;
	}


	// display the top form
	header("Cache-control: no-cache");
	header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");

	echo "
	<html>
	<head><title>Manage Users</title>
	</head>	
	<body>

	<!-- common, displays every page -->
	<table border=\"1\" >
	<tr>
		<td>Find User:</td>
  		<td>
    			<form name=\"topfind\" method=\"POST\" action=\"".$this->php_self."?display=admin&task=users\">
			<input type=\"text\" name=\"username\" size=\"20\">
			<input type=\"submit\" value=\"Go!\" name=\"EditFind\">&nbsp;&nbsp;
			Limit to 
			<input type=\"text\" name=\"Limit\" size=\"4\"> names.
			</form>
		</td>
	</tr>
	<tr>
		<td>Add User:</td>
		<td>
			<form name=\"topadd\" method=\"POST\" action=\"".$this->php_self."?display=admin&task=users\">
			<input type=\"text\" name=\"username\" size=\"20\">
			<input type=\"submit\" value=\"Go!\" name=\"AddFind\">
			</form>
  		</td>
	</tr>
	<tr>
  		<td colspan=\"2\">
			<form name=\"topdone\" method=\"POST\" action=\"".$this->php_self."?display=admin&task=users\">
			<input type=\"submit\" value=\"Done\" name=\"Done\">
			</form>
		</td>
	</tr>
	</table>
	<!-- end common table -->
	";

	// if "find" was clicked on the "top" form
	if ( isset($_POST['EditFind']) ){
		$this->displayList($_POST['username'],$_POST['Limit']);

	// If "Add" was clicked on the "top" form	
	}elseif ( isset($_POST['AddFind']) ){
  		if ( $this->user->findByName($_POST['username']) ){
			echo "User ".$_POST['username']." already exists and can't be added.";
  		}else{
    			$this->displayEdit('add');
  		}

	// If "Add" was clicked on the "input" form 
	}elseif ( isset($_POST['AddInsert']) ){
		if ( $this->user->findByName($_POST['username']) ){
			$msg = "User ".$_POST['username']." already exists and can't be added.";
		}else{
			$errors = $this->addUser();
			if ( !empty($errors) ){
				$this->displayEdit('add',$errors);
			}else{
				$this->displayList();
			}
  		}

	// If "Edit" was clicked on the "list" form
	}elseif ( isset($_POST['EditUser']) ){
		if ( !$this->user->findByName($_POST['username']) ){
			echo "User ".$_POST['username']." does not exist.";
		}else{
			$this->user2_Post();
			$this->displayEdit('edit');
  		}

	// if "Edit" was clicked on the "input" form
	}elseif ( isset($_POST['EditUpdate'] ) ){
		if ( !$this->user->findByName($_POST['username']) ){
			echo "User ".$_POST['username']." does not exist.";
		}else{
			$errors = $this->editUser();
			if ( !empty($errors) ){
				$this->displayEdit('edit',$errors);
			}else{
				$this->displayList();
			}
  		}

	// if "Delete" was clicked on the "list" form  
	}elseif ( isset($_POST['EditDelete']) ){
		if ( !$this->user->findByName($_POST['username']) ){
			echo "User ".$_POST['username']." does not exist.";
  		}else{
    			$this->deleteUser();
			$this->displayList();	
  		}
	}else{
		$this->displayList();
	}

	echo "</body></html>";
	return;
} // end function.manage

function user2_Post(){

	$_POST['username'] = $this->user->username;
	$_POST['email']	   = $this->user->email;
	if ( $this->user->getStatus(USLOCKED) ) $_POST['USLOCKED'] = 'on';
	if ( $this->user->getStatus(USCHGPW)  ) $_POST['USCHGPW'] = 'on';
	if ( $this->user->getPrivledge(UPREAD)) $_POST['UPREAD'] = 'on';
	if ( $this->user->getPrivledge(UPEDIT)) $_POST['UPEDIT'] = 'on';
	if ( $this->user->getPrivledge(UPEMAIL))$_POST['UPEMAIL'] = 'on';
	if ( $this->user->getPrivledge(UPLOGS)) $_POST['UPLOGS'] = 'on';
	if ( $this->user->getPrivledge(UPADMIN))$_POST['UPADMIN'] = 'on';
	$_POST['bad_logins'] = $this->user->bad_logins;
	$_POST['bad_logins_start'] = $this->user->bad_logins_start;
	$_POST['last_pw_change'] = $this->user->last_pw_change;

} // end function.user2_Post

function displayEdit($mode='',$errors=''){

	echo "<br>
	<form name=\"edit\" method=\"post\" action=\"".$this->php_self."?display=admin&task=users\">
	<table border=\"1\">
	<tr><td align=\"right\"><b>Username</b></td><td><input type=\"text\" width=\"20\" size=\"20\" name=\"username\"
	";

	if ( !empty($_POST['username']) ) echo "value=\"".$_POST['username']."\" ";
	echo "></td></tr>\n";
	
	echo "
	<tr><td align=\"right\"><b>Password<br>Again</b></td>
	    <td><input type=\"password\" width=\"20\" size=\"20\" name=\"pw\"><br>
		<input type=\"password\" width=\"20\" size=\"20\" name=\"pwa\">
	    </td>
	</tr>
	<tr><td align=\"right\"><b>Email Addr</b></td>
	    <td><input type=\"text\" size=\"20\" width=\"100\" name=\"email\" 
	";

	if ( !empty($_POST['email']) ) echo "value=\"".$_POST['email']."\" ";
	echo "
	></td></tr>
	<tr><td align=\"right\"><b>Status</b></td>
	    <td><input type = \"checkbox\" name=\"USLOCKED\" 
	";
	if ( isset($_POST['USLOCKED']) ) echo "checked";
	
	echo "
	>Account Locked<br>
	<input type = \"checkbox\" name=\"USCHGPW\" 
	";
	if ( isset($_POST['USCHGPW']) ) echo "checked";

	echo "
	>Must Change Pw<br></td></tr>
	";

	echo "
	<tr><td align=\"right\"><b>Privledges</b></td>
	    <td><input type = \"checkbox\" name=\"UPREAD\" 
	";
	if ( isset($_POST['UPREAD']) ) echo "checked";

	echo "
	>Read<br>
	<input type = \"checkbox\" name=\"UPEDIT\" 
	";
	if ( isset($_POST['UPEDIT']) ) echo "checked";

	echo "
	>Edit<br>
	<input type = \"checkbox\" name=\"UPEMAIL\" 
	";
	if ( isset($_POST['UPEMAIL']) ) echo "checked";

	echo "
	>Send Email<br>
	<input type = \"checkbox\" name=\"UPLOGS\" 
	";
	if ( isset($_POST['UPLOGS']) ) echo "checked";

	echo "
	>Log Read<br>
	<input type = \"checkbox\" name=\"UPADMIN\" 
	";
	if ( isset($_POST['UPADMIN']) ) echo "checked";

	echo "
	>Administrator<br>
	</td></tr>
	<tr><td align=\"right\"><b>Bad Logins<br>Started</b></td><td valign=\"top\">";
	if ( isset($_POST['bad_logins']) ){
		echo "<input type=\"text\" name=\"bad_logins\" size=\"2\" width=\"2\" value=\"".$_POST['bad_logins']."\"><br>\n";
	}else{
		echo "&nbsp;<br>\n";
	}
	if ( isset($_POST['bad_logins_start']) ){
		echo $_POST['bad_logins_start']."\n" ;
	}else{
		echo "&nbsp;\n";
	}
	
	echo "
	</td></tr>
	<tr><td align=\"right\"><b>Last Pw Change</b></td><td>
	";

	if ( isset($_POST['last_pw_change']) && !empty($_POST['last_pw_change']) ){
		echo $_POST['last_pw_change']."\n";
	}else{
		echo "&nbsp;\n";
	}
	echo "
	</td></tr>
	<tr><td colspan=\"2\">
	";
	
	switch ($mode){
	case 'add' : 
		echo "<input type=\"submit\" name=\"AddInsert\" value=\"Add\">";
		break;
	case 'edit':
		echo "<input type=\"submit\" name=\"EditUpdate\" value=\"Update\">";
		break;
	}
	echo"
	</table></form>
	".$errors."<br>".$this->auth->pwRules()
	;
	
} // end function.displayEdit

function displayList($username='',$limit=''){

	$query  = "SELECT username FROM ".$this->table." WHERE 1 ";
 
	if ( !empty($username) ) $query .= " AND username LIKE '%".addslashes($username)."%' ";

	$query .= "ORDER BY username ";

	if ( empty($limit) ) $limit = 50;	
	$query .= "LIMIT 0,".$limit." ";

	$result = $this->user->db->db_query($query);

	if ( !$this->user->db->db_numrows($result) ){
		echo "No users found.<br>";
		return;
	}

	echo "Displaying (upto) ".$limit." users.";
 	
	echo "
	<!-- list table (one row for each returned name) -->
	<table border=\"1\">
	<tr><td><b>Username</b></td><td>Function</td></tr>
	";

	while ( $row = $this->user->db->db_fetch_array($result) ){
		echo "
		<!-- this repeats for each row returned -->
		<tr>
	  	  <td>".stripslashes($row['username'])."</td>
		  <td>
		    <form method=\"POST\" action=\"".$this->php_self."?display=admin&task=users\">
		      <input type=\"hidden\" value=\"".$row['username']."\" name=\"username\">
		      <input type=\"submit\" value=\"Edit\" name=\"EditUser\">&nbsp;&nbsp;&nbsp;&nbsp;
		      <input type=\"submit\" value=\"Delete\" name=\"EditDelete\">
		    </form>
		  </td>
		</tr>
		<!-- end repeating section -->
		";
	}

	echo "
	</table>
	<!-- end list table -->
	";
} // end function.displayList()


function addUser(){
	$errors = '';
	$logm	= '';
	$logd	= '';
	$this->user->privledges = 0;
	$this->user->status 	= 0;
	$this->user->bad_logins = 0;

	if ( empty($_POST['username']) || !preg_match('/^\w{1,20}$/', $_POST['username'])) $errors .= "Bad username!<br>";

	$this->user->username = $_POST['username'];
	$logm .= "<b>User: Add ".$_POST['username']."</b><br>";

	$rstr = $this->auth->isPasswordValid($_POST['username'],'',$_POST['pw'],$_POST['pwa']);
	if ( !empty($rstr) ){
		$errors = $rstr;
		return $errors;
	}

	// success, set the pw
	$this->user->password = crypt($_POST['pw'], $this->salt);
	$this->user->last_pw_change = date("Y-m-d H:I:s");
	$logd .= "Password set<br>";
	$logd .= "Last_Pw_Change: ".$this->user->last_pw_change."<br>";


	// set email address
	if ( !empty($_POST['email']) ){
		$this->user->email  = $_POST['email'];
		$logd .= "Email: ".$this->user->email."<br>";
	}

	// set Status & Privs
	$this->user->status = 0;		
	if ( !empty($_POST['USLOCKED']) ){
		$this->user->setStatus(USLOCKED);
		$state = "On";
	}else{
		$state = "Off";
	}
	$logd .= "Locked Status: ".$state."<br>";

	if ( !empty($_POST['USCHGPW']) ){
		$this->user->setStatus(USCHGPW);
		$state = "On";
	}else{
		$state = "Off";
	}
	$logd .= "Change Pw Status: ".$state."<br>";

	if ( !empty($_POST['UPREAD']) ){
		$this->user->setPrivledge(UPREAD);
		$state = "On";
	}else{
		$state = "Off";
	}
	$logd .= "Read Priv: ".$state."<br>";

	if ( !empty($_POST['UPEDIT']) ){
		$this->user->setPrivledge(UPEDIT);
		$state = "On";
	}else{
		$state = "Off";
	}
	$logd .= "Edit Priv: ".$state."<br>";

	if ( !empty($_POST['UPEMAIL']) ){
		$this->user->setPrivledge(UPEMAIL);
		$state = "On";
	}else{
		$state = "Off";
	}
	$logd .= "Email Priv: ".$state."<br>";

	if ( !empty($_POST['UPLOGS']) ){
		$this->user->setPrivledge(UPLOGS);
		$state = "On";
	}else{
		$state = "Off";
	}
	$logd .= "Logs Priv: ".$state."<br>";

	if ( !empty($_POST['UPADMIN']) ){
		$this->user->setPrivledge(UPADMIN);
		$state = "On";
	}else{
		$state = "Off";
	}
	$logd .= "Admin Priv: ".$state."<br>";

	if ( empty($errors) ){
		$this->user->create();
		$this->notifier($logm,$logd);
	}
	return $errors;
} // end function.addUser


function deleteUser(){
	$errors = '';
	$logm 	= '';
	$logd 	= '';

	if ( empty($_POST['username']) ){
		$errors .= "Error, no username to delete";
		return $errors;
	}
	
		
	$logm  = "<b>User: Deleted ".$_POST['username']."</b><br>";
	$logd .= "Email: ".$this->user->email."<br>";
	$logd .= "Status: ".$this->user->status."<br>";
	$logd .= "Privs: ".$this->user->privledges."<br>";
	$logd .= "Bad_logins: ".$this->user->bad_logins."<br>";
	$logd .= "Bad_logins_start: ".$this->user->bad_logins_start."<br>";
	$logd .= "Last_pw_change: ".$this->user->last_pw_change."<br>";
	 
 	$this->user->deleteByName($_POST['username']);
	$this->notifier($logm,$logd);
} // end function.deleteUser			


function editUser(){	
	$errors = '';
	$logm	= '';
	$logd	= '';
	$updateit= 0;

	if ( !isset($_POST['username']) ){
		$errors = "Error, no username to edit";
		return $errors;
	}
		
	$logm = '<b>User: Update '.$this->user->username.'</b><br>';
		
	if ( !empty($_POST['username']) ){
		if ( $this->user->username != $_POST['username'] ){
			$logd .= "Name: ".$this->user->username." -> ".$_POST['username']."<br>";
			$this->user->username = $_POST['username'];
			$updateit = 1;
		}
	}

	if ( !empty($_POST['pw']) ){
		$rstr = $this->auth->isPasswordValid($_POST['username'],'',$_POST['pw'],$_POST['pwa']);
		if ( !empty($rstr) ){
			$errors = $rstr;
			return $errors;
		}else{
			$logd .= 'Password: Changed<br>';
			$this->user->password = crypt($_POST['pw'],$this->salt);	
			$this->user->last_pw_change = date("Y-m-d H:I:s");
			$logd .= "Last_Pw_Change: ".$this->user->last_pw_change."<br>";
			$updateit = 1;
		}
	}

	if ( $this->user->email != $_POST['email'] ){
		$logd .= "Email: ".$this->user->email.' -> '.$_POST['email'].'<br>';
		$this->user->email  = $_POST['email'];
		$updateit = 1;
	}

		
	if ( !empty($_POST['USLOCKED']) ) $tstate = 1; else $tstate = 0;
	if ( $this->user->getStatus(USLOCKED) != $tstate ){
		if ( $tstate == 0 ){
			$state = "Off";
			$this->user->clrStatus(USLOCKED);
		}else{
			$state = "On";
			$this->user->setStatus(USLOCKED);
		}
		$logd .= "Locked Status: ".$state."<br>";
		$updateit = 1;
	}
		

	if ( !empty($_POST['USCHGPW']) ) $tstate = 1; else $tstate = 0;
	if ( $this->user->getStatus(USCHGPW) != $tstate ){
		if ( $tstate == 0 ){
			$state = "Off";
			$this->user->clrStatus(USCHGPW);
		}else{
			$state = "On";
			$this->user->setStatus(USCHGPW);
		}
		$logd .= "Change Pw Status: ".$state."<br>";
		$updateit = 1;
	}

	if ( !empty($_POST['UPREAD']) ) $tstate = 1; else $tstate = 0;
	if ( $this->user->getPrivledge(UPREAD) != $tstate ){
		if ( $tstate == 0 ){
			$state = "Off";
			$this->user->clrPrivledge(UPREAD);
		}else{
			$state = "On";
			$this->user->setPrivledge(UPREAD);
		}
		$logd .= "Read Privledge: ".$state."<br>";
		$updateit = 1;
	}

	if ( !empty($_POST['UPEDIT']) ) $tstate = 1; else $tstate = 0;
	if ( $this->user->getPrivledge(UPEDIT) != $tstate ){
		if ( $tstate == 0 ){
			$state = "Off";
			$this->user->clrPrivledge(UPEDIT);
		}else{
			$state = "On";
			$this->user->setPrivledge(UPEDIT);
		}
		$logd .= "Edit Privledge: ".$state."<br>";
		$updateit = 1;
	}

	if ( !empty($_POST['UPEMAIL']) ) $tstate = 1; else $tstate = 0;
	if ( $this->user->getPrivledge(UPEMAIL) != $tstate ){
		if ( $tstate == 0 ){
			$state = "Off";
			$this->user->clrPrivledge(UPEMAIL);
		}else{
			$state = "On";
			$this->user->setPrivledge(UPEMAIL);
		}
		$logd .= "Email Privledge: ".$state."<br>";
		$updateit = 1;
	}

	if ( !empty($_POST['UPLOGS']) ) $tstate = 1; else $tstate = 0;
	if ( $this->user->getPrivledge(UPLOGS) != $tstate ){
		if ( $tstate == 0 ){
			$state = "Off";
			$this->user->clrPrivledge(UPLOGS);
		}else{
			$state = "On";
			$this->user->setPrivledge(UPLOGS);
		}
		$logd .= "Logs Privledge: ".$state."<br>";
		$updateit = 1;
	}

	if ( !empty($_POST['UPADMIN']) ) $tstate = 1; else $tstate = 0;
	if ( $this->user->getPrivledge(UPADMIN) != $tstate ){
		if ( $tstate == 0 ){
			$state = "Off";
			$this->user->clrPrivledge(UPADMIN);
		}else{
			$state = "On";
			$this->user->setPrivledge(UPADMIN);
		}
		$logd .= "Admin Privledge: ".$state."<br>";
		$updateit = 1;
	}

	if ( !empty($_POST['bad_logins']) ){
		if ( $this->user->bad_logins != $_POST['bad_logins'] ){
			$logd .= "Bad Logins: ".$this->user->bad_logins." -> ".$_POST['bad_logins']."<br>";
			$this->user->bad_logins = $_POST['bad_logins'];
			$this->user->bad_logins_start = time();
			$logd .= "Started: ".$this->user->bad_logins_start;
			$updateit = 1;
		}
	}

	if ( $updateit ){
		$this->user->update();
		$this->notifier($logm,$logd);
	}
	return $errors;

} // end function.updateUser

// Notifier:
// sends the appropriate notifications to the log
//
function notifier($logm='', $logd=''){
	if ( $this->loglevel != 0 ) {
		if ( $this->loglevel & ULDETAIL ){
			$this->user->log($this->auth->user->username,$logm.$logd);
		}else{
			$this->user->log($this->auth->user->username,$logm);
		}
	}

} // end function.notifier

} // end class.ltwUsers
?>
