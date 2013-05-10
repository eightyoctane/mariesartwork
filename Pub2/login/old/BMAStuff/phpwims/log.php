<?php

// ----------------------------------------------------------------------------
// Name: log.php
// Author: R Sean Eidemiller (mojo@halcyon.cc)
// Last Modified: January 24, 2003
// ----------------------------------------------------------------------------

include("include/sqlinfo.php");

session_start();

// Check to make sure current session is valid

if (!session_is_registered("WIMS_SESSION")) {
   header("Location: /phpwims/index.php?error=4");
   exit();
}

// Connect to the database

$link = @mysql_connect("$sql_hostname", "$sql_username", "$sql_password")
   or die("ERROR: Could not connect to server");

@mysql_select_db("$sql_dbname")
   or die("ERROR: Could not connect to database");

// ----------------------------------------------------------------------------
print <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
</head>
<body bgcolor="#ffffff">
   <table border=0 cellpadding=3 cellspacing=0 width="100%">
      <tr>
         <td bgcolor="#808080">
            <font face="arial,helvetica" size=2 color="#ffffff">
	       <b><u>Transaction Log</u></b>
            </font>
         </td>
         <td bgcolor="#808080" align="right">
            <font face="arial,helvetica" size=2 color="#ffffff">
               PHP Wine Inventory Management System v0.4.2&nbsp;
            </font>
         </td>
      </tr>
   </table>
   <font size=1><br></font>
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
	       You may customize the results that you would like to generate
		  using the form provided below...&nbsp;
	    </font>
         </td>
      </tr>
   </table>
   <br>
EOF;
// ----------------------------------------------------------------------------

if ($error) {
   if ($error == 1) {
      print "<font face='arial,helvetica' size=2 color='#ff0000'>";
      print "The server indicates that no transactions ";
      print "were recorded during the specified time interval.</font>\n";
   } elseif ($error == 2) {
      print "<font face='arial,helvetica' size=2 color='#ff0000'>";
      print "The server indicates that no checkouts ";
      print "were recorded during the specified time interval.</font>\n";
   } elseif ($error == 3) {
      print "<font face='arial,helvetica' size=2 color='#ff0000'>";
      print "The server indicates that no submissions ";
      print "were recorded during the specified time interval.</font>\n";
   } elseif ($error == 4) {
      print "<font face='arial,helvetica' size=2 color='#ff0000'>";
      print "The server indicates that no updates ";
      print "were recorded during the specified time interval.</font>\n";
   }
   print "<font size=2><br><br></font>";
}

// ----------------------------------------------------------------------------
print <<<EOF
   <form method="post" action="logview.php">
      <font face="arial,helvetica" size=2>
	 View all&nbsp;
	 <select name="view" size=1>
	    <option selected>Transactions
	    <option>Checkouts
	    <option>Submissions
	    <option>Updates
	 </select>&nbsp;&nbsp;
	 From month:&nbsp;
	 <select name="selmonth" size=1>
	    <option selected>Any
	    <option>January
	    <option>February
	    <option>March
	    <option>April
	    <option>May
	    <option>June
	    <option>July
	    <option>August
	    <option>September
	    <option>October
	    <option>November
	    <option>December
	 </select>&nbsp;&nbsp;
	 From year:&nbsp;
	 <select name="selyear" size=1>
EOF;
// ----------------------------------------------------------------------------

$yearquery = "SELECT DISTINCT year FROM translog ORDER BY year";
$yearresult = @mysql_query($yearquery);

if ($yearresult) {
   print "<option selected>Any\n";

   while ($row = @mysql_fetch_row($yearresult)) {
      print "<option>$row[0]\n";
   }
} else {
   print "<option selected>ERROR\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
	 </select>&nbsp;&nbsp;
	 By user:&nbsp;
	 <select name="seluser" size=1>
EOF;
// ----------------------------------------------------------------------------

$userquery = "SELECT DISTINCT user FROM translog WHERE user!='' ORDER BY user";
$userresult = @mysql_query($userquery);

if ($yearresult) {
   print "<option selected>Any\n";

   while ($row = @mysql_fetch_row($userresult)) {
      print "<option>$row[0]\n";
   }
} else {
   print "<option selected>ERROR\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
	 </select>
	 <br><br>
	 <input type="submit" value="View Results">
	 &nbsp;&nbsp;
	 <input type="reset" value="Restore Defaults">
      </font>
   </form>
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
	       <b>Note:</b> Please be patient as it may take several seconds
		  for the server to process your query.&nbsp;
	    </font>
         </td>
      </tr>
   </table>
   <font size=1><br></font>
EOF;
// ----------------------------------------------------------------------------

mysql_close($link);
include("include/footer.php");

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
