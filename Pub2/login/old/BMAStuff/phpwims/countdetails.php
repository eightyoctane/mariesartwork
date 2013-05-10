<?php

// ----------------------------------------------------------------------------
// Name: countdetails.php
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
               <b><u>Search Results</u></b>
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
EOF;
// ----------------------------------------------------------------------------

// Generate query statement

if ($vintage) {
   $query = "SELECT * FROM bottle WHERE vintage=$vintage";
} elseif ($type) {
   $query = "SELECT * FROM bottle WHERE type=\"$type\"";
} elseif ($producer) {
   $query = "SELECT * FROM bottle WHERE producer=\"$producer\"";
} elseif ($vineyard) {
   $query = "SELECT * FROM bottle WHERE vineyard=\"$vineyard\"";
} elseif ($varietal) {
   $query = "SELECT * FROM bottle WHERE varietal=\"$varietal\"";
} elseif ($region) {
   $query = "SELECT * FROM bottle WHERE region=\"$region\"";
} elseif ($country) {
   $query = "SELECT * FROM bottle WHERE country=\"$country\"";
} elseif ($store) {
   $query = "SELECT * FROM bottle WHERE store=\"$store\"";
} elseif ($price) {
   $query = "SELECT * FROM bottle WHERE price=\"$price\"";
} elseif ($appellation) {
   $query = "SELECT * FROM bottle WHERE appellation=\"$appellation\"";
} elseif ($size) {
   $query = "SELECT * FROM bottle WHERE size=\"$size\"";
}

$query .= " AND quantity>0";
$result = @mysql_query($query);

if (($result) && (mysql_affected_rows($link))) {

   $numrows = mysql_affected_rows($link);

// ----------------------------------------------------------------------------
print <<<EOF
   <font face="arial,helvetica" size=2>
      Your query was successful and the server returned the following
	 <b>$numrows</b> matching entry(ies)...&nbsp;
   </font>
   <font size=1><br><br></font>
   <table border=0 cellpadding=2 cellspacing=2>
      <tr bgcolor="#808080">
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Vintage&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Producer&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Vineyard&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Varietal&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Appellation&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Quan&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Details&nbsp;&nbsp;</font></td>
      </tr>
EOF;
// ----------------------------------------------------------------------------

   $dogs_slobber = 0;

   while ($row = @mysql_fetch_row($result)) {

      if (($dogs_slobber % 2) == 0) {
	 $color = "aliceblue";
      } else {
	 $color = "#eeeeee";
      }

      $dogs_slobber++;

// ----------------------------------------------------------------------------
print <<<EOF
      <tr bgcolor="$color">
         <td><font face="arial,helvetica" size=2>$row[2]</font></td>
         <td><font face="arial,helvetica" size=2>$row[3]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$row[4]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$row[5]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$row[14]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$row[13]&nbsp;</font></td>
         <td><a href="details.php?id=$row[0]"><font face="arial,helvetica"
	    size=2>View</font></a></td>
      </tr>
EOF;
// ----------------------------------------------------------------------------

   }
   print "</table>\n";
} else {
   serverError();
}

print "<font size=1><br></font>\n";

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
