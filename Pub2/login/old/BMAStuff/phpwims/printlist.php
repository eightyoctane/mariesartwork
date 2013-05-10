<?php

// ----------------------------------------------------------------------------
// Name: printlist.php
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

// Query shopping list database

$query = "SELECT id FROM list WHERE user='$WIMS_UNAME'";
$result = @mysql_query($query);

if (($result) && (mysql_affected_rows($link))) {
   $num_rows = mysql_affected_rows($link);
}

// ----------------------------------------------------------------------------
print <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
   <title>phpWIMS Shopping List</title>
</head>
<body bgcolor="#ffffff">
   <font size=8><b>Shopping list for $WIMS_UNAME</b></font>
   <hr><br>
   There is a total of <b>$num_rows</b> item(s) currently in your list...
   <br><br><br>
EOF;
// ----------------------------------------------------------------------------

$query = "SELECT * FROM list WHERE user='$WIMS_UNAME' ORDER BY store, price";
$result = @mysql_query($query);

while ($row = @mysql_fetch_row($result)) {
   global $store;
   global $num_stores;

   if ($row[6] == "$0 - $10") {
      $total_cost += 8;
   } else if ($row[6] == "$10 - $20") {
      $total_cost += 15;
   } else if ($row[6] == "$20 - $30") {
      $total_cost += 25;
   } else if ($row[6] == "$30 - $50") {
      $total_cost += 40;
   } else if ($row[6] == "$50 - $100") {
      $total_cost += 75;
   } else if ($row[6] == "$100+") {
      $total_cost += 100;
   } 

   if ($GLOBALS["store"] != $row[5]) {
      print "</table>\n";
      $GLOBALS["store"] = $row[5];
      $GLOBALS["num_stores"]++;

      if ($GLOBALS["num_stores"] > 1) {
         print "<br><br>\n";
      }

// ----------------------------------------------------------------------------
print <<<EOF
   Items From Store: &nbsp;<b>$row[5]</b>
   <font size=1><br><br></font>
   <table border=0 cellpadding=2 cellspacing=2>
      <tr>
         <td><u>Vintage</u>&nbsp;&nbsp;</td>
         <td><u>Producer</u>&nbsp;&nbsp;</td>
         <td><u>Varietal</u>&nbsp;&nbsp;</td>
         <td><u>Appellation</u>&nbsp;&nbsp;</td>
         <td><u>Price</u>&nbsp;&nbsp;</td>
      </tr>
      <tr>
         <td>$row[1]</td>
         <td>$row[2]&nbsp;</td>
         <td>$row[3]&nbsp;</td>
         <td>$row[4]&nbsp;</td>
         <td>$row[6]&nbsp;</td>
      </tr>
EOF;
// ----------------------------------------------------------------------------

   } else {

// ----------------------------------------------------------------------------
print <<<EOF
      <tr>
         <td>$row[1]</td>
         <td>$row[2]&nbsp;</td>
         <td>$row[3]&nbsp;</td>
         <td>$row[4]&nbsp;</td>
         <td>$row[6]&nbsp;</td>
      </tr>
EOF;
// ----------------------------------------------------------------------------

      }
   }

// ----------------------------------------------------------------------------
print <<<EOF
   </table>
   <br><br>
   Total estimated cost: &nbsp;<b>$$total_cost</b>
</body>
</html>
EOF;
// ----------------------------------------------------------------------------

mysql_close($link);

/* ----------------------------------------------------------------------------

PHP Wine Inventory Management System (phpWIMS)
Copyright (C) 2002 R Sean Eidemiller

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
