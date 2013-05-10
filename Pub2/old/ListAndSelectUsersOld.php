<?php
include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
include_once($rootlocation."login/dbdata.php");
include_once($rootlocation."login/mysqlClass.php");
include_once($rootlocation."login/ListAndSelectUsersClass.php");
include_once($rootlocation."login/rFormClass.php");
require_once($rootlocation."login/libfunctions.php");
include_once($rootlocation."PresenterClass.php");
include_once($rootlocation."logFileClass.php");
	
$lf = new logFile();
$sp = new sessionPageBrowser($rootlocation."bma_RegionAndHeader.htm",$upriv['webadmin'],$homelandingurlfull[$utype['resident']]);
	
//$sp->addHeader($rootlocation."HeaderContent.htm");


$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);

$mysqldb->connect();
$mysqldb->select();

//echo "opening the database <br />";	

$o = new ListAndSelectUsers();
$o->addTableHeader();
$o->addCallback($homelocationpart.$homelocationsub."login/ListAndSelectUsers.php");

if(!isset($_POST['VE']) && (!isset($_POST['RM']))) {
	/* Render the list of all user profiles */
	$lf->logMessage("ListAndSelectUsers: Rendering all profiles\n");
	$sp->addBodyText(renderprofiles($mysqldb, $o));
	$sp->renderPage();		

} else {
	if(isset($_POST['VE'])) {
	/* We have just gotten the list of user profiles to view/edit or remove. 
	   For our purposes view and edit are the same. */
	
		//echo "pressed VE <br />";
		// We can only handle editing one profile at a time right now
		if($o->getEVListCount() == 0) {
			$sp->setErrorStatus("You must checkmark a profile to edit");			
			$sp->addBodyText(renderprofiles($mysqldb, $o));
			$sp->renderPage();
		} else if($o->getEVListCount() > 1) {
			$sp->setErrorStatus("Only one user profile can be edited at a time");
			$sp->addBodyText(renderprofiles($mysqldb, $o));
			$sp->renderPage();
		} else {
			
			$ids = $o->getEVListCount();
			
			if ($ids > 1) {
				$sp->setErrorStatus("More than one profile was selected. Select only one to edit");
				$sp->addBodyText(renderprofiles($mysqldb, $o));
				$sp->renderPage();
			} else {
				$idnum = $o->getEVList();
				//print_r($idnum);
				//$mysqldb->query("SELECT rowID, name, username FROM user WHERE rowID = '$idnum[0]'");
				$mysqldb->query("SELECT rowID, name, username, email, add_apt, add_street, add_city, add_state, add_zip, add_country, u_type, u_priv, phone FROM user WHERE rowID='$idnum[0]'");
				$numrows = $mysqldb->numRows();
				switch ($numrows) {
					case 1:
						$row = $mysqldb->fetchObject();
						//print_r($row);
						//echo "name: $row->name <br />";
						$of = new rForm($rootlocation."login/rFormA.html");
						$of->setWebsite($website);
						$of->setFormName("User Profile Editor - Password will be changed only if a new value is entered");
						$of->setEditCallBackPgm($homelocationpart.$homelocationsub."login/profileEdit.php");
						$of->setHiddenValue("editid", $row->rowID);
						$of->setHiddenValue("unamesaved", $row->username);
						$of->setHiddenValue("u_type", $row->u_type);
						$of->setHiddenValue("u_priv", $row->u_priv);
						$lf->logMessage("ListAndSelectUsers: u_type: ".$row->u_type." u_priv: ".$row->u_priv."\n");
						//echo "u_type: $row->u_type <br />";	
						
						UpdateTypeBoxSelection($row->u_type,&$tboxhtml,&$tmsg);
						UpdatePrivBoxSelection($row->u_priv,&$pboxhtml,&$pmsg);						
						
						$text = $of->renderRformText(NULL, NULL, NULL, NULL, $row->name,$row->username,$row->email,$row->add_apt,
						$row->add_street,$row->add_city,$row->add_state,$row->add_zip,$row->add_country,$row->phone,$tboxhtml,$pboxhtml,$tmsg,$pmsg);
						
						$sp->addHeader($rootlocation."HeaderContent.htm");
						
						$sp->addBodyText($text);
						
						$sp->renderPage();
						
						break;
					case 0:
						$sp->setErrorStatus("Database error, profile could not be retrieved");
						$sp->addHeader($rootlocation."HeaderContent.htm");

						$sp->addBodyText(renderprofiles($mysqldb, $o));
						$sp->renderPage();
						break;
					default:
						$sp->setErrorStatus("More than one ID was returned from the search");
						$sp->addHeader($rootlocation."HeaderContent.htm");

						$sp->addBodyText(renderprofiles($mysqldb, $o));
						$sp->renderPage();				}
			}		
		}
	}
	
	if (isset($_POST['RM'])) {
			// Remove
		//echo "RM pressed. <br />";
		//echo "The following was deleted: <br />";
		if($o->getEVListCount() == 0) {
			$sp->setErrorStatus("You must checkmark one or more profiles to delete");			
			echo renderprofiles($mysqldb, $o);
			exit();
		}
		foreach($_POST['slist'] AS $dlistitem) {	
			
			$mysqldb->query("DELETE from user WHERE rowID=$dlistitem");
			//echo "$dlistitem<br />";
		}
		// Finished deleting to return to previous page
		// **FIX - this location should be a session variable
		header('Location: '.$homelocationpart.$homelocationsub.'login/admin.php');
	}
}

function renderprofiles($database, $screen) {
//echo "getting the user profiles from the database <br />";
		$database->query("SELECT rowID, name, username FROM user ORDER BY name");
		$count = $database->numRows();
		if ($count == 0) {
			$sp->setErrorStatus("You have no user profiles in the database");
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
