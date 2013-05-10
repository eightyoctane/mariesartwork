<?php 

/* 
We may not be logged in when we get here

This page for projects privilege 
anyone who's logged in can use this page

we can't assume a session in progress
if we're not logged in make them log in first
have to check $_SESSION['UserName'] directly
this is because we can wind up here from an un logged in events page

*/

include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
include_once($rootlocation."PresenterClass.php");	
include_once($rootlocation.'logFileClass.php');
	
$lf = new logFile();

// this because we could come here from events page
// make them go back and log in to get to the projects page

$gp = new generalPageBrowser($regionandheaderfull);


if(!$gp->validateInSession()) {
	$loginpath = $homelocationpart.$homelocationsub.'login/login.php';
	$lf->logMessage("projects.php: Trying to access projects page but not logged in. Going to ".$loginpath."\n");
	header('Location: '.$loginpath);
	exit;

} else {

	/* Ok so now we know we're in a session and it's ok to proceed,
	but because of this special case which is a "visitor only" user type
	we have to do some special to insure that a consistent set of menu
	options appear. 	
	*/
	$sp = new sessionPageBrowser($regionandheaderfull,$upriv['nobody'],$homelandingurlfull[$utype['visitor']]);
	
	$sp->addBodyContent($rootlocation.$staticcontentloc."projectsBody.htm");
	$sp->renderPage();	
}
?>
