<?php

// ----------------------------------------------------------------------------
// Name: logview.php
// Author: R Sean Eidemiller (mojo@halcyon.cc)
// Last Modified: January 24, 2003
// ----------------------------------------------------------------------------

include("include/sqlinfo.php");
include("include/error.php");

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

// Modify HTML form data

if ($view == "Submissions") {
   $view = "Submission";
} elseif ($view == "Updates") {
   $view = "Update";
} elseif ($view == "Checkouts") {
   $view = "Checkout";
}

// Generate query statement

$query = "SELECT * FROM translog";

if (($view != "Transactions") || ($selmonth != "Any") ||
   ($selyear != "Any") || ($seluser != "Any")) {

   $query .= " WHERE ";
   $update = 0;

   if ($view != "Transactions") {
      $query .= "type='$view'";
      $update++;
   }

   if ($selmonth != "Any") {
      if ($update > 0) {
	 $query .= " AND month='$selmonth'";
      } else {
	 $query .= "month='$selmonth'";
	 $update++;
      }
   }

   if ($selyear != "Any") {
      if ($update > 0) {
	 $query .= " AND year='$selyear'";
      } else {
	 $query .= "year='$selyear'";
	 $update++;
      }
   }

   if ($seluser != "Any") {
      if ($update > 0) {
	 $query .= " AND user='$seluser'";
      } else {
	 $query .= "user='$seluser'";
      }
   }
}

$query .= " ORDER BY id DESC";

// Query the database

$result = @mysql_query($query);

if (($result) && (mysql_affected_rows($link))) {
   $numrows = @mysql_affected_rows($link);

// ----------------------------------------------------------------------------
print <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<body bgcolor="#ffffff">
   <table class="custom" border=0 cellpadding=3 cellspacing=0 width="100%">
      <tr>
         <td bgcolor="#808080">
            <font face="arial,helvetica" size=2 color="#ffffff">
               <b><u>Search Results</u></b>
         </td>
         <td bgcolor="#808080" align="right">
            <font face="arial,helvetica" size=2 color="#ffffff">
               PHP Wine Inventory Management System v0.4.2&nbsp;
            </font>
         </td>
      </tr>
   </table>
   <font size=1><br></font>
   <font face="arial,helvetica" size=2>
      Your query was successful and the server returned the following
	 <b>$numrows</b> entry(ies)...&nbsp;
   </font>
   <font size=1><br><br></font>
   <table class="custom" border=0 cellpadding=2 cellspacing=2>
      <tr bgcolor="#808080">
	 <td><font face="arial,helvetica" size=2
	    color="#ffffff">Transaction Type&nbsp;&nbsp;</font></td>
	 <td><font face="arial,helvetica" size=2
	    color="#ffffff">Username&nbsp;&nbsp;</font></td>
	 <td><font face="arial,helvetica" size=2
	    color="#ffffff">Timestamp&nbsp;&nbsp;</font></td>
	 <td><font face="arial,helvetica" size=2
	    color="#ffffff">Bottle ID&nbsp;&nbsp;</font></td>
	 <td><font face="arial,helvetica" size=2
	    color="#ffffff">Details&nbsp;&nbsp;</font></td>
      </tr>
EOF;
// ----------------------------------------------------------------------------

   $bonerwang = 0;

   while ($row = @mysql_fetch_row($result)) {

      if (($bonerwang % 2) == 0) {
	 $color = "aliceblue";
      } else {
	 $color = "#eeeeee";
      }

      $bonerwang++;

// ----------------------------------------------------------------------------
print <<<EOF
      <tr bgcolor="$color">
         <td><font face="arial,helvetica" size=2>$row[3]</font></td>
         <td><font face="arial,helvetica" size=2>$row[7]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$row[2]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$row[1]&nbsp;</font></td>
EOF;
// ----------------------------------------------------------------------------

      $existquery = "SELECT * FROM bottle WHERE id=$row[1]";
      $existresult = @mysql_query($existquery);

      if (mysql_affected_rows($link)) {
	 print "<td><a href='details.php?id=$row[1]'><font ";
	 print "face='arial,helvetica' size=2>View</font></a></td></tr>\n";
      } else {
	 print "<td><font face='arial,helvetica' ";
	 print "color='#ff0000' size=2>Deleted&nbsp;&nbsp;";
	 print "</font></td></tr>\n";
      }
   }
   print "</table>\n";
} elseif (($result) && ((mysql_affected_rows($link)) == 0)) {
   if ($view == "Transactions") {
      header("Location: /phpwims/log.php?error=1");      
   } elseif ($view == "Checkout") {
      header("Location: /phpwims/log.php?error=2");
   } elseif ($view == "Submission") {
      header("Location: /phpwims/log.php?error=3");
   } elseif ($view == "Update") {
      header("Location: /phpwims/log.php?error=4");
   }
   exit();
} else {
   serverError();
}

print "<font size=1><br></font>";

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
