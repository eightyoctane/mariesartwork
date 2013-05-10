<?php
define('HOMELOCATIONURL',"http://www.koffinscarclub.com/Pub2/");
$docroot = $_SERVER['DOCUMENT_ROOT']."/";
$sitedescription = "car club community website";

$rootlocation = $docroot."Pub2/";
$staticcontentloc = "login/htmlfiles/";
$homelocationpart = "http://www.koffinscarclub.com/";
$homelocationsub = "Pub2/";
$homelocationnewsurl = $homelocationpart.$homelocationsub."login/news.php";
$homelocationurl = $homelocationpart.$homelocationsub."index.php";
$regionandheader = "bma_RegionAndHeader.htm";
$regiondef = "RegionAndHeader.htm";
$regionandheaderfull = $rootlocation.$staticcontentloc.$regiondef;
$sitewidecssfull = $homelocationpart.$homelocationsub.$staticcontentloc."sitewide.css";
$simpleregion = "bma_RegionSimple.htm";
$regionandheadersplitfull = $rootlocation.$staticcontentloc."RegionAndHeaderSplit.htm";
$regionandheadertestfull = $rootlocation.$staticcontentloc."bma_RegionSimple2.htm";
$headercontent = $rootlocation."HeaderContent.htm";

$homemenuheadfull=$rootlocation.$staticcontentloc."headMenu.html";

$menucontentfull = $rootlocation.$staticcontentloc."MenuContent.htm";
$loginbody = $rootlocation.$staticcontentloc."login.html";

$footercontentfull = $rootlocation.$staticcontentloc."generalFooter.htm";
$sitetitlefull = $rootlocation.$staticcontentloc."siteTitleContent.htm";	

$cookiedomain = ".koffinscarclub.com";
$website = "http://www.koffinscarclub.com";
$email = "eightyoctane@yahoo.com";
$mailerfull = $rootlocation."login/"."phpmailer/";
$errorlog = "errorlog.txt";
$errorlogfull = $_SERVER['DOCUMENT_ROOT']."/".$errorlog;
$nameid = "Koffins Car Club";
$logintracking = 'LoginTracking.txt';
$logintype = "koffins";
$walldisplaymsglimit = 200;

//$utype = array("nobody" => 0, "visitor" => 1, "resident" => 2, "contractor" => 3);
$utype = array("nobody" => 0, "visitor" => 1, "friend" => 2, "resident" => 3, "contractor" => 4);


$utypemsg = array( 	$utype["nobody"] => "User Type must be set",
					$utype["visitor"] => "Koffins Visitor",
					$utype["resident"] => "Koffins Member",
					$utype["contractor"] => "Koffins Contractor"
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
						'<font color="#FF0000">Currently set to Member</font>',
						'<font color="#FF0000">Currently set to Contractor</font>'
					);
					
$privtypemsg = array(
						'<font color="#FF0000">Priviledge box must be set</font>',
						'<font color="#FF0000">Currently set to Visitor/Friend/Member Privileges</font>',
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
					$upriv["anyowner"] => "Visitor/Friend/Member Privileges",
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
						$utype['nobody'] => "login/htmlfiles/homepageMenu.html", 						
						$utype['visitor'] => "login/htmlfiles/homepageMenu.html", 
						$utype['resident'] => "login/htmlfiles/porMenu.html",
						$utype['contractor'] => "login/htmlfiles/contractorMenu.html");
						
$homemenulistfull = array( 	
						$utype['nobody'] => $rootlocation.$homemenulist[$utype['nobody']], 						
						$utype['visitor'] => $rootlocation.$homemenulist[$utype['visitor']], 
						$utype['resident'] => $rootlocation.$homemenulist[$utype['resident']],
						$utype['contractor'] => $rootlocation.$homemenulist[$utype['contractor']]);												
						
$homemenu = $homemenulist[$utype['nobody']];
$homemenufull = $rootlocation.$homemenu;
$homenufullA = $rootlocation.$homemenu;

$generalfooter = $rootlocation."generalFooter.htm";
$mobilehomepagebody = $rootlocation."login/htmlfiles/homepage_body_contentm.htm";
$homepagebody = $rootlocation."login/htmlfiles/homepage_body_content.htm";					
					
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