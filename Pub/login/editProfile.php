<?php 
	session_start();
	include_once("sPageClass.php");
	include_once("../logFileClass.php");
	$lf = new logFile();
	$sp = new sPage("../bma_RegionAndHeader.htm");
	$sp->validateSession(2,1,"por.php");		
	$sp->addMenu("porMenu.html");	
	$_SESSION['prevpage'] = $_SESSION['homepage'];
	
	$mysqldb = new mysql("localhost","bluemov2_vadmin","1200", "bluemov2_visitors");

	$mysqldb->connect();
	$mysqldb->select();

	

	$of = new rForm("rFormA.html");
	$of->setWebsite("www.bluemountainairpark.com");
	$of->setFormName("User Profile Editor - Password will be changed only if a new value is entered");
	$of->setEditCallBackPgm("profileEdit.php");
	$ids = $o->getEVListCount();

	$mysqldb->query("SELECT rowID, name, username, email, add_apt, add_street, add_city, add_state, add_zip, add_country, u_type, phone FROM user WHERE rowID='$idnum[0]'");
				$numrows = $mysqldb->numRows();
				switch ($numrows) {
					case 1:
						$row = $mysqldb->fetchObject();
						//print_r($row);
						//echo "name: $row->name <br />";
						$of->setHiddenValue("editid", $row->rowID);
						$of->setHiddenValue("unamesaved", $row->username);
						$of->setHiddenValue("u_type", $row->u_type);
						//echo "u_type: $row->u_type <br />";
						$of->renderRform(NULL, NULL, NULL, NULL, $row->name,$row->username,$row->email,$row->add_apt,
						$row->add_street,$row->add_city,$row->add_state,$row->add_zip,$row->add_country,$row->phone,$row->u_type);
						break;
					case 0:
						echo "Database error, profile could not be retrieved <br />";
						echo renderprofiles($mysqldb, $o);
						break;
					default:
						echo "More than one ID was returned from the search <br />";
						echo renderprofiles($mysqldb, $o);
				}








	
	$sp->addBodyContent("htmlfiles/FAQBody.htm");
	$sp->renderSessionPage();
?>
