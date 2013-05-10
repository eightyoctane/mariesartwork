<?php
////////////////////////////////////////////////////////////////////////////
// ltwdbmgr.php
// $Id: ltwdbmgr.php 28 2006-04-01 23:38:13Z mootinator $
//
// ltwCalendar db Cleanup Tool
////////////////////////////////////////////////////////////////////////////


class ltwDbMgr {
var $db   	= '';
var $auth 	= '';
var $ltable	= '';
var $etable	= '';
var $logit	= '';
var $php_self 	= '';

// constructor
function ltwDBMgr(){
	global $_POST;
	global $_SERVER;
	global $ltw_config;

	$this->db	= new ltwDb;
	$this->auth	= new ltwAuth;
	$this->ltable	= $ltw_config['db_table_log'];
	$this->etable	= $ltw_config['db_table_calendar'];
	$this->logit	= $ltw_config['eloglevel']+$ltw_config['cloglevel']+$ltw_config['uloglevel'];

	$this->php_self	= $_SERVER['PHP_SELF'];
} // end constructor

function manage(){
	global $_POST;
	
	$errors 	= '';
				
	if ( !$this->auth->checkLogin() ){
		$this->auth->notLoggedIn();
		return 0;
	}

	if ( !$this->auth->user->getPrivledge(UPADMIN) ){
		$this->auth->notPrivledged();
		return 0;
	}

	// 'Done' Button Pressed
	if ( !empty($_POST['Done']) ){
		echo "SAW DONE!!";
		jsClosePopupReloadMain($this->php_self."?display=month");
		return;
	}

	// "No" clicked on confirm page
	if ( !empty($_POST['Back']) ){
                jsReloadPopup($this->php_self."?display=admin&task=dbmaint");
		return;
	}

	// Check dates are formatted right
	if ( !empty($_POST['eDate']) ){
		if ( !$this->_checkPDate($_POST['eDate']) ){
			$errors .= "Invalid date (".$_POST['eDate'].") entered for Events!<br>";
		}
	}
	if ( !empty($_POST['lDate']) ){
		if ( !$this->_checkPDate($_POST['lDate']) ){
			$errors .= "Invalid date (".$_POST['lDate'].") entered for Log!<br>";
		}
	}

	// Submit button pressed
	// Ask for verification
	if ( empty($errors) ){
		if ( !empty($_POST['Submit']) && (!empty($_POST['eDate']) || !empty($_POST['lDate'])) ){
			echo "
			<html>
	       		<head></head>
		        <body>
			<h3>Database Maintainence</h3>
			<form name=\"dbmaint\" method=\"post\" action=\"".$this->php_self."?display=admin&task=dbmaint\">
			Are you sure you want to delete:<br><br>
			";
			if ( !empty($_POST['eDate']) ){
				$edelete = $this->_row_count($this->etable,"event_date",$_POST['eDate']);
				echo "<b>".$edelete." Event entries</b> prior to ".$_POST['eDate']."?<br>";
				echo "<input type=\"hidden\" name=\"eDate\" value=\"".$_POST['eDate']."\"> ";
				echo "<input type=\"hidden\" name=\"ecount\" value=\"".$_POST['ecount']."\"> ";
				echo "<input type=\"hidden\" name=\"edelete\" value=\"".$edelete."\"> ";
			}
			if ( !empty($_POST['lDate']) ){
				$ldelete = $this->_row_count($this->ltable,"occured",preg_replace('/-/','',$_POST['lDate'])."000000");
				echo "<b>".$ldelete." Log&nbsp;&nbsp;entries</b> prior to ".$_POST['lDate']."?<br>";
				echo "<input type=\"hidden\" name=\"lDate\" value=\"".$_POST['lDate']."\"> ";
				echo "<input type=\"hidden\" name=\"lcount\" value=\"".$_POST['lcount']."\"> ";
				echo "<input type=\"hidden\" name=\"ldelete\" value=\"".$ldelete."\"> ";
			}
			echo "<br>
			<input type=\"submit\" name=\"DoItAlready\" value=\"Yes\">&nbsp;&nbsp;&nbsp;
			<input type=\"submit\" name=\"Back\" value=\"No\">
			</form></body></html>
			";
			return;
		}
	}

	// DoIt button Pressed!
	//
	if ( empty($errors) ){
		if ( !empty($_POST['DoItAlready']) ){
			if ( !empty($_POST['eDate']) ){
				$query  = "DELETE FROM ".$this->etable." ";
				$query .= "WHERE event_date < '".$_POST['eDate']."' ";
				$result = $this->db->db_query($query);
			        if ( !$result ) die("<br><b>Could not delete old events.</b>");
				$errors .= "Deleted ".$_POST['edelete']." Events older than ".$_POST['eDate'].".<br>";
			}
	                if ( !empty($_POST['lDate']) ){
				$ldate = preg_replace('/-/','',$_POST['lDate'])."000000";
        	                $query  = "DELETE FROM ".$this->ltable." ";
                	        $query .= "WHERE occured < '".$ldate."' ";
                        	$result = $this->db->db_query($query);
	                        if ( !$result ) die("<br><b>Could not delete old events.</b>");
				$errors .= "Deleted ".$_POST['ldelete']." Log Entries older than ".$_POST['lDate'].".<br>";

        	        }
			// if ANY logging being done, 
			// log the deletions
			if ( $this->logit && !empty($errors) ){
				$this->auth->user->log($this->auth->user->username,$errors);
			}
		}
	}

	// Display the form
	$ecount = $this->_row_count($this->etable);
	$eold   = $this->_oldest_entry($this->etable,"event_date");
	$lcount = $this->_row_count($this->ltable);
	$ltmp	= $this->_oldest_entry($this->ltable,"occured");
	$lold	= substr($ltmp,0,4).'-'.substr($ltmp,4,2).'-'.substr($ltmp,4,2);

	echo "
	<html>
	<head></head>
	<body>
	<h3>Database Maintainence</h3>
	<form name=\"dbmaint\" method=\"post\" action=\"".$this->php_self."?display=admin&task=dbmaint\">
	Event Table:  ".$ecount." <input type=\"hidden\" name=\"ecount\" value=\"".$ecount."\"> entries,&nbsp;&nbsp;
	earliest one dated: ".$eold."<br>
	Delete Events prior to (yyyy-mm-dd) <input type=\"text\" size=\"11\" maxlength=\"11\" name=\"eDate\">
	<br><br>
	Log Table:  ".$lcount." <input type=\"hidden\" name=\"lcount\" value=\"".$lcount."\"> entries,&nbsp;&nbsp;
	earliest one dated: ".$lold."<br>
        Delete Entries prior to (yyyy-mm-dd) <input type=\"text\" size=\"11\" maxlength=\"11\" name=\"lDate\">
        <br><br>
	<input type=\"submit\" name=\"Submit\" value=\"Delete\">
	&nbsp;&nbsp;
	<input type=\"submit\" name=\"Done\" value=\"Done\">
	</form><br>
	".$errors."
	</body></html>
	";
} // end function.display

function _row_count($table='',$column= '', $value=''){
	$query = "SELECT COUNT(*) FROM ".$table." ";
	if ( !empty($column) ) $query .= "WHERE ".$column." < '".$value."' ";
	$result = $this->db->db_query($query);
	if ( !$result ) die("<br><b>Could not count rows in ".$table);
	$row = $this->db->db_fetch_array($result);
	return $row[0];
}

function _oldest_entry($table='',$column=''){
	$query = "SELECT MIN(".$column.") FROM ".$table." ";
        $result = $this->db->db_query($query);
        if ( !$result ) die("<br><b>Could not get oldest row for ".$column." in ".$table);
	$row = $this->db->db_fetch_array($result);
        return $row[0];
}

// checks a date is in the format yyyy-mm-dd
function _checkPDate ($pdate=''){
        if ( preg_match ('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',$pdate) ){
                return 1;
        }else{
                return 0;
        }
} // end function._checkPDate

}// end class.ltwDbMgr

?>
