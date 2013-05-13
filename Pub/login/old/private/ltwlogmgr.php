<?php
////////////////////////////////////////////////////////////////////////////
// ltw_logmgr.php
// $Id: ltwlogmgr.php 5 2006-03-12 08:15:51Z mootinator $
//
// ltwCalendar Log Viewer
////////////////////////////////////////////////////////////////////////////


class ltwLogMgr {
var $db   = '';
var $auth = '';
var $log  = '';
var $php_self = '';

// constructor
function ltwLogMgr(){
	global $_POST;
	global $_SERVER;
	global $ltw_config;

	$this->db	= new ltwDb;
	$this->auth	= new ltwAuth;
	$this->log	= $ltw_config['db_table_log'];
	$this->php_self	= $_SERVER['PHP_SELF'];
} // end constructor

function display(){
	global $_POST;
			
	if ( !$this->auth->checkLogin() ){
		$this->auth->notLoggedIn();
		return 0;
	}

	if ( !$this->auth->user->getPrivledge(UPEDIT+UPADMIN) ){
	  $this->auth->notPrivledged();
		return 0;
	}

	// 'Done' Button Pressed
	if ( !empty($_POST['Done']) ){
		jsClosePopupReloadMain($this->php_self."?display=month");
		return;
	}
		

	// Display the selection form at the top
	echo "
	<html>
	<head></head>
	<body>
	<form name=\"logname\" method=\"post\" action=\"".$this->php_self."?display=admin&task=logs\">
	<table border=\"1\">
	<tr align=\"center\"><td><b>Log</b></td>
	<td colspan=\"2\"><b>Filters</td></tr>
	";

	echo "<tr><td><input type=\"radio\" name=\"log\" value=\"all\" ";
	if ( empty($_POST['log']) || (!empty($_POST['log']) && ($_POST['log'] === 'all')) ) echo "checked";
	echo ">All logs<br>\n";

	echo "<input type=\"radio\" name=\"log\" value=\"user\" ";
	if ( isset($_POST['log']) && ($_POST['log'] === 'user') ) echo "checked";
	echo ">User  Log<br>\n";

	echo "<input type=\"radio\" name=\"log\" value=\"event\" ";
	if ( isset($_POST['log']) && ($_POST['log'] === 'event') ) echo "checked";
	echo ">Event Log<br>\n";

	echo "<input type=\"radio\" name=\"log\" value=\"category\" ";
	if ( isset($_POST['log']) && ($_POST['log'] === 'category') ) echo "checked";
	echo ">Category Log</font></td>\n";

	echo "<td align=\"right\">Admin:<br>Info:<br>Start<font size=\"-1\"> (yyyymmdd):</font><br>End<font size=\"-1\"> (yyyymmdd):</font><br>Rows:</td>";

	echo "<td><input size=\"30\" type=\"text\" name=\"admin\" ";
	if ( isset($_POST['admin']) ) echo "value=\"".$_POST['admin']."\""; 
	echo "><br>\n";

	echo "<input size=\"30\" type=\"text\" name=\"info\" ";
	if ( isset($_POST['info']) ) echo "value=\"".$_POST['info']."\""; 
	echo "><br>\n";

	echo "<input size=\"30\" type=\"text\" name=\"start\" ";
	if ( isset($_POST['start']) ) echo "value=\"".$_POST['start']."\""; 
	echo "><br>\n";

	echo "<input size=\"30\" type=\"text\" name=\"end\" ";
	if ( isset($_POST['end']) ) echo "value=\"".$_POST['end']."\""; 
	echo "><br>\n";

	echo "<input type=\"text\" name=\"limit\" width=\"3\" size=\"3\" ";
	if ( isset($_POST['limit']) ) echo "value=\"".$_POST['limit']."\""; 
	echo "></td></tr>\n";
	
	echo "
	<tr><td align=\"left\" colspan=\"3\">
	<input type=\"submit\" name=\"View\" value=\"View\">&nbsp;&nbsp;
	<input type=\"submit\" name=\"Done\" value=\"Done\"></td>
	</tr>
	</table>
	</form>
	";

	// Return if no log name selected
	if ( !isset($_POST['log']) ) return;
			
	// start sql statements
	switch ($_POST['log']){
	case 'user': 
		$query  = "SELECT * FROM ".$this->log." WHERE type = ".ULOG." ";
		break;

	case 'event': 
		$query  = "SELECT * FROM ".$this->log." WHERE type = ".ELOG." ";
		break;

	case 'category': 
		$query  = "SELECT * FROM ".$this->log." WHERE type = ".CLOG." ";
		break;

	case 'all':
	default: 
		$query  = "SELECT * FROM ".$this->log." WHERE 1 ";
		break;
	}

	if ( !empty($_POST['admin'])	) $query .= "AND admin = '".$_POST['admin']."' ";
	if ( !empty($_POST['info']) 	) $query .= "AND info like '%".$_POST['info']."%' ";
	if ( !empty($_POST['start'])	) $query .= "AND occured >= ".$_POST['start']."000000 ";
	if ( !empty($_POST['end'])  	) $query .= "AND occured <= ".$_POST['end']."999999 ";

	$query .= "ORDER BY id DESC ";
	if ( !empty($_POST['limit']) ) $query .= "LIMIT 0,".$_POST['limit']." ";
		
	$result  = $this->db->db_query($query);     // rows to view

  	echo "
	<table border=\"1\">
	<tr><td><b>Log Id</b></td><td><b>Occured</b></td><td><b>Admin</b></td><td><b>Info</b></td></tr>
	";

	while ( $row = $this->db->db_fetch_array($result) ){
		echo "<tr><td>".$row['id']."</td><td>".$row['occured']."</td><td>";
		if ( !empty($row['admin']) ) echo $row['admin']; else echo "&nbsp;";
		echo "</td><td>".stripslashes($row['info'])."</td></tr>\n";
	}

	echo "
	</table>
	</body></html>
	";

} // end function.display

}// end class.ltwLogMgr

?>
