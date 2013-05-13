<?php
////////////////////////////////////////////////////////////////////
// Class ltwCalendar
// $Id: ltwdisplaymonth.php 15 2006-03-15 07:25:00Z mootinator $
//
// Displays the calendar in month, day, and event formats
////////////////////////////////////////////////////////////////////
 
class ltwCalendar {
var $db 		= '';
var $auth 		= '';
var $stamp 		= '';
var $day_of_week	= '';
var $month	 	= '';
var $month_name 	= '';
var $day 		= '';
var $year 		= '';
var $next_month 	= '';
var $next_month_year	= '';
var $next_month_name	= '';
var $prev_month 	= '';
var $prev_month_year	= '';
var $prev_month_name	= '';
var $ctable		= '';
var $daynames 		= '';
var $monthnames 	= '';
var $bullets 		= '';
var $hrs_per_day      	= '';

var $catA		= array(); 	// Array for holding Category Names & colors
var $cat_fgcolor	= ''; 		// Default category color (failsafe)
var $cat_bgcolor	= ''; 		// Default category color (failsafe)
var $category_table	= ''; 		// Table name

// these are used by the list view
var $cat_ids		= '';
	
var $header 		= '';
var $footer 		= '';
var $login_req 		= '';
var $week_starts_monday	= 0;
var $php_self		= '';
var $use_popups		= '';

// constructor
function ltwCalendar($timestamp) {
	global $ltw_config;
	global $_REQUEST;
	global $_SERVER;
		
	$this->db 			= new ltwDb;
	$this->auth			= new ltwAuth;
	$this->php_self			= $_SERVER['PHP_SELF'];
	$this->week_starts_monday 	= $ltw_config['week_starts_monday'];
	$this->use_popups		= $ltw_config['use_popups'];
	$this->day_of_week		= date('w',$timestamp);
	$this->stamp		 	= $timestamp;
	$timepieces 			= getdate($timestamp);
	$this->month 			= $timepieces["mon"];
	$this->month_name 		= $timepieces["month"];
	$this->day 			= $timepieces["mday"];
	$this->year 			= $timepieces["year"];
	$this->days_in_month 		= date('t',$timestamp);
	$this->first_day_of_month 	= date('w', mktime( 12, 12, 12, $this->month, 1, $this->year));
	if ( $this->week_starts_monday == 1 ){
		$this->first_day_of_month = $this->first_day_of_month-1;
		if ( $this->first_day_of_month < 0 ) $this->first_day_of_month = 6;
	}
	
	$this->next_month 		= $this->month +1;
	$this->next_month_year 		= $this->year;
	if ( $this->next_month > 12 ){
	  $this->next_month 	 = 1 ;
	  $this->next_month_year 	= $this->year + 1;
	}
	$this->next_month_name 		= $ltw_config['monthnames'][$this->next_month];

	$this->prev_month 		= $this->month -1;
	$this->prev_month_year 		= $this->year;
	if ( $this->prev_month == 0 ){
	  $this->prev_month 	 	= 12 ;
	  $this->prev_month_year 	= $this->year - 1;
	}
	$this->prev_month_name 		= $ltw_config['monthnames'][$this->prev_month];
		
	$this->ctable 			= $ltw_config['db_table_calendar'];
	$this->daynames 		= $ltw_config['daynames'];
	$this->monthnames 		= $ltw_config['monthnames'];
	$this->bullets 			= $ltw_config['bullets'];
	$this->header 			= $ltw_config['html_header_file'];
	$this->footer 			= $ltw_config['html_footer_file'];
	$this->hrs_per_day 		= $ltw_config['hrs_per_day'];
	$this->login_req		= $ltw_config['login_required'];

	if ( isset($_REQUEST['cat_ids']) ) $this->cat_ids = $_REQUEST['cat_ids'];

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

function displayMonth() {
	if ( $this->login_req == 1  && !$this->auth->checkLogin() ){
		echo "<br><br>&nbsp;&nbsp;
		".$this->_popup_link("admin",$this->php_self."?display=admin&amp;task=login","Login Required")."
		</body></html>
		";
		exit;
	}

		
	$num_of_rows = ceil(($this->days_in_month + $this->first_day_of_month) / 7.0);
	$day    	= 1;								// on first day of the month
	$evtA   	= array();							// array of cal entries for the month
	$evtMax 	= 0;								// number of entries read
	$start_date	= $this->year.'-'.$this->month.'-1';				// 1st day if month
	$end_date	= $this->year.'-'.$this->month.'-'.$this->days_in_month;	// last day if month


	// Read all the events into the array evtA in one block
	// then I'll loop thru the array for reach day.  This reduces db 
	// accesses from (upto 31) to one.
	$query  = "SELECT id,name,event_date,event_end,start_time,end_time,recurring,recur_dayofweek,";
	$query .= "       day_event,cat_id ";
	$query .= "FROM ".$this->ctable." ";
	$query .= "WHERE event_end  >= '".$start_date."' ";
	$query .= "  AND event_date <= '".$end_date."' ";
	if ( !empty($this->cat_ids) ) $query .= "  AND cat_id in (".$this->cat_ids.") ";
	$query .= "ORDER BY event_date, day_event DESC, start_time ";
	$result = $this->db->db_query($query);
	while ( $evtA[$evtMax] = $this->db->db_fetch_array($result) ) $evtMax++;


	header("Cache-control: no-cache");
	header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");

	include_once($this->header);

	echo "
	<table height=\"50\" width=\"100%\">
	<tr><td width=\"25%\" align=\"left\">&nbsp;</td>
	
	<td width=\"50%\" align=\"center\">
	<a class=\"prevnext\" rel=\"nofollow\" href=\"".$this->php_self."?month=".$this->prev_month."&amp;year=".$this->prev_month_year."\">".$this->monthnames[(int)$this->prev_month]."</a>
	&nbsp;&nbsp;<font class=\"caption\">".$this->monthnames[(int)$this->month]."&nbsp;".$this->year."</font>
	&nbsp;&nbsp;<a class=\"prevnext\" rel=\"nofollow\" href=\"".$this->php_self."?month=".$this->next_month."&amp;year=".$this->next_month_year."\">".$this->monthnames[(int)$this->next_month]."</a></td>

	<td width=\"25%\" align=\"right\" valign=\"bottom\">
	<form name=\"jump\" method=\"get\" action=\"".$this->php_self."?display=month\">
	<select name=\"month\" size=\"1\">
	";
	for ( $i = 1 ; $i <= 12 ; $i++ ){
	    echo "<option value=\"".$i."\" ";
	    if ( $i == $this->month ) echo "selected";
	    echo ">".$this->monthnames[$i]."</option>\n";
	}
	echo "
	</select>&nbsp;
	<select name=\"year\" size=\"1\">
	";
	$yearnow = date("Y");
	for ( $i = ($yearnow - 1) ; $i <= ($yearnow + 2) ; $i++ ){
	    echo "<option value=\"".$i."\" ";
	    if ( $i == $this->year ) echo "selected";
	    echo ">".$i."</option>\n";
	}

	echo "
	</select>&nbsp;
	<input type=\"submit\" name=\"Go\" value=\"Go!\">
	</form></td></tr></table>";
	
	echo "<table class=\"cal\" width=\"100%\"><thead class=\"thead\" align=\"center\"><tr>";
	
	for ( $i = 0; $i < 7; $i++ ) echo "<td width=\"14%\">" . $this->daynames[$i] . "</td>\n";
	echo "</tr></thead>";

	echo "<tbody>\n";
	for ( $i = 1; $i <= $num_of_rows; $i++ ){ 
		echo "<tr>\n";
		for ( $j = 0; $j < 7; $j++ ){
	  		if ( (($i == 1) && ($this->first_day_of_month <= $j)) || (($i > 1) && ($day <= $this->days_in_month)) ){
				echo "<td class=\"cal\" width=\"14%\" height=\"" . (100 / $num_of_rows) . "%\">";
				$this->_popup_link("day",$this->php_self."?display=day&amp;stamp=".$this->stamp."&amp;day=".$day."&amp;returnto=month",$day);
				echo "<br>";
					
				// date to store in DB is CREATED like this
				$today   = date('Y-m-d',strtotime($this->year . "-" . $this->month . "-" . $day));
				$todayTS = strtotime($today);
				
				echo "<table>\n";
				if($this->bullets == 'TRUE') echo "<ul class=\"cal\">\n";

				// loop thru event array
				for ( $e = 0 ; $e < $evtMax ; $e++ ){
					$event_dateTS = strtotime($evtA[$e]['event_date']);
					$event_endTS  = strtotime($evtA[$e]['event_end']);

					//echo "today=$today, TS=$todayTS, edTS=$event_dateTS, eeTS=$event_endTS<br>";

					// process the event if it needs to be displayed
					// Test 1: is the "working date" within the event start and end?
					if ( ($todayTS >= $event_dateTS) && ($todayTS <= $event_endTS) ){
						//Assume will show event & time
						$showtime = 1;
						$showevent= 1;
					
						// the unix timestamp of the start_time
						$start_timeTS = strtotime($evtA[$e]['start_time']);
						$end_timeTS   = strtotime($evtA[$e]['end_time']);

						if ( !$evtA[$e]['recurring'] ){
							if ( $todayTS == $event_dateTS ){
								$showevent = 1;
								if ( $evtA[$e]['day_event'] ) $showtime = 0;
							}
							if ( ($todayTS == $event_endTS) && ($end_timeTS <$start_timeTS) ){
								$showtime = 0;
							}
						}else{
							if ( date('w', $todayTS) != $evtA[$e]['recur_dayofweek'] ){
								$showtime = 1;
								$showevent = 0;
							}
						}
						if ( $evtA[$e]['day_event'] ) $showtime=0;

						if ( $showevent ){
							$text = '';	
							if ( $this->bullets == 'TRUE' ) echo "<li>";
							if ( $this->hrs_per_day == 12 ) $time_fmt = "%I:%M%p"; else $time_fmt = "%H:%M";
							$start_time = strftime($time_fmt,$start_timeTS);
							if ( $start_time{0} == '0' ) $start_time = substr($start_time, 1);

            						if ( isset($this->catA[$evtA[$e]['cat_id']]) ){
		              					$fgcolor = $this->catA[$evtA[$e]['cat_id']][1];
              							$bgcolor = $this->catA[$evtA[$e]['cat_id']][2];
            						}else{
              							$fgcolor = $this->cat_fgcolor; 
								$bgcolor = $this->cat_bgcolor; 
            						}

							// Figure out the text for the link
							echo "<td align='left' bgcolor=\"".$bgcolor."\">";
							$text = "<font color=\"".$fgcolor."\">";
							if ( $showtime ) $text .= $start_time."&nbsp;-&nbsp;";
							$text .= $evtA[$e]['name']."</font>";

							// Display the link
							$this->_popup_link("event",
							$this->php_self."?display=event&amp;id=".$evtA[$e]['id']."&amp;date=".date('Y-M-d',$todayTS)."&amp;returnto=month",$text);
							echo "</td></tr>";
						}
					}
				}
          			echo "</table>";
				$day++;
					
			}else{
				echo "<!--empty Cell -->
				<td class=\"cal\" width=\"14%\"  height=\"" . (100 / $num_of_rows) . "%\"> 
				";
			}
			echo "</td>\n";
		}
		echo "</tr>\n";
	}
	echo "
	</tbody><tfoot class=\"cal\">
	<tr><td colspan=\"7\">\n
	";
        echo "<a class=\"admin\" href=\"".$this->php_self."?display=pdf&amp;month=". $this->month . "&amp;year=".$this->year ."\"><font class=\"cal-admin-link\">PDF</font></a>&nbsp;&nbsp;";
	
	if ( $this->auth->checkLogin() ){
		echo "&nbsp;&nbsp;<a class=\"admin\" href=\"".$this->php_self."?display=admin&amp;task=logout\"><font class=\"cal-admin-link\">logout</font></a>&nbsp;&nbsp;";
		$this->_popup_link("admin",$this->php_self."?display=admin&amp;task=changepw","<font class=\"cal-admin-link\">change pw</font>");

		if ( $this->auth->user->getPrivledge(UPEDIT+UPADMIN) ){
			echo "&nbsp;&nbsp;";
			$this->_popup_link("admin",$this->php_self."?display=admin&amp;task=add&amp;returnto=month","<font class=\"cal-admin-link\">add event</font>");
		}
		if ( $this->auth->user->getPrivledge(UPLOGS+UPADMIN) ){
			echo "&nbsp;&nbsp;";
			$this->_popup_link("admin",$this->php_self."?display=admin&amp;task=logs","<font class=\"cal-admin-link\">view logs</font>");
		}
		if ( $this->auth->user->getPrivledge(UPADMIN) ){
			echo "&nbsp;&nbsp;";
			$this->_popup_link("admin",$this->php_self."?display=admin&amp;task=categories","<font class=\"cal-admin-link\">categories</font>");
			echo "&nbsp;&nbsp;";
			$this->_popup_link("admin",$this->php_self."?display=admin&amp;task=users","<font class=\"cal-admin-link\">users</font>");
			echo "&nbsp;&nbsp;";
			$this->_popup_link("admin",$this->php_self."?display=admin&amp;task=dbmaint","<font class=\"cal-admin-link\">db</font>");
		}
	}else{
		echo "&nbsp;&nbsp;";
		$this->_popup_link("admin",$this->php_self."?display=admin&amp;task=login","<font class=\"cal-admin-link\">login</font>");
	}
	echo "</td></tr></tfoot></table>\n";	
	include_once($this->footer);
} // end function.display month

function _popup_link ($class='',$url='',$text=''){
	echo " <a class=\"".$class."\" ";
	if ($this->use_popups == 1 ) echo " onclick=\"launchevent('".$url."'); return false;\" ";
	echo " href=\"".$url."\">".$text."</a>";
}
			
} //end class.ltwCalendar

?>
