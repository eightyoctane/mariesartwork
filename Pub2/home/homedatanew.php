<?php
//define('HOMELOCATIONURL',"http://www.bluemountainairpark.com/Pub/");
//define('HOMELOCATIONURL',"http://184.173.246.35/~bmasite/Pub/");
$documentroot = $_SERVER['DOCUMENT_ROOT'];
//$documentroot = 'http://184.173.246.35/~bmasite';
$homelocationpart = $documentroot.'/';
$rootlocation = $documentroot."/Pub/";
$homelocationsub = "Pub/";
$regionandheader = "bma_RegionAndHeader.htm";
$regionandheaderfull = $rootlocation."bma_RegionAndHeader.htm";
$simpleregion = "bma_RegionSimple.htm";
$regiondef = "bma_RegionAndHeader.htm";

$headercontent = $rootlocation."HeaderContent.htm";
$homemenu = "homepageMenu.html";
$homemenufull = $rootlocation.$homemenu;
$homenufullA = $rootlocation.$homemenu;
$menucontent = $rootlocation."MenuContent.htm";
$loginbody = $rootlocation."login/login.html";	
//$logtofile = $rootlocation."login/osalog.txt";
$website = "http://www.bluemountainairpark.com";
//$website = "http://184.173.246.35/~bmasite";
$email = "eightyoctane@yahoo.com";
$errorlog = "errorlog.txt";
$errorlogfull = $documentroot."/".$errorlog;
$nameid = "Blue Mountain Airpark";
$logintracking = 'LoginTracking.txt';
$logintype = "bluemountain";

$utype = array("nobody" => 0, "visitor" => 1, "resident" => 2, "contractor" => 3);

$utypemsg = array( 	$utype["nobody"] => "User Type must be set",
					$utype["visitor"] => "Blue Mountain Visitor",
					$utype["resident"] => "Blue Mountain Owner/Resident",
					$utype["contractor"] => "Blue Mountain Contractor"
					);
		
$usertypepairs = array(
						array($utype['nobody'], $utypemsg[$utype['nobody']] ),
						array($utype['visitor'], $utypemsg[$utype['visitor']]),
						array($utype['resident'], $utypemsg[$utype['resident']]),
						array($utype['contractor'], $utypemsg[$utype['contractor']])
					);
					
$usertypemsg = array(
						'<font color="#FF0000">User Type must be set</font>',
						'<font color="#FF0000">Currently set to Visitor</font>',
						'<font color="#FF0000">Currently set to Property Owner/Resident</font>',
						'<font color="#FF0000">Currently set to Blue Mountain Contractor</font>'
					);
					
$privtypemsg = array(
						'<font color="#FF0000">Priviledge box must be set</font>',
						'<font color="#FF0000">Currently set to Project or Owner\/Resident Privileges</font>',
						'<font color="#FF0000">Currently set to Committee Privileges</font>',
						'<font color="#FF0000">Currently set to Committee Chairman</font>',
						'<font color="#FF0000">Currently set to Board Member Privileges</font>',
						'<font color="#FF0000">Currently set to Board President</font>',
						'<font color="#FF0000">Currently set to Webadmin</font>'
					);

$upriv = array( "nobody" => 0, 
				"anyowner" => 1, 
				"committee" => 2, 
				"commchair" => 3, 
				"board" => 4, 
				"boardpres" => 5, 
				"webadmin" => 6, 
				"submit" => 7, 
				"staff" => 8);


$uprivmsg = array(  $upriv["nobody"] => "User Privilege must be set",
					$upriv["anyowner"] => "Project or Owner\/Resident Privileges",
					$upriv["committee"] => "Committee Privileges",
					$upriv["commchair"] => "Committee Chairman Privileges",
					$upriv["board"] => "Board Member Privileges",
					$upriv["boardpres"] => "Board President Privileges",
					$upriv["webadmin"] => "Webadmin Privileges",
					$upriv["submit"] => "Contractor Submit Privileges",
					$upriv["staff"] => "Contractor Staff Privileges");
					
$privtypepairs = array(
						array($upriv['nobody'], $uprivmsg[$upriv['nobody']]),
						array($upriv['anyowner'], $uprivmsg[$upriv['anyowner']]),
						array($upriv['committee'], $uprivmsg[$upriv['committee']]),
						array($upriv['commchair'], $uprivmsg[$upriv['commchair']]),
						array($upriv['board'], $uprivmsg[$upriv['board']]),
						array($upriv['boardpres'], $uprivmsg[$upriv['boardpres']]),
						array($upriv['webadmin'], $uprivmsg[$upriv['webadmin']]),
						array($upriv['submit'], $uprivmsg[$upriv['submit']]),
						array($upriv['staff'], $uprivmsg[$upriv['staff']])
					);

$homemenulist = array( 	
						$utype['nobody'] => "homepageMenu.html", 						
						$utype['visitor'] => "homepageMenu.html", 
						$utype['resident'] => "login/porMenu.html",
						$utype['contractor'] => "login/contractorMenu.html");
						
$homemenulistfull = array( 	
						$utype['nobody'] => $rootlocation.$homemenulist[$utype['nobody']], 						
						$utype['visitor'] => $rootlocation.$homemenulist[$utype['visitor']], 
						$utype['resident'] => $rootlocation.$homemenulist[$utype['resident']],
						$utype['contractor'] => $rootlocation.$homemenulist[$utype['contractor']]);												
						

$generalfooter = $rootlocation."generalFooter.htm";
$mobilehomepagebody = $rootlocation."homepage_body_contentm.htm";
$homepagebody = $rootlocation."homepage_body_content.htm";					
					
$homelanding = array( 
			$utype['nobody'] => "index.php",	
			$utype['visitor'] => "login/bmarv.php",
			$utype['resident'] => "login/news.php",
			$utype['contractor'] => "login/contractorlanding.php");
						
$homelandingfull = array( 
						$utype['nobody'] => $rootlocation.$homelanding[$utype['nobody']],
						$utype['visitor'] => $rootlocation.$homelanding[$utype['visitor']],
                        $utype['resident'] => $rootlocation.$homelanding[$utype['resident']],
                        $utype['contractor'] => $rootlocation.$homelanding[$utype['contractor']]);

$homelandingurlfull = array(
 						$utype['nobody'] => $homelocationpart.$homelocationsub.$homelanding[$utype['nobody']],
						$utype['visitor'] => $homelocationpart.$homelocationsub.$homelanding[$utype['visitor']],
                        $utype['resident'] => $homelocationpart.$homelocationsub.$homelanding[$utype['resident']],
                        $utype['contractor'] => $homelocationpart.$homelocationsub.$homelanding[$utype['contractor']]);


// for session startup and initial menus
// 0> the user type
// 1> is the privilege required to get through session setup
// 2> the previous page to return to if session setup fails
// 3> the startup menu for this page
								
$adminauthinfo = array( 'adminutype' => $utype['resident'] , 
						'adminreqpriv' => $upriv['webadmin'], 
						'adminhomelandingurl' => $homelocationpart.$homelocationsub.$homelanding[$utype['resident']],
						'adminhomelandingfull' => $homelandingfull[$utype['resident']],
						'adminmenufull' => $homemenulistfull[$utype['resident']],
						'adminhtmlpage' => $rootlocation."login/htmlfiles/adminbody.htm");
						
						
?>
