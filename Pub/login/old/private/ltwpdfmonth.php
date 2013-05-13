<?php
////////////////////////////////////////////////////////////////////
// Class ltwCalendar
// $Id: ltwpdfmonth.php 28 2006-04-01 23:38:13Z mootinator $
//
// Displays the calendar in month, day, and event formats
////////////////////////////////////////////////////////////////////
require($ltw_config['include_dir'].'/fpdf.php');
class PDF extends FPDF
{
//Page header
function Header()
{
  //Unused
}
}

class ltwPdfMonth {
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
function ltwPdfMonth($timestamp) {
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
	
    $pdf=new PDF($orientation='L',$unit='mm',$format='LETTER');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',15);
	$pdf->Cell(0,10, $this->month_name . ' ' . $this->year ,0,0,'C');
	$pdf->SetFont('Arial','',10);
	$pdf->SetY(25);
        $pdf->SetDrawColor(128,128,128);
	$pdf->SetFillColor(0,0,139);
	$pdf->SetTextColor(255,255,255);
	foreach($this->daynames as $pdf_day){
	    $pdf->Cell(37,8,$pdf_day,1,0,'C',1);
	}
	$pdf->Ln();
	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(0,0,0);
	for($j=0;$j<$num_of_rows;$j++){
		for($i=0;$i<7;$i++){
			$pdf->Cell(37,floor(162/$num_of_rows),'',1,0,'R');
		}
		$pdf->Ln();
	}
	for ( $i = 1; $i <= $num_of_rows; $i++ ){
		$y_spacing = floor(162/$num_of_rows);
		$y_offset=33 + $y_spacing * ($i-1);
		for ( $j = 0; $j < 7; $j++ ){
			$x_offset = 10 + 37 * $j;
			$pdf->SetY($y_offset);
			$pdf->SetX($x_offset);
	  		if ( (($i == 1) && ($this->first_day_of_month <= $j)) || (($i > 1) && ($day <= $this->days_in_month)) ){
				
                                $pdf->SetFont('Arial','B','10');
				$pdf->Cell(36,4,$day,0,0,'R');
				$pdf->Ln();
				$pdf->SetFont('Arial','','8');
					
				// date to store in DB is CREATED like this
				$today   = date('Y-m-d',strtotime($this->year . "-" . $this->month . "-" . $day));
				$todayTS = strtotime($today);
				
				// TODO: if($this->bullets == 'TRUE') echo "<ul class=\"cal\">\n";

				// loop thru event array
				for ( $e = 0 ; $e < $evtMax ; $e++ ){
					$event_dateTS = strtotime($evtA[$e]['event_date']);
					$event_endTS  = strtotime($evtA[$e]['event_end']);

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
							//if ( $this->bullets == 'TRUE' ) echo "<li>";
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

							// Set Foreground Color
                                                        $r = intval(substr($fgcolor,1,2),16);
                                                        $g = intval(substr($fgcolor,3,2),16);
                                                        $b = intval(substr($fgcolor,5,2),16);
                                                        $pdf->SetTextColor($r,$g,$b);

                                                        // Set Background Color
                                                        $r = intval(substr($bgcolor,1,2),16);
                                                        $g = intval(substr($bgcolor,3,2),16);
                                                        $b = intval(substr($bgcolor,5,2),16);
                                                        $pdf->SetFillColor($r,$g,$b);
							if ( $showtime ) $text .= $start_time." - ";
							$text .= $evtA[$e]['name'];

							// Display the title
							$pdf->SetX($x_offset + 0.5);
							$pdf->MultiCell(36,3,$text,0,'L',1);
                                                        $pdf->SetTextColor(0,0,0);
                                                        $pdf->SetFillColor(255,255,255);
						}
					}
				}
				$day++;
			}
		}
	}
	$pdf->Output();
} 

} //end class.ltwCalendar

?>
