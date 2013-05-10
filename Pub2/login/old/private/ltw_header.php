<?php
//////////////////////////////////////////////////////////////////////////// 
// ltw_header.php 
// $Id: ltw_header.php,v 1.3 2010/12/07 11:41:52 bluemov2 Exp $ 
// 
// ltwCalendar Sample Header File
//////////////////////////////////////////////////////////////////////////// 

header("Cache-control: no-cache, must-revalidate");
header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");

echo "
<HTML>
<HEAD>
<TITLE>BMA - Calendar</TITLE>
<LINK REL=\"stylesheet\" TYPE=\"text/css\" HREF=\"ltw_style.css\">
<!-- The following functions need to be declared in the page header for the -->
<!-- javascript popups to work.                                             -->
<SCRIPT LANGUAGE=\"JavaScript\" type=\"text/javascript\"> 
<!-- 
function launchcategory(url){
	window.name = 'opener'; 
	remote = open(url, \"\", \"resizable,status,scrollbars,width=500,height=400,left=300,top=100\");
} 
function launchevent(url){
	self.name = \"opener\"; 
	remote = open(url, \"\", \"resizable,scrollbars,width=400,height=600,left=300,top=100\");
} 
function launchlog(url){
	window.name = 'opener'; 
	remote = open(url, \"\", \"resizable,status,scrollbars,width=600,height=650,left=300,top=100\");
} 
function launchlogin(url){
	window.name = 'opener'; 
	remote = open(url, \"\", \"resizable,status,scrollbars,width=400,height=300,left=300,top=100\");
} 
function launchuser(url){
	window.name = 'opener'; 
	remote = open(url, \"\", \"resizable,status,scrollbars,width=650,height=600,left=300,top=100\");
} 
--> 
</SCRIPT>
</HEAD>
<BODY>
<TABLE>
<tr><td colspan=\"2\" align=\"center\"><H1>Blue Mountain Property Owner/Resident Events</H1></td></tr>
<tr><td width=\"10%\"> </td>

<!-- ltwCalendar Main window starts here -->
<td width=\"90%\">

";

?>
