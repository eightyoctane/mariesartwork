<?php
////////////////////////////////////////////////////////////////////
// Class ltwCalendar
// $Id: ltwdisplayevent.php 5 2006-03-12 08:15:51Z mootinator $
//
// Displays the calendar in month, day, and event formats
////////////////////////////////////////////////////////////////////

class ltwCalendar {
var $db 		= '';
var $auth 		= '';
var $stamp 		= '';
var $ctable		= '';
var $daynames 		= '';
var $hrs_per_day      	= '';

var $catA		= array(); 	// Array for holding Category Names & colors
var $cat_fgcolor	= ''; 		// Default category color (failsafe)
var $cat_bgcolor	= ''; 		// Default category color (failsafe)
var $category_table	= ''; 		// Table name

var $login_req 		= '';
var $week_starts_monday	= 0;
var $php_self		= '';

var $returnto		= 'month';

// constructor
function ltwCalendar($timestamp) {
	global $ltw_config;
	global $_REQUEST;
	global $_SERVER;
		
	$this->db 			= new ltwDb;
	$this->auth			= new ltwAuth;
	$this->php_self			= $_SERVER['PHP_SELF'];
	$this->week_starts_monday 	= $ltw_config['week_starts_monday'];
	$this->stamp		 	= $timestamp;
	$timepieces 			= getdate($timestamp);
		
	$this->ctable 			= $ltw_config['db_table_calendar'];
	$this->daynames 		= $ltw_config['daynames'];
	$this->hrs_per_day 		= $ltw_config['hrs_per_day'];
	$this->login_req		= $ltw_config['login_required'];

	if ( isset($_REQUEST['cat_ids'])  ) $this->cat_ids = $_REQUEST['cat_ids'];
	if ( isset($_REQUEST['returnto']) ) $this->returnto  = $_REQUEST['returnto'];

	$this->cat_fgcolor		= $ltw_config['cat_fgcolor'];
	$this->cat_bgcolor		= $ltw_config['cat_bgcolor'];
	$this->cat_table  		= $ltw_config['db_table_category'];

	// read the category table into an array
	$query = "SELECT * from ". $this->cat_table;
	$result = $this->db->db_query($query);
	while($row = $this->db->db_fetch_array($result) ){
		$this->catA[$row['id']] = array(stripslashes($row['name']),stripslashes($row['fgcolor']),stripslashes($row['bgcolor']));
	}
		
} //end constructor


function displayEvent($id, $date) {
	if ( $this->login_req == 1  && !$this->auth->checkLogin() ){
		$this->auth->notLoggedIn();
		exit;
	}

	$query 	= "SELECT * FROM $this->ctable WHERE id=" . $id;
	$result = $this->db->db_query($query);
	$row 	= $this->db->db_fetch_array($result);

	header("Cache-control: no-cache");
	header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");
	echo "<html><head></head><body>";

	$sdate = date("F j, Y",strtotime($row['event_date']));
	$edate = date("F j, Y",strtotime($row['event_end']));

	if ( $this->hrs_per_day == 12 ){
		$stime = strftime("%I:%M%p",strtotime($row["start_time"]));
		$etime = strftime("%I:%M%p",strtotime($row["end_time"]));
	}else{
		$stime = strftime("%H:%M",strtotime($row["start_time"]));
		$etime = strftime("%H:%M",strtotime($row["end_time"]));
	}
	if ( $stime{0} == '0') $stime = substr($stime, 1);
	if ( $etime{0} == '0') $etime = substr($etime, 1);

	if ( isset($this->catA[$row['cat_id']]) ){
		$fgc = $this->catA[$row['cat_id']][1];
		$bgc = $this->catA[$row['cat_id']][2];
	}else{
		$fgc = $this->cat_fgcolor; 
		$bgc = $this->cat_bgcolor; 
	}

	echo "<table><tr><td bgcolor=\"".$bgc."\"><font color=\"".$fgc."\"><b>".stripslashes($row['name'])."</b></font></td></tr></table>\n";
	if ( !empty($row['location']) )echo "at <b>".stripslashes($row['location'])."</b><br>";
	if ( $this->auth->checkLogin() && $this->auth->user->getPrivledge(UPEDIT+UPADMIN) ){
		echo "
		<a href=\"".$this->php_self."?display=admin&amp;task=edit&amp;id="   . $row["id"] . "&amp;returnto=".$this->returnto."\">edit</a>&nbsp;&nbsp;
		<a href=\"".$this->php_self."?display=admin&amp;task=delete&amp;id=" . $row["id"] . "&amp;returnto=".$this->returnto."\">delete event</a><br>
		";
	}
	
	echo "Starts on ".$sdate;
	if ( !$row['day_event'] ) echo " at ".$stime;
	
	echo "<br>&nbsp;Ends on ".$edate;
	if ( !$row['day_event'] ) echo " at ".$etime."<br>";
	if (  $row['day_event'] ) echo "<br>All Day Event<br>";
	if (  $row['recurring'] ) {
		$dow = $row['recur_dayofweek'];
		if ( $this->week_starts_monday ){
			$dow = $dow - 1;
			if ( $dow < 0 ) $dow = 6;
		}
		echo "Reoccurs weekly on ".$this->daynames[$dow]."<br>";
	}
	echo nl2br(stripslashes($row["description"])) . "\n";
	echo "</body></html>";

} // end function.displayOneEvent

} //end class.ltwCalendar

?>
