<?php
////////////////////////////////////////////////////////////////////////////
// ltw_eventmgr.php
// $Id: ltweventmgr.php 28 2006-04-01 23:38:13Z mootinator $
//
// ltwCalendar Event Manager (add, edit, delete)
////////////////////////////////////////////////////////////////////////////
 
class ltwEventMgr {
var $auth   		= '';
var $event  		= '';
var $email		= '';
var $email_enabled	= '';
var $catA		= array();
var $ctable		= '';
var $monthnames 	= '';
var $daynames   	= '';
var $hrs_per_day 	= '';
var $loglevel 	  	= '';
var $utable     	= '';
var $default_start_time = '';
var $default_end_time 	= '';
var $php_self		= '';
var $week_starts_monday = '';

var $returnto		= 'month';
	
// constructor
function ltwEventMgr(){
	global $ltw_config ;
	global $_POST;
	global $_REQUEST;
	global $_SERVER;

	// required objects
	$this->auth  = new ltwAuth;
	$this->event = new ltwEvent;

	$this->php_self		= $_SERVER['PHP_SELF'];
	$this->ctable 		= $ltw_config['db_table_category'];
	$this->utable 		= $ltw_config['db_table_users'];
	$this->monthnames 	= $ltw_config['monthnames'];
	$this->daynames 	= $ltw_config['daynames'];
	$this->loglevel		= $ltw_config['eloglevel'];
	$this->hrs_per_day	= $ltw_config['hrs_per_day'];
	$this->week_starts_monday=$ltw_config['week_starts_monday'];

	if ( isset($_REQUEST['returnto']) ) $this->returnto = $_REQUEST['returnto'];
	if ( isset($_POST['returnto'])    ) $this->returnto = $_POST['returnto'];
	
	$this->default_start_time = $ltw_config['start_time'];
	$this->default_end_time   = $ltw_config['end_time'];

	// see if email is enabled, then create the object
	// only if it is (saves a db access)
	$this->email_enabled 	= $ltw_config['email_enabled'];
	if ( !empty($this->email_enabled) ) $this->email = new ltwMail;

	// read the category table into an array
	$query = "SELECT * FROM ".$this->ctable." ORDER BY name";
	$result = $this->event->db->db_query($query);
	while ( $row = $this->event->db->db_fetch_array($result) ){
		$this->catA[$row['id']] = array(stripslashes($row['name']) );
	}
} // end constructor

function add($reqDate = ''){
	global $_POST;
	
	$errors = '';
	$logd	= '';
	$logm	= '';
	$mode = "Add";

	if ( !$this->auth->checkLogin() ){
		$this->auth->notLoggedIn();
		return 0;
	}

	if ( !$this->auth->user->getPrivledge(UPEDIT+UPADMIN) ){
	  $this->auth->notPrivledged();
		return 0;
	}

	
	//echo "_POST: ".print_r($_POST);echo "<br>";
	//////////////////////////////////////////////////////////
	// If no form button had been pressed, set up the display
	//////////////////////////////////////////////////////////			
	if ( empty($_POST['AddEvent']) ){

		//////////////////////////////////////////////////////////
		// if no reqDate, set it to today
		// otherwise, set to requested date
		//////////////////////////////////////////////////////////
		if ( empty($reqDate) ){
			$this->event->event_date = date("Y-n-d");
			$this->event->event_end  = $this->event->event_date;
		}else{
			$this->event->event_date = $reqDate;
			$this->event->event_end  = $this->event->event_date;
		}
		//////////////////////////////////////////////////////////
		// if default start & end times were configured
		// set them up
		//////////////////////////////////////////////////////////
		if ( $this->default_start_time != 0 ){
			$tmpA = explode(":",$this->default_start_time);
			if ( $tmpA[2] == 'PM' ) $tmpA[0] = $tmpA[0] + 12;
			$this->event->start_time = $tmpA[0].":".$tmpA[1].":00";
		}
		if ( $this->default_end_time != 0 ){
			$tmpA = explode(":",$this->default_end_time);
			if ( $tmpA[2] == 'PM' ) $tmpA[0] = $tmpA[0] + 12;
			$this->event->end_time = $tmpA[0].":".$tmpA[1].":00";
		}

		$this->Event2_POST();
		$this->display($mode);
			
	}else{
		//////////////////////////////////////////////////////////
		// Process the form input
		//////////////////////////////////////////////////////////
		// NAME
		
		$logm   = "<B>Event: Added</b><br>";
		if ( empty($_POST['name']) ){
			$errors .= "Missing Event Name<br>";
		}else{
			$this->event->name = $_POST['name'];
			$logd .= 'Name: '.$this->event->name.'<br>';
		}
			

		// LOCATION
		$this->event->location = $_POST['location'];
		$logd .= 'Location: '.$this->event->location.'<br>';

		// START DATE & TIME
		$sdate = sprintf('%04.4d-%02.2d-%02.2d',$_POST['sYear'],$_POST['sMonth'],$_POST['sDay']);
		if ( $sdate === '--' ){
			$errors .= "Missing Start Date<br>";
		}else{
			$this->event->event_date = $sdate;
			$logd .= 'Event_date: '.$this->event->event_date.'<br>';
		}
		if ( ($this->hrs_per_day == 12) & ($_POST['sAMPM'] == 'PM') ){
			if ( $_POST['sHour'] < 12 )$_POST['sHour'] = $_POST['sHour'] + 12;
		}
		else
		{
			if ( $_POST['sHour'] == 12)$_POST['sHour'] = $_POST['sHour'] = 0;
		}

		$stime = sprintf('%02.2d:%02.2d:00',$_POST['sHour'],$_POST['sMin']);
		$this->event->start_time = $stime;
		$logd .= 'Start_time: '.$this->event->start_time.'<br>';
		$startTS = strtotime($sdate." ".$stime);

		// END DATE & TIME
		$edate = sprintf('%04.4d-%02.2d-%02.2d',$_POST['eYear'],$_POST['eMonth'],$_POST['eDay']);
		if ( $edate === '--' ) $edate = $sdate;
		$this->event->event_end = $edate;
		$logd .= 'Event_end: '.$this->event->event_end.'<br>';
		if ( ($this->hrs_per_day == 12) & ($_POST['eAMPM'] == 'PM') ){
			if ( $_POST['eHour'] < 12 )$_POST['eHour'] = $_POST['eHour'] + 12;
		}
		$etime = sprintf('%02.2d:%02.2d:00',$_POST['eHour'],$_POST['eMin']);
		$this->event->end_time = $etime;
		$logd .= 'End_time: '.$this->event->end_time.'<br>';
		$endTS = strtotime($edate." ".$etime);

		// Date Check
		if ( $endTS < $startTS ){
			$errors = "End Date/Time can not be before Start Date/Time<br>";
		}

		// dayEvent?
		if ( empty($_POST['day_event']) ) $day_event = 0 ; else $day_event = 1;
		$this->event->day_event = $day_event;
		$logd .= 'Day_event: '.$this->event->day_event.'<br>';
	
		// recurring?
		if ( empty($_POST['recurring']) ) $recurring = 0 ; else $recurring = 1;
		if ( $recurring == 1 ){
			$recur_dayofweek = date('w',strtotime($this->event->event_date));
			$dow = $recur_dayofweek;
			if ( $this->week_starts_monday ){
				$dow = $dow - 1;
				if ( $dow < 0 ) $dow = 6;
			}
			$logd .= 'Recurring: '.$recurring.' on '.$this->daynames[$dow].'<br>';
			$this->event->recur_dayofweek = $recur_dayofweek;
		}else{
			$logd .= 'Recurring: 0<br>';
			$this->event->recur_dayofweek = "NULL";
		}
		$this->event->recurring = $recurring;

		if ( !empty($_POST['cat_id']) ){
			$this->event->cat_id = $_POST['cat_id'];
			$logd .= 'Category: '.$this->catA[$this->event->cat_id][0].'<br>';
		}else{
			$this->event->cat_id = 0;
		}

		$this->event->description = $_POST['description'];
		$logd .= 'Description:<br>'.$this->event->description;

		if ( empty($errors) ){
			$this->event->create();
			$this->notifier($logm,$logd);
 			$this->rYear = sprintf('%04.4d',$_POST['sYear']);
 			$this->rMonth = sprintf('%04.4d',$_POST['sMonth']);
 			jsClosePopupReloadMain($this->php_self."?display=".$this->returnto."&month=".$this->rMonth."&year=".$this->rYear);
		}else{
			$this->display($mode,$errors);
		}
		return;
	}
} // end function.add

function delete($id) {
	$logm = '';
	$logd = '';

	if ( !$this->auth->checkLogin() ){
		$this->auth->notLoggedIn();
		return 0;
	}

	if ( !$this->auth->user->getPrivledge(UPEDIT+UPADMIN) ){
		$this->auth->notPrivledged();
		return 0;
	}

	$mode = "Delete";
	if ( ! $this->event->findById($id) ){
		echo "<html><head></head><body>Event not found (".$id.")</body></html>";
		return 0;
    	}

	$logm = "<b>Event:  Deleted</b>(".$id.")<br>";
	$logd  = "Name: ".$this->event->name."<br>";
	$logd .= "Location: ".$this->event->location."<br>";
	$logd .= "Event_date: ".$this->event->event_date."<br>";
	$logd .= "Event_end: ".$this->event->event_end."<br>";
	$logd .= "Start_time: ".$this->event->start_time."<br>";
	$logd .= "End_time: ".$this->event->end_time."<br>";
	$logd .= "Day_event: ".$this->event->day_event."<br>";
	$logd .= "Recurring: ".$this->event->recurring;
	if ($this->event->recurring) $logd.= " on ".$this->event->recur_dayofweek;
	$logd .= "<br>";
	$logd .= "Category: ".$this->catA[$this->event->cat_id][0]."<br>";
	$logd .= "Description: ".$this->event->description;
	
	$this->event->deleteById($id);
	$this->notifier($logm,$logd);
	$this->rYear = date("Y",strtotime($this->event->event_date));
 	$this->rMonth = date("m",strtotime($this->event->event_date));
 	jsClosePopupReloadMain($this->php_self."?display=".$this->returnto."&month=".$this->rMonth."&year=".$this->rYear);

} //end function.delete
	

function display($mode = '', $errors = ''){
	global $_POST;

	header("Cache-control: no-cache");
	header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");

	echo "
	<html>
	<head><title>".$mode." Event</title>
	</head>
	<body>
	<form action=\"".$this->php_self."?display=admin&task=".strtolower($mode)."\" method=\"POST\" name=\"addedit\">
	<table border=\"1\">
	<tr><td colspan=\"2\"><b>".$mode." Event</b></td></tr>
	 ";

	// Event Id (if Edit, for reference only)
	echo "<tr><td>Event Id</td><td>";
	if ( !empty($_POST['id']) ){
		echo $_POST['id']."<input type=\"hidden\" name=\"id\" value=\"".$_POST['id']."\">"; 
	}else{
		echo "&nbsp;";
	}
	echo "</td></tr>\n";

	// Event Name
	echo "<tr><td>Name</td><td><input type=\"text\" name=\"name\" ";
	if ( !empty($_POST['name']) ) echo "value=\"".$_POST['name']."\"";
	echo "></td></tr>\n";

	// Event Location
	echo "<tr><td>Location</td><td><input type=\"text\" name=\"location\" ";
	if ( !empty($_POST['location']) ) echo "value=\"".$_POST['location']."\"";
	echo "></td></tr>\n";

	// Start Date
	echo "<tr><td align=\"right\">Start Date</td><td><select name=\"sMonth\">";
	for ( $i = 1; $i < 13; $i++ ){
		echo "<option value=\"".$i."\" ";
		if ( !empty($_POST['sMonth']) && ($i == $_POST['sMonth']) ) echo "SELECTED";
		echo ">" . $this->monthnames[$i] . "</OPTION>\n";
	}
	echo "</select>\n&nbsp;<select name=\"sDay\">";
	for($i = 1; $i <= 31; $i++) {
		echo "<option value=\"".$i."\" ";
		if ( !empty($_POST['sDay']) && ($i == $_POST['sDay']) ) echo "SELECTED";
		echo ">" . $i . "</OPTION>\n";
	}
	echo "</select>&nbsp;\n<select name=\"sYear\">\n";
	$thisYear = date("Y");
	for ( $i = $thisYear; $i <= ($thisYear+10); $i++ ){
		echo "<option value=\"".$i."\" ";
		if ( !empty($_POST['sYear']) && ($i == $_POST['sYear']) ) echo "SELECTED";
		echo ">" . $i . "</OPTION>\n";
	}
	echo "</select></td></tr>\n";
	// Start Time
	echo "<tr><td align=\"right\">Start Time</td><td><select name=\"sHour\">\n";
	if ( $this->hrs_per_day == 12 ){ $j = 1 ; $k = 12; }else{ $j = 0 ; $k = 23; }
	for ( $i = $j; $i <= $k; $i++ ){
		echo "<option value=\"" . $i . "\" ";
		if ( !empty($_POST['sHour']) && ($i == $_POST['sHour']) ) echo "SELECTED";
		echo ">" . $i . "</OPTION>\n";
	}
	echo "</select>&nbsp;&nbsp;\n<select name=\"sMin\">\n";
	for($i = 0; $i <= 45; $i = $i + 15) {
		echo "<option value=\"" . $i . "\" ";
		if( !empty($_POST['sMin']) && ($i == $_POST['sMin']) ) echo "SELECTED";
		echo ">" . $i . "</OPTION>\n";
	}
	echo "</select>&nbsp;\n";

	if ( $this->hrs_per_day == 12 ){
		echo "<select name=\"sAMPM\">\n";
		echo "<option value=\"AM\" ";
		if( !empty($_POST['sAMPM']) && ($_POST['sAMPM'] == 'AM') ) echo "SELECTED";
		echo ">AM</OPTION>\n";
		echo "<option value=\"PM\" ";
		if( !empty($_POST['sAMPM']) && ($_POST['sAMPM'] == 'PM') ) echo "SELECTED";
		echo ">PM</OPTION>\n";
		echo "</select><BR>\n";
	}
	echo "</td></tr>";

	// End Date
	echo "<tr><td align=\"right\">End Date</td><td><select name=\"eMonth\">\n";
	for ( $i = 1; $i < 13; $i++ ){
		echo "<option value=\"".$i."\" ";
		if ( !empty($_POST['eMonth']) && ($i == $_POST['eMonth']) ) echo "SELECTED";
		echo ">" . $this->monthnames[$i] . "</OPTION>\n";
	}
	echo "</select>&nbsp;\n<select name=\"eDay\">\n";
	for($i = 1; $i <= 31; $i++) {
		echo "<option value=\"".$i."\" ";
		if ( !empty($_POST['eDay']) && ($i == $_POST['eDay']) ) echo "SELECTED";
		echo ">" . $i . "</OPTION>\n";
	}
	echo "</select>&nbsp;<select name=\"eYear\">\n";
	$thisYear = date("Y");
	for ( $i = $thisYear; $i <= ($thisYear+10); $i++ ){
		echo "<option value=\"".$i."\" ";
		if ( !empty($_POST['eYear']) && ($i == $_POST['eYear']) ) echo "SELECTED";
		echo ">" . $i . "</OPTION>\n";
	}
	echo "</select>\n";
	// End Time
	echo "<tr><td align=\"right\">End Time</td><td><select name=\"eHour\">\n";
	if ( $this->hrs_per_day == 12 ){ $j = 1 ; $k = 12; }else{ $j = 0 ; $k = 23; }
	for ( $i = $j; $i <= $k; $i++ ){
		echo "<option value=\"" . $i . "\" ";
		if ( !empty($_POST['eHour']) && ($i == $_POST['eHour']) ) echo "SELECTED";
		echo ">" . $i . "</OPTION>\n";
	}
	echo "</select>&nbsp;&nbsp;\n";
	echo "<select name=\"eMin\">\n";
	for($i = 0; $i <= 45; $i = $i + 15) {
		echo "<option value=\"" . $i . "\" ";
		if( !empty($_POST['eMin']) && ($i == $_POST['eMin']) ) echo "SELECTED";
		echo ">" . $i . "</OPTION>\n";
	}
	echo "</select>&nbsp;\n";

	if ( $this->hrs_per_day == 12 ){
		echo "<select name=\"eAMPM\">\n";
		echo "<option value=\"AM\" ";
		if( !empty($_POST['eAMPM']) && ($_POST['eAMPM'] == 'AM') ) echo "SELECTED";
		echo ">AM</OPTION>\n";
		echo "<option value=\"PM\" ";
		if( !empty($_POST['eAMPM']) && ($_POST['eAMPM'] == 'PM') ) echo "SELECTED";
		echo ">PM</OPTION>\n";
		echo "</select><BR>\n";
	}
	echo "</td></tr>";

	// All Day Event
	echo "<tr><td>All Day</td><td><input type=\"checkbox\" name=\"day_event\" ";
	if ( !empty($_POST['day_event']) ) echo "checked";
	echo ">Event lasts all day </td></tr>";

	// Reoccuring Event
	echo "<tr><td>Reoccurs</td><td><input type=\"checkbox\" name=\"recurring\" ";
	if ( !empty($_POST['recurring']) ){
		$dow = $_POST['recur_dayofweek'];
		if ( $this->week_starts_monday ){
			$dow = $dow - 1;
			if ( $dow < 0 ) $dow = 6;
		}
		echo "checked>Weekly on ".$this->daynames[$dow];
	}else{
		echo ">Weekly on this day";
	}
	echo "</td></tr>";

	echo "<tr><td>Category</td><td>";
	echo "<select name=\"cat_id\">\n";
	foreach ( $this->catA as $key => $value ){
		echo "<option value=\"".$key."\" ";
		if ( !empty($_POST['cat_id']) && ($key == $_POST['cat_id']) ) echo "selected";
		echo ">".$value[0]."</option>\n";
    	}
	echo "</select></td></tr>\n";

	// Description
	echo "<tr><td>Description</td><td>\n";
	echo "<textarea name=\"description\" rows=\"5\" cols=\"30\">"; 
	if ( !empty($_POST['description']) ) echo stripslashes($_POST['description']); 
	echo "</textarea></td></tr>\n";

	echo "<tr><td colspan=\"2\" align=\"center\">";
	switch ($mode){
	case	'Add' : 
		echo "<input type=\"submit\" name=\"AddEvent\" value=\"Add\">";
		break;
	case  'Edit': 
		echo "<input type=\"submit\" name=\"EditEvent\" value=\"Update\">";
		break;
	}

	echo "
	</td></tr>
	</table>
	<input type=\"hidden\" name=\"returnto\" value=\"".$this->returnto."\"></form>
	".$errors."
	<body><html>
	 ";

} // end function.display

	
function edit($id){
	global $_POST;

	$errors = '';
	$logm	= '';
	$logd	= '';
	$mode = "Edit";

	if ( !$this->auth->checkLogin() ){
		$this->auth->notLoggedIn();
		return 0;
	}

	if ( !$this->auth->user->getPrivledge(UPEDIT+UPADMIN) ){
		$this->auth->notPrivledged();
		return 0;
	}

	if ( !$this->event->findById($id) ){
		echo "<html><head></head><body>Event not found (".$id.")</body></html>";
		return 0;
	}

	//////////////////////////////////////////////////////////
	// No form button had been pressed, set up the display
	// Array sA is used since the form fields do not map 
	//  1 to 1 with the db fields
	//////////////////////////////////////////////////////////			
	if ( !isset($_POST['EditEvent']) ){
		$this->Event2_POST();
		$this->display('Edit');

	}else{
		//////////////////////////////////////////////////////////
		// Updating an existing event
		//////////////////////////////////////////////////////////
		$logm  = "<b>Event: Updated</b> ".$this->event->name."(".$_POST[id].")<br>";

		// NAME
		if ( empty($_POST['name']) ){
			$errors .= "Missing Event Name<br>";
		}else{
			if ( addslashes($this->event->name) != $_POST['name'] ){ 
				$logd .= 'Name: '.$this->event->name.'->'.$_POST['name'].'<br>';
				$this->event->name = $_POST['name'];
			}
		}

		// LOCATION
		if ( addslashes($this->event->location) != $_POST['location'] ){
			$logd .= 'Location: '.$this->event->location.'->'.$_POST[location].'<br>';
			$this->event->location = $_POST['location'];
		}

		// START DATE/Time
		$sdate = sprintf ('%04.4d-%02.2d-%02.2d',$_POST['sYear'],$_POST['sMonth'],$_POST['sDay']);
		//echo "sdate=$sdate<br>";
		if ( $sdate === '--' ){
			$errors .= "Missing Start Date<br>";
		}else{
			if ( $this->event->event_date != $sdate ){
				$logd .= 'Event_date: '.$this->event->event_date.'->'.$sdate.'<br>';
				$this->event->event_date = $sdate;
			}
		}
		if ( ($this->hrs_per_day == 12) & ($_POST['sAMPM'] == 'PM') ){
			if ( $_POST['sHour']  < 12 ) $_POST['sHour'] = $_POST['sHour'] + 12;
		}
		$stime = sprintf ('%02.2d:%02.2d:00',$_POST['sHour'],$_POST['sMin']);
		if ( $this->event->start_time != $stime ){
			$logd .= 'Start_time: '.$this->event->start_time.'->'.$stime.'<br>';
			$this->event->start_time = $stime;
		}
		$startTS = strtotime($sdate." ".$stime);

		// END DATE/TIME
		$edate = sprintf ('%04.4d-%02.2d-%02.2d',$_POST['eYear'],$_POST['eMonth'],$_POST['eDay']);
		//echo "edate=$edate<br>";
		if ( $edate == '--' ) $edate = $sdate;
		if ( strtotime($edate) < 	strtotime($sdate) ){
			$errors .= "End Date < Start Date.<br>";
		}else{
			if ( $this->event->event_end != $edate ){
				$logd .= 'Event_end: '.$this->event->event_end.'->'.$edate.'<br>';
				$this->event->event_end = $edate;
			}
		}
		//end time
		if ( ($this->hrs_per_day == 12) & ($_POST['eAMPM'] == 'PM') ){
			if ( $_POST['eHour']  < 12 ) $_POST['eHour'] = $_POST['eHour'] + 12;
		}
		$etime = sprintf ('%02.2d:%02.2d:00',$_POST['eHour'],$_POST['eMin']);
		if ( $this->event->end_time != $etime ){
			$logd .= 'End_time: '.$this->event->end_time.'->'.$etime.'<br>';
			$this->event->end_time = $etime;
		}
		$endTS = strtotime($edate." ".$etime);

		// Date Check
		if ( $endTS < $startTS ){
			$errors .= "End Date/Time can not be before Start Date/Time<br>";
		}

		// dayEvent?
		if ( empty($_POST['day_event']) ) $day_event = 0 ; else $day_event = 1;
		if ( $this->event->day_event != $day_event ){
			$logd .= 'Day_event: '.$this->event->day_event.'->'.$day_event.'<br>';
			$this->event->day_event = $day_event;
		}
	
		// recurring?
		if ( empty($_POST['recurring']) ) $recurring = 0 ; else $recurring = 1;
		if ( $this->event->recurring != $recurring ){
			$logd .= 'Recurring: '.$this->event->recurring.'->'.$recurring;
			$this->event->recurring = $recurring;
			if ( $recurring == 1 ){
				$recur_dayofweek = date('w',strtotime($this->event->event_date));				
				$this->event->recur_dayofweek = $recur_dayofweek;
				$dow = $recur_dayofweek;
				if ( $this->week_starts_monday ){
					$dow = $dow - 1;
					if ( $dow < 0 ) $dow = 6;
				}
				$logd .= ' on '.$this->daynames[$dow].'<br>';
			}
		}

		if ( empty($_POST['cat_id']) ) $_POST['cat_id'] = 0;
		if ( $this->event->cat_id != $_POST['cat_id'] ){
			$logd .= 'Category: '.$this->catA[$this->event->cat_id][0].'->'.$this->catA[$_POST['cat_id']][0].'<br>';
			$this->event->cat_id = $_POST['cat_id'];
		}

		if ( addslashes($this->event->description) != $_POST['description'] ){
			$logd .= 'Description: '.$this->event->description.'->'.$_POST['description'].'<br>';
			$this->event->description = $_POST['description'];
		}

		if ( empty($errors) ){
			$this->event->name        = $this->event->name;
			$this->event->location    = $this->event->location;
			$this->event->description = $this->event->description;
			$this->event->update();
			$this->notifier($logm,$logd);
			$this->rYear = sprintf('%04.4d',$_POST['sYear']);
			$this->rMonth = sprintf('%04.4d',$_POST['sMonth']);
			jsClosePopupReloadMain($this->php_self."?display=".$this->returnto."&month=".$this->rMonth."&year=".$this->rYear);
		}else{
			$this->Event2_Post();
			$this->display($mode,$errors);
		}
	}
	return;
} // end function.edit

	
function Event2_POST(){
	global $_POST;
	
	$_POST['id'] 	  = $this->event->id;
	$_POST['name']	  = htmlentities($this->event->name);
	$_POST['location']= htmlentities($this->event->location);
		
	$tmpA = explode("-",$this->event->event_date);
	$_POST['sYear']  = $tmpA[0]; 
	$_POST['sMonth'] = $tmpA[1]; 
	$_POST['sDay']   = $tmpA[2]; 
 	
	$tmpA = explode("-",$this->event->event_end);
	$_POST['eYear']  = $tmpA[0];
	$_POST['eMonth'] = $tmpA[1];
	$_POST['eDay']   = $tmpA[2];

	if ( !empty($this->event->start_time) ){
		$tmpA = split(":",$this->event->start_time);
		if ( $this->hrs_per_day == 12 ){
			if ( $tmpA[0] < 12 ){	
				$_POST['sAMPM'] = 'AM';
			}else{
 				$_POST['sAMPM'] = 'PM';
				$tmpA[0] = $tmpA[0] - 12;
			}

		}
		$_POST['sHour'] = $tmpA[0];
		$_POST['sMin']  = $tmpA[1];	}
				  
	if ( !empty($this->event->end_time )){
		$tmpA = split(":",$this->event->end_time); 
		if ( $this->hrs_per_day == 12 ){
			if ( $tmpA[0] < 12 ){
				$_POST['eAMPM'] = 'AM';
			}else{
 				$_POST['eAMPM'] = 'PM';
				$tmpA[0] = $tmpA[0] - 12;
			}
		}
		$_POST['eHour'] = $tmpA[0];
		$_POST['eMin']  = $tmpA[1];
	}
	if ( $this->event->day_event == 1 ) $_POST['day_event'] = 1;
	if ( $this->event->recurring == 1 ) $_POST['recurring'] = 1;
	$_POST['recur_dayofweek'] = $this->event->recur_dayofweek;
	$_POST['description']= htmlentities($this->event->description);
	$_POST['cat_id'] = $this->event->cat_id;

} // end function.Event2_POST

// Notifier:
// sends the appropriate notifications to the log or emailer
//
function notifier($logm='', $logd=''){
	
	if ( $this->loglevel != 0 ) {
		if ( $this->loglevel & ELDETAIL ){
			$this->event->log($this->auth->user->username,$logm.$logd);
		}else{
			$this->event->log($this->auth->user->username,$logm);
		}
	}

	if ( $this->email_enabled != 0 ){
		$this->email->subject = "ltwCalendar Event Notification";
		$body  = $logm.$logd;
		$body .= "<br><br><b>Changed by<br>---------------</b><br>";
		$body .= "User : ".$this->auth->user->username."<br>";
		$body .= "Email: ".$this->auth->user->email;
		$this->email->msg = stripslashes($body);
		$this->email->send();
	}
} // end function.notifier


} // end class.ltwEventManager

?>
