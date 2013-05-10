<?php
////////////////////////////////////////////////////////////////////
// Class ltwCalendar
// $Id: ltwdisplaylist.php 5 2006-03-12 08:15:51Z mootinator $
//
// Displays the calendar in month, day, and event formats
////////////////////////////////////////////////////////////////////

class ltwCalendarList {
var $db 		= '';
var $auth 		= '';
var $day_of_week	= '';
var $month	 	= '';
var $month_name 	= '';
var $day 		= '';
var $year 		= '';
var $ctable		= '';
var $daynames 		= '';
var $monthnames 	= '';
var $hrs_per_day      	= '';
var $list_query_size	= '';

var $catA		= array(); 	// Array for holding Category Names & colors
var $cat_fgcolor	= ''; 		// Default category color (failsafe)
var $cat_bgcolor	= ''; 		// Default category color (failsafe)
var $category_table	= ''; 		// Table name

// these are used by the list view
var $cat_ids		= '';
var $start_date		= '';
var $end_date		= '';
	
var $header 		= '';
var $footer 		= '';
var $login_req 		= '';
var $php_self		= '';
var $use_popups		= '';

// constructor
function ltwCalendarList() {
	global $ltw_config;
	global $_SERVER;
	global $_REQUEST;
		
	$this->db 		= new ltwDb;
	$this->auth		= new ltwAuth;
	$this->php_self		= $_SERVER['PHP_SELF'];
	$this->use_popups	= $ltw_config['use_popups'];
		
	$this->ctable 		= $ltw_config['db_table_calendar'];
	$this->daynames 	= $ltw_config['daynames'];
	$this->monthnames 	= $ltw_config['monthnames'];
	$this->header 		= $ltw_config['html_header_file'];
	$this->footer 		= $ltw_config['html_footer_file'];
	$this->hrs_per_day 	= $ltw_config['hrs_per_day'];
	$this->login_req	= $ltw_config['login_required'];
	
	if ( isset($_REQUEST['cat_ids']) ) $this->cat_ids = $_REQUEST['cat_ids'];
	$this->start_date = $_REQUEST['start_date'];
	$this->end_date   = $_REQUEST['end_date'];

	$this->cat_fgcolor	= $ltw_config['cat_fgcolor'];
	$this->cat_bgcolor	= $ltw_config['cat_bgcolor'];
	$this->cat_table  	= $ltw_config['db_table_category'];
	$this->list_query_size	= $ltw_config['list_query_size'];

	// read the category table into an array
	$query = "SELECT * from ". $this->cat_table;
	$result = $this->db->db_query($query);
	while($row = $this->db->db_fetch_array($result) ){
		$this->catA[$row['id']] = array(stripslashes($row['name']),stripslashes($row['fgcolor']),stripslashes($row['bgcolor']));
	}

} //end constructor


function displayList(){
	$mPrevious 	= '';			// flag holding "previous" month
	$dPrevious 	= '';			// flag holding "previous" day
	$evtA		= array();		// array of events from the database

	$dWidth		= 80;		// width of the Date Cell
	$tWidth		= 60;		// width of the Time Cell
	$nWidth		= 220;		// width of the Name Cell

	header("Cache-control: no-cache");
	header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");
	include_once($this->header);

	if ( $this->login_req == 1  && !$this->auth->checkLogin() ){
		echo "<br><br>&nbsp;&nbsp;
		".$this->_popup_link('admin',$this->php_self.'?display=admin&amp;task=login','Login Required')."
		</body></html>
		";
	}


	// Read list_query_size records until done
	$query_start	= $this->start_date;
	$query_end	= date('Y-m-d',strtotime($query_start." + ".($this->list_query_size-1)." days"));
	if ( strtotime($query_end) > strtotime($this->end_date) ) $query_end = $this->end_date;
	while ( strtotime($query_start) < strtotime($this->end_date) ){
		
		$query  = "SELECT * ";
		$query .= "FROM ".$this->ctable." ";
		$query .= "WHERE event_end  >= '".$query_start."' ";
		$query .= "  AND event_date <= '".$query_end."' ";
		if ( !empty($this->cat_ids) ) $query .= "  AND cat_id in (".$this->cat_ids.") ";
		$query .= "ORDER BY event_date, day_event DESC, start_time ";
		$result = $this->db->db_query($query);
		
		$evtMax		= 0;
		while ( $evtA[$evtMax] = $this->db->db_fetch_array($result) ) $evtMax++;

		$evtShown    = 0;	
		$today       = $query_start;
		$todayTS     = strtotime($today);
		$query_endTS = strtotime($query_end);
		while ( $todayTS <= $query_endTS ){
			
			$tmpA = explode('-',$today);
			// if the month has changed
			if ( $tmpA[1] != $mPrevious ){
				if ( $mPrevious ) echo "</table>";
				$mPrevious = $tmpA[1];
				echo "
				<table width=100%>
				<tr><td><font class='caption'>".$this->monthnames[intval($tmpA[1])].", ".$tmpA[0]."</font></td>
				<td  align=\"right\" valign=\"bottom\">
				<form name=\"jump\" method=\"get\" action=\"".$this->php_self."\">
				
				<select name=\"month\" size=\"1\">
				";
				for ( $i = 1 ; $i <= 12 ; $i++ ){
	    				echo "<option value=\"".$i."\" ";
	    				if ( $i == $tmpA[1] ) echo "selected";
	    				echo ">".$this->monthnames[$i]."</option>\n";
				}
				echo "
				</select>&nbsp;
				<select name=\"year\" size=\"1\">
				";
				$yearnow = date("Y");
				for ( $i = ($yearnow - 1) ; $i <= ($yearnow + 2) ; $i++ ){
					echo "<option value=\"".$i."\" ";
					if ( $i == $tmpA[0] ) echo "selected";
					echo ">".$i."</option>\n";
				}

				echo "
				</select>&nbsp;
				<input type=\"submit\" name=\"Go\" value=\"Go!\">
				<input type=\"hidden\" name=\"display\" value=\"list\">
				</form></td></tr></table>
				<table border=\"1\" width=100%><tbody>
				<tr><td width=".$dWidth."><b>Date</b></td>
				    <td width=".$tWidth."><b>Time</b></td>
				    <td width=".$nWidth."><b>Name</b></td>
				    <td><b>Description</b></td></tr>
				";
			}


			// iterate thru $evtA for this day.
			for ( $e = 0 ; $e < $evtMax ; $e++ ){
				$event_dateTS = strtotime($evtA[$e]['event_date']);
				$event_endTS  = strtotime($evtA[$e]['event_end']);

				// process the event if it needs to be displayed
				// Test 1: is the "working date" within the event start and end?
				if ( ($todayTS >= $event_dateTS) && ($todayTS <= $event_endTS) ){
					//Assume will show event & time
					$showtime = 1;
					$showevent= 1;

					$start_timeTS  = strtotime($evtA[$e]['start_time']);
					$end_timeTS    = strtotime($evtA[$e]['end_time']);


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

					if ( $showevent ){
						$evtShown++;
						echo "
						<tr><td align='left' width=".$dWidth.">
						".date('D, n/j',$todayTS)."
						</td>";
						
						if ( $showtime ){
							// get the formatted start_time
							if ( $this->hrs_per_day == 12 ) $time_fmt = "%I:%M%p"; else $time_fmt = "%H:%M";
							$start_time = strftime($time_fmt,$start_timeTS);
 							echo "<td align='left' width=".$tWidth."><b>".$start_time."</b></td>"; 
						}else{
							echo "<td align='left' width=".$tWidth.">&nbsp;</td>";
						}
	
						if ( isset($this->catA[$evtA[$e]['cat_id']]) ){
             						$fgcolor = $this->catA[$evtA[$e]['cat_id']][1];
             						$bgcolor = $this->catA[$evtA[$e]['cat_id']][2];
            					}else{
             						$fgcolor = $this->cat_fgcolor; 
							$bgcolor = $this->cat_bgcolor; 
           					}
						echo "<td width=".$nWidth."align='left' bgcolor=\"".$bgcolor."\">";
						$text="<font color=\"".$fgcolor."\">".$evtA[$e]['name']."</font>";
						$this->_popup_link("event",
						$this->php_self."?display=event&amp;id=".$evtA[$e]['id']."&amp;date=".date('Y-M-d',$todayTS)."&amp;returnto=list",$text);
						echo "</td><td>";
						if ( !empty($evtA[$e]['location'])    ) echo "at ".stripslashes($evtA[$e]['location'])."<br>";
						if ( !empty($evtA[$e]['description']) ) echo stripslashes($evtA[$e]['description']);
						echo "&nbsp;</td></tr>";
					}
				}
			} // end loop thru $evtA[]
			
			//move to next date
			$tmpA[2]++;
			if ( $tmpA[2] > date('t',strtotime($today)) ){
				$tmpA[2] = 1;
				$tmpA[1] ++;
			}
			if ( $tmpA[1] > 12 ){
				$tmpA[0]++;
				$tmpA[1] = 1;
			}
			$today  = $tmpA[0]."-".$tmpA[1]."-".$tmpA[2];
			$todayTS = strtotime($today);
		} // end of this query loop

		$query_start	= date('Y-m-d',strtotime($today));
		$query_end	= date('Y-m-d',strtotime($query_start." + ".($this->list_query_size-1)." days"));
		if ( strtotime($query_end) > strtotime($this->end_date) ) $query_end = $this->end_date;
	}

	echo "</tbody><tfoot class=\"cal\">";

	if ( $evtShown ){
	
		echo "<tr><td colspan=\"4\"> ";
		if ( $this->auth->checkLogin() ){
			echo "&nbsp;&nbsp;<a class=\"admin\" href=\"".$this->php_self."?display=admin&amp;task=logout\"><font class=\"cal-admin-link\">logout</font></a>&nbsp;&nbsp;";
			$this->_popup_link("admin",$this->php_self."?display=admin&amp;task=changepw","<font class=\"cal-admin-link\">change pw</font>");

			if ( $this->auth->user->getPrivledge(UPEDIT+UPADMIN) ){
				echo "&nbsp;&nbsp;";
				$this->_popup_link("admin",$this->php_self."?display=admin&amp;task=add&returnto=list","<font class=\"cal-admin-link\">add event</font>");
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
	}else{
		echo "<tr><td>No Events found";
	}

	echo "</td></tr></tfoot></table>";
	include_once($this->footer);
}

function getmicrotime(){ 
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec); 
    } 
    
function _popup_link ($class='',$url='',$text=''){
	echo " <a class=\"".$class."\" ";
	if ($this->use_popups == 1 ) echo " onclick=\"launchevent('".$url."'); return false;\" ";
	echo " href=\"".$url."\">".$text."</a>";
}
			
} //end class.ltwCalendar

?>
