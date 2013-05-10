<?php

function renderReturnToHome($ButtonName, $HomeLocation) {
	$Content .="
<html>

<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">
<title>artmarie.com return to homepage</title>
</head>

<body>
<p><a href=\"$HomeLocation\">
<img border=\"0\" id=\"img2\" src=\"RtnToHomePage.jpg\" height=\"30\" width=\"150\" alt=\"$ButtonName\" 
fp-style=\"fp-btn: Embossed Capsule 1; fp-img-hover: 0; fp-img-press: 0; fp-preload: 0\" 
fp-title=\"$ButtonName\"></a></p>

</body>

</html>";
return ("$Content");
}


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

function UpdateTypeBoxSelection($type_received, &$box_html, &$tmsg) {
	switch($type_received) {
		default:
		case 0:
			$box_html = '<select size="3" name="u_type">'.
			'<option selected value="0">Please Select</option>'.
			'<option value="1">Consumer</option>'.
			'<option value="2">Art Dealer</option>'.
			'</select>';
			$tmsg = '<font color="#FF0000">User Type box must be set</font>';
			break;
		case 1:
			$box_html = '<select size="3" name="u_type">'.
			'<option value="0">Please Select</option>'.
			'<option selected value="1">Consumer</option>'.
			'<option value="2">Art Dealer</option>'.
			'</select>';
			$tmsg = 'Currently set to Consumer';
			break;
		case 2:
			$box_html = '<select size="3" name="u_type">'.
			'<option value="0">Please Select</option>'.
			'<option value="1">Consumer</option>'.
			'<option selected value="2">Art Dealer</option>'.
			'</select>';
			$tmsg = 'Currently set to Art Dealer';
			break;
	}
}

function UpdatePrivBoxSelection($priv_received, &$box_html, &$pmsg) {
	switch($priv_received) {
		default:
		case 0:
			$box_html = '<select size="2" name="u_priv">'.
			'<option selected value="0">Please Select</option>'.
			'<option value="1">Priv level 1</option>'.
			'<option value="2">Priv level 2</option>'.
			'<option value="3">Priv level 3</option>'.
			'<option value="4">Priv level 4</option>'.
			'<option value="5">Priv level 5</option>'.
			'<option value="6">Webadmin Access</option>'.
			'</select>';
			$pmsg = '<font color="#FF0000">Priviledge box must be set</font>';
			break;
		case 1:
			$box_html = '<select size="2" name="u_priv">'.
			'<option value="0">Please Select</option>'.
			'<option selected value="1">Priv level 1</option>'.
			'<option value="2">Priv level 2</option>'.
			'<option value="3">Priv level 3</option>'.
			'<option value="4">Priv level 4</option>'.
			'<option value="5">Priv level 5</option>'.
			'<option value="6">Webadmin Access</option>'.
			'</select>';
			$pmsg = "Priv level 1";
			break;
		case 2:
			$box_html = '<select size="2" name="u_priv">'.
			'<option value="0">Please Select</option>'.
			'<option value="1">Priv level 1</option>'.
			'<option selected value="2">Priv level 2</option>'.
			'<option value="3">Priv level 3</option>'.
			'<option value="4">Priv level 4</option>'.
			'<option value="5">Priv level 5</option>'.
			'<option value="6">Webadmin Access</option>'.
			'</select>';
			$pmsg = "Priv level 2";
			break;
		case 3:
			$box_html = '<select size="2" name="u_priv">'.
			'<option value="0">Please Select</option>'.
			'<option value="1">Priv level 1</option>'.
			'<option value="2">Priv level 2</option>'.
			'<option selected value="3">Priv level 3</option>'.
			'<option value="4">Priv level 4</option>'.
			'<option value="5">Priv level 5</option>'.
			'<option value="6">Webadmin Access</option>'.
			'</select>';
			$pmsg = "Priv level 3";
			break;
		case 4:
			$box_html = '<select size="2" name="u_priv">'.
			'<option value="0">Please Select</option>'.
			'<option value="1">Priv level 1</option>'.
			'<option value="2">Priv level 2</option>'.
			'<option value="3">Priv level 3</option>'.
			'<option selected value="4">Priv level 4</option>'.
			'<option value="5">Priv level 5</option>'.
			'<option value="6">Webadmin Access</option>'.
			'</select>';
			$pmsg = "Priv level 4";
			break;
		case 5:
			$box_html = '<select size="2" name="u_priv">'.
			'<option value="0">Please Select</option>'.
			'<option value="1">Priv level 1</option>'.
			'<option value="2">Priv level 2</option>'.
			'<option value="3">Priv level 3</option>'.
			'<option value="4">Priv level 4</option>'.
			'<option selected value="5">Priv level 5</option>'.
			'<option value="6">Webadmin Access</option>'.
			'</select>';
			$pmsg = "Priv level 5";
			break;
		case 6:
			$box_html = '<select size="2" name="u_priv">'.
			'<option value="0">Please Select</option>'.
			'<option value="1">Project and Resident or Owner Access Only</option>'.
			'<option value="2">Committee Access</option>'.
			'<option value="3">Committee Chairman Access</option>'.
			'<option value="4">Board Member Access</option>'.
			'<option value="5">Board President Access</option>'.
			'<option selected value="6">Webadmin Access</option>'.
			'</select>';
			$pmsg = "Webadmin";
			break;
	}
}

function getUserPrivMessage($u_priv) {

			switch($u_priv) {
				/*
				POR 
					1 - Project and Document and Initiatives Access 
					2 - Committee Access 
					3 - Committee Chairman 
					4 - Board Member Access 
					5 - Board President
					6 - Website Access 
				*/
				case 1:
					$addpriv = " priv 1";
					break;

				case 2:
					$addpriv = " priv 2";
					break;
				case 3:
					$addpriv = " priv 3";
					break;
				case 4:
					$addpriv = " priv 4";
					break;
				case 5:
					$addpriv = " priv 5";
					break;
				case 6:
					$addpriv = " as the Webadmin";
					break;

				default:
					$addpriv = "";
			}
			
		return $addpriv;

}

function getUserTypeMessage($u_type) {
			switch($u_type) {

				case 1:
					$message = "Logged in as Consumer User";
					break;
				case 2:
					$message = "Logged in as Dealer User".$addpriv;
					break;
				default:
					$message = "Logged in as Undefined";
			}

	return $message;

}


function ConvertTypeAndPriv($u_type, $u_priv, &$tmsg, &$pmsg) {
	switch($u_type) {
				// 1 has immediate access to projects section only
				// 2 has immediate access to projects and residents section
				
				case 1:
					$tmsg = 'Consumer';
					break;
				case 2:
					$tmsg = 'Dealer';
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
				*/
				case 1:
					$pmsg = "Priv level 1";
					break;

				case 2:
					$pmsg = "Priv level 2";
					break;
				case 3:
					$pmsg = "Priv level 3";
					break;
				case 4:
					$pmsg = "Priv level 4";
					break;
				case 5:
					$pmsg = "Priv level 5";
					break;
				case 6:
					$pmsg = "Webadmin";
					break;

				default:
					$addpriv = "Unknown";
			}

	return;

}
	
?>
