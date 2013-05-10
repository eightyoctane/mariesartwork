<?php
include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
include_once($rootlocation."PresenterClass.php");	
include_once($rootlocation.'logFileClass.php');
require_once($rootlocation."login/libfunctions.php");
include_once($rootlocation."login/mysqlClass.php");
include_once($rootlocation."login/rFormClass.php");
include_once($rootlocation."dbdata.php");
include_once($rootlocation."login/ListAndSelectUsersClass.php");
include_once($rootlocation.'/logFileClass.php');

$lf = new logFile();

$sp = new sessionPageBrowser($regionandheaderfull,$upriv['anyowner'],$homelandingurlfull[$utype['webadmin']]);

$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
$mysqldb->connect();
$mysqldb->select();

//$lf->logMessage("ListAndSelectUsers.php: database opened\n");	

$o = new ListAndSelectUsers();
$o->addTableHeader();
$o->addCallback($homelocationpart.$homelocationsub."login/ListAndSelectUsers.php");

if(!isset($_POST['VE']) && (!isset($_POST['RM']))) {
	/* Render the list of all user profiles */
	//$lf->logMessage("ListAndSelectUsers.php: rendering the list of user profiles\n");
	$sp->addBodyText(renderprofiles($mysqldb, $o));
	$sp->renderPage();		

} else {
	if(isset($_POST['VE'])) {
	/* We have just gotten the list of user profiles to view/edit or remove. 
	   For our purposes view and edit are the same. */
		//$lf->logMessage("ListAndSelectUsers.php: detected view/edit\n");
		//echo "pressed VE <br />";
		// We can only handle editing one profile at a time right now
		if($o->getEVListCount() == 0) {
			$sp->setErrorStatusBarMessage("You must checkmark a profile to edit");			
			$sp->addBodyText(renderprofiles($mysqldb, $o));
			$sp->renderSessionPage();
		} else if($o->getEVListCount() > 1) {
			$sp->setErrorStatusBarMessage("Only one user profile can be edited at a time");
			$sp->addBodyText(renderprofiles($mysqldb, $o));
			$sp->renderPage();
		} else {
			
			$ids = $o->getEVListCount();
			
			if ($ids > 1) {
				$sp->setErrorStatusBarMessage("More than one profile was selected. Select only one to edit");
				$sp->addBodyText(renderprofiles($mysqldb, $o));
				$sp->renderPage();
			} else {
				$idnum = $o->getEVList();
				$mysqldb->query("SELECT rowID, name, username, email, add_apt, add_street, add_city, add_state, add_zip, add_country, u_type, u_priv, phone, status, vid, e_notify FROM user WHERE rowID='$idnum[0]'");
				$numrows = $mysqldb->numRows();
				
				switch ($numrows) {
				case 1:
					$row = $mysqldb->fetchObject();
					//print_r($row);
					//echo "name: $row->name <br />";
					//$header = "Location: /login/profileEdit.php?id=2";
					$lf->logMessage("ListAndSelectUsers.php: Editing profile for uid number: ".$row->rowID."\n");
					//header($header);
					
					//we do it this way because we don't want to reveal the id we're editing to the user
					$of = new rForm($rootlocation."login/rFormABasicW.html");
					
					$of->setWebsite($nameid);
					$of->setFormName("User Profile Editor - Password will be changed only if a new value is entered");
					$of->setEditCallBackPgm($homelocationpart.$homelocationsub."login/profileEdit.php");
					$of->setHiddenValue("editid", $row->rowID);
					$of->setHiddenValue("unamesaved", $row->username);
					$of->setHiddenValue("u_type", $row->u_type);
					$of->setHiddenValue("u_priv", $row->u_priv);
					
					if($row->e_notify == 1) {
						$of->setCheckBox("notifyme", 1);
					} else {
						$of->setCheckBox("notifyme", 0);
					}


					$of->setVIDField($row->vid);

					//$lf->logMessage("ListAndSelectUsers.php:\n".print_r($row,TRUE));
					if($row->status === "active") {
						$of->setCheckbox("acctstatus", 1);
						//$lf->logMessage("ListAndSelectUsers.php: This account status active\n");
					} else {
						$of->setCheckbox("acctstatus", 0);
						//$lf->logMessage("ListAndSelectUsers.php: This account status inactive\n");
					}

				
					//$lf->logMessage("ListAndSelectUsers.php: u_type: ".$row->u_type." u_priv: ".$row->u_priv."\n");
					
					
					UpdateBoxSelection($lf, "u_type", $usertypepairs, $usertypemsg, $row->u_type,&$tboxhtml,&$tmsg);					
					UpdateBoxSelection($lf, "u_priv", $privtypepairs, $privtypemsg, $row->u_priv,&$pboxhtml,&$pmsg);						

					$size = sizeof($privtypemsg);
					//$lf->logMessage("ListAndSelectUsers.php: size = ".$size." Priv Level: ".$row->u_priv."\n");
/*					
					for($i=0; $i<$size; $i++) {
						//$lf->logMessage("ListAndSelectUsers.php: privilege> ".$privtypemsg[$i]."\n");
					}
*/					
					//$lf->logMessage("ListAndSelectUsers.php: pmsg> ".$pmsg."\n");

					//$text = $of->renderRformText(NULL, NULL, NULL, NULL, $row->name,$row->username,$row->email,$row->add_apt,
					//$row->add_street,$row->add_city,$row->add_state,$row->add_zip,$row->add_country,$row->phone,$tboxhtml,$pboxhtml,$tmsg,$pmsg);
						
	
					$text = $of->renderRformText(NULL, NULL, NULL, NULL, $row->name,$row->username,$row->email,$row->add_apt,
					$row->add_street,$row->add_city,$row->add_state,$row->add_zip,$row->add_country,$row->phone,$tboxhtml,$pboxhtml,$tmsg,$pmsg);
					
					$sp->addBodyText($text);
					
					$sp->renderPage();					
					
					exit();
				case 0:
					$sp->setErrorStatus("Database error, profile could not be retrieved");
					$sp->addBodyText(renderprofiles($mysqldb, $o));
					$sp->renderPage();
					exit();
				default:
					$sp->setErrorStatus("More than one ID was returned from the search");
					$sp->addBodyText(renderprofiles($mysqldb, $o));
					$sp->renderPage();
					exit();				}
				}		
		}
	}
	
	if (isset($_POST['RM'])) {
			// Remove
		//echo "RM pressed. <br />";
		//echo "The following was deleted: <br />";
		if($o->getEVListCount() == 0) {
			$sp->setErrorStatus("You must checkmark one or more profiles to delete");			
			//echo renderprofiles($mysqldb, $o);
			$sp->addBodyText(renderprofiles($mysqldb, $o));
			$sp->renderPage();
			exit();
		}
		foreach($_POST['slist'] AS $dlistitem) {	
			
			$mysqldb->query("DELETE from user WHERE rowID=$dlistitem");
			//echo "$dlistitem<br />";
		}
		// Finished deleting to return to previous page
		// **FIX - this location should be a session variable
		header('Location: '.$homelocationpart.$homelocationsub."login/ListAndSelectUsers.php");
	}
}

function renderprofiles($database, $screen) {
//echo "getting the user profiles from the database <br />";
		$database->query("SELECT rowID, name, username FROM user ORDER BY name");
		$count = $database->numRows();
		if ($count == 0) {
			$sp->setErrorStatusBarMessage("You have no user profiles in the database");
		} else {		
			while($count--) {
				$row = $database->fetchObject();
				$screen->addRow($row->rowID, $row->name, $row->username);
			}
			
			$Content = $screen->renderLSScreen();
		
			return("$Content");
		}	
}
?>
