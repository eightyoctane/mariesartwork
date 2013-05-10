<?php 
session_start();


/* 
We may not be logged in when we get here

This page for projects privilege 
anyone who's logged in can use this page

we can't assume a session in progress
if we're not logged in make them log in first
have to check $_SESSION['UserName'] directly
this is because we can wind up here from an un logged in events page

*/

include_once($_SERVER['DOCUMENT_ROOT']."/homedataV0.php");
include_once($rootlocation."PresenterClass.php");	
include_once($rootlocation.'logFileClass.php');
include_once($rootlocation."login/sessionSetup.php");
	
$lf = new logFile();

// this because we could come here from events page
// make them go back and log in to get to the projects page

if(!$_SESSION['UserName']) {

	$lf->logMessage("Trying to access projects page but not logged in\n");
	header('Location: '.$homelocation.$homelocationsub.'login/login.php');

} else {

	/* Ok so now we know we're in a session and it's ok to proceed,
	but because of this special case which is a "visitor only" user type
	we have to do some special to insure that a consistent set of menu
	options appear. 	
	*/
	$sp = new sessionPageBrowser($rootlocation.$regiondef);
	
	$usertype = $sp->getUserType();
	
	sessionSetup($sp,$lf,$usertype,1,$homelocationpart.$homelocationsub.$homelanding[$usertype]);
	$sp->addMenu($rootlocation.$homemenulist[$usertype]);
	$_SESSION['prevpage'] = $homelocationpart.$homelocationsub.$homelanding[$usertype];			
	$sp->addBodyContent($rootlocation."login/htmlfiles/projectsBody.htm");
	$sp->renderPage();	
}
?>
