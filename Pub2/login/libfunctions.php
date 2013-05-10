<?php

function CheckNameValidity($name, &$msg) {
	// Is name set?
	if ($name == NULL) {
		$msg = "Name required. FIRST LAST or FIRST MI LAST. Please try again.";
		return(0);
	}
	// Is name alphanumeric?
	$narray = split("[ ]", $name);
	$parts = count($narray);
	if ( (!is_array($narray)) || ( $parts  < 2 )) {
		$msg = "Minimum FIRST LAST or FIRST MI LAST required. Please try again.";
		return(0);
		
	} else if ( $parts > 3 ) {
		$msg = "Maximum FIRST MI LAST required. Please try again.";
		return(0);
	
	} else {
		// We have names :-)
		for ($index=0; $index < ($parts); $index++) {
			$val = $narray[$index];
			//echo "next name: $val<br />";
			if ( !eregi("^[a-zA-Z]{1,40}$", $val) ) {
				$msg = "Name must be alpanumeric, FIRST LAST or FIRST MI LAST. Please try again.";
				return(0);
			}
		}
	}
	return(1);
}

function CheckEmailValidity($name, &$msg) {
	if ( $name == NULL) {
		$msg = "Email address required. Please try again.";
		return(0);
	}
	// Good! We have an email address but we should do some mild checking
	// just to see if it has any chance of being valid
			
	if ( (! eregi("@",$_POST['email'] )) || (! eregi("\.",$_POST['email']) )) { 
		$msg = "Invalid email address. Please try again.";
		return(0);
	}
	return(1);	
}

function CheckForDuplicates($reference, $field, $table) {
	$query = "SELECT $field FROM $table";
	$result = mysql_query($query);
	//$rows = mysql_numrows($result);
	//echo "rows: $rows";
	$tryagainval = 0;
	for ($count=0; $count < mysql_numrows($result); $count++) {
		$dbentry = mysql_result($result, $count, "$field");
		if($dbentry == $reference) {
			//echo "<p>Duplicate entry</p>";
			$tryagainval = 1;
			break;
		}
	}
	return($tryagainval);
}


function ReturnBoxHtml($returnname, $arrstring, $selectindex) {
	$size = sizeof($arrstring);
	
	$box_h = "<select size=\"".$size."\" name=\"".$returnname."\">";

	
	for($i=0; $i<$size; $i++) {
		
		if($i == ($selectindex)) {
			$selected = "selected ";
		} else {
			$selected = NULL;
		}
	$box_h .= 
	"<option ".$selected." value=\"".$arrstring[$i][0]."\">".$arrstring[$i][1]."</option>";
	}
	return($box_h);
}


function UpdateBoxSelection($lf, $retname, $typepairs, $tmsgpairs, $type_received, &$box_html, &$tmsg) {

/*
	$size = sizeof($tmsgpairs);
	$lf->logMessage("UpdateBox: sizeof tmsgpairs> ".sizeof($tmsgpairs)."message rec: ".$type_received."\n");
	for($i=0; $i<$size; $i++) {
		$lf->logMessage("UpdateBoxSelection.php: message> ".$tmsgpairs[$i]."\n");
	}
*/
	$tmsg = $tmsgpairs[$type_received];

	//$lf->logMessage("UpdateBox: ".$tmsg."\n");
	
	$box_html = ReturnBoxHtml($retname, $typepairs, $type_received);

}


function ConvertTypeAndPriv($u_type, $u_priv, &$tmsg, &$pmsg) {
	switch($u_type) {
				// 1 has immediate access to projects section only
				// 2 has immediate access to projects and residents section
				
				case 1:
					$tmsg = 'visitor';
					break;
				case 2:
					$tmsg = 'property owner/resident';
					break;
				case 3:
					$tmsg = 'contractor';
					break;
				default:
					$tmsg = 'unknown';
	}
	
	switch($u_priv) {
				/*
				POR 
					1 - Project and Document and Initiatives Access 
					2 - Committee Access 
					3 - Committee Chairman 
					4 - Board Member Access 
					5 - Board President
					6 - Website Access
					7 - Contractor Submit
					8 - Contractor Staff 
				*/
				case 1:
					$pmsg = "Project and Owner\/Resident Privileges";
					break;

				case 2:
					$pmsg = "Committee Privileges";
					break;
				case 3:
					$pmsg = "Committee Chairman";
					break;
				case 4:
					$pmsg = "Board Member Privileges";
					break;
				case 5:
					$pmsg = "Board President";
					break;
				case 6:
					$pmsg = "Webadmin";
					break;
				case 7:
					$pmsg = "Contractor Submit";
					break;
				case 8:
					$pmsg = "Contractor Staff";
					break;
				default:
					$addpriv = "Unknown";
			}

	return;

}
	
?>
