<?php

// ----------------------------------------------------------------------------
// Name: navbar.php
// Author: R Sean Eidemiller (mojo@halcyon.cc)
// Last Modified: January 24, 2003
// ----------------------------------------------------------------------------

session_start();

// Check to make sure current session is valid

if (!session_is_registered("WIMS_SESSION")) {
   header("Location: /phpwims/index.php?error=4");
   exit();
}

// Generate date/time and strip newlines

$day = trim(`/bin/date +%d`);
$month = trim(`/bin/date +%m`);
$year = trim(`/bin/date +%Y`);
$time = trim(`/bin/date +%r`);
$timezone = trim(`/bin/date +%Z`);

// ----------------------------------------------------------------------------
print <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript">

function openChangelog()
{
   window.open('docs/changelog.txt','Changelog','scrollbars=yes,resizable=yes');
}

function openDocumentation()
{
   window.open('docs/documentation.txt','Documentation','scrollbars=yes,resizable=yes');
}

</script>
</head>
<body bgcolor="#ffffff">
   <font size=1>
      <table border=0 cellpadding=3 cellspacing=0 width="100%">
	 <tr>
	    <td width="100%" bgcolor="#808080">
	       <font face="arial,helvetica" size=2 color="#ffffff">
		  <b><u>Navigation Toolbar</u></b>
	       </font>
	    </td>
	 </tr>
      </table>
      <br>
   </font>
   <font face="arial,helvetica" size=2>
      <b>Date:</b> $month/$day/$year<br>
      <b>Time:</b> $time $timezone
      <br><br>
      <a href="http://phpwims.sourceforge.net"
	 target="resource window"><img border=0 src="images/logo.png"></a>
      <br><br>
   </font>
   <table border=0 cellpadding=4 cellspacing=0>
      <tr>
	 <td bgcolor="#eeeeee">
            <a href="navigator.php"
               target="main"><font face="arial,helvetica"
		  size=2>Cellar Navigator</font></a>&nbsp;
            <br>
            <a href="submit.php"
               target="main"><font face="arial,helvetica"
                  size=2>Submit Bottle(s)</font></a>&nbsp;
            <br>
            <a href="report.php"
               target="main"><font face="arial,helvetica"
                  size=2>Generate Report</font></a>&nbsp;
            <br>
            <a href="log.php"
               target="main"><font face="arial,helvetica"
                  size=2>Transaction Log</font></a>&nbsp;
            <br>
            <a href="charts.php"
               target="main"><font face="arial,helvetica"
                  size=2>Vintage Charts</font></a>&nbsp;
	    <br>
	    <a href="list.php"
               target="main"><font face="arial,helvetica"
                  size=2>Shopping List</font></a>&nbsp;
            <font size=1><br><br></font>
            <a href="admin.php"
               target="main"><font face="arial,helvetica"
                  size=2>Admin Tools</font></a>&nbsp;
            <font size=1><br><br></font>
            <a href="logout.php" target="_parent"><font
	       face="arial,helvetica" size=2>Logout</font></a>&nbsp;
            <font size=1><br><br></font>
            <a href="javascript:openDocumentation()"><font size=2
               face="arial,helvetica">Documentation</font></a>
            <br>
            <a href="javascript:openChangelog()"><font size=2
               face="arial,helvetica">Changelog</font></a>
            <br>
	    <a href="http://phpwims.sourceforge.net"
               target="resource window"><font size=2
                  face="arial,helvetica">Project Home</font></a>
	 </td>
      </tr>
   </table>
   <br>
   <font face="arial,helvetica" size=2>
      <b>Username:</b>&nbsp;
EOF;
// ----------------------------------------------------------------------------

if ($WIMS_UNAME == "admin") {
   print "<font color='#ff0000'>admin</font>\n";
} else {
   print "$WIMS_UNAME\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
   <br>
   <font size=1>
      <br>
      This program may be distributed freely under the terms of the GNU General
	 Public License.
      <br><br>
EOF;
// ----------------------------------------------------------------------------

if (!stristr($HTTP_SERVER_VARS['HTTP_USER_AGENT'],"MSIE")) {

// ----------------------------------------------------------------------------
print <<<EOF
      <table width="100%" border=0 cellpadding=3 cellspacing=0>
	 <tr>
	    <td bgcolor="#808080">
	    </td>
	 </tr>
      </table>
   </font>
   <font size=1>
      <br>
      <a href="http://sourceforge.net" target="resource window"><img border=0
	 src="images/sflogo.png"></a>
   </font>
</body>
</html>
EOF;
// ----------------------------------------------------------------------------

} else {

// ----------------------------------------------------------------------------
print <<<EOF
      <table width="100%" border=0 cellpadding=0 cellspacing=0>
	 <tr>
	    <td bgcolor="#808080">
	       <font size=1 color="#808080">-</font>
	    </td>
	 </tr>
      </table>
   </font>
   <font size=1>
      <br>
      <a href="http://sourceforge.net" target="resource window"><img border=0
         src="images/sflogo.png"></a>
   </font>
</body>
</html>
EOF;
// ----------------------------------------------------------------------------

}

/* ----------------------------------------------------------------------------

PHP Wine Inventory Management System (phpWIMS)
Copyright (C) 2002, 2003 R Sean Eidemiller

This program is free software; you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation; either version 2 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program; if not, write to the Free Software Foundation, Inc., 59 Temple
Place - Suite 330, Boston, MA  02111-1307, USA.

---------------------------------------------------------------------------- */

?>
