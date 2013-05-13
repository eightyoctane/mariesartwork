<?php

// ----------------------------------------------------------------------------
// Name: list.php
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
<script language="JavaScript">

function confirmation()
{
   return confirm("Are you SURE you want to remove this entry from the list?");
}

</script>
</head>
<body bgcolor="#ffffff">
   <table border=0 cellpadding=3 cellspacing=0 width="100%">
      <tr>
         <td bgcolor="#808080">
            <font face="arial,helvetica" size=2 color="#ffffff">
	       <b><u>Shopping List</u></b>
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

$total_cost = 0;
$num_stores = 0;
$store = "nothing yet";

$query = "SELECT * FROM list WHERE user='$WIMS_UNAME' ORDER BY store, price";

// Query the database

$result = @mysql_query($query);

// Print results of query

if (($result) && (mysql_affected_rows($link))) {
   $num_rows = mysql_affected_rows($link);

// ----------------------------------------------------------------------------
print <<<EOF
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
	       Your query was successful and there is/are currently
		  <b>$num_rows</b> item(s) in your shopping list...
	    </font>
         </td>
      </tr>
   </table>
   <font size=1><br></font>
EOF;
// ----------------------------------------------------------------------------

   while ($row = @mysql_fetch_row($result)) {
      global $store;
      global $num_stores;

      if ($row[1] == "1001") {
	 $row[1] = "NV";
      }

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
	    print "<br>\n";
	 }

// ----------------------------------------------------------------------------
print <<<EOF
   <font face="arial,helvetica" size=2>
      Items From Store: <u>$row[5]</u>
   </font>
   <font size=1><br><br></font>
   <table border=0 cellpadding=2 cellspacing=2>
      <tr bgcolor="#808080">
	 <td><font face="arial,helvetica" size=2
	    color="#ffffff">Vintage&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Producer&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Varietal&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Appellation&nbsp;&nbsp;</font></td>
	 <td><font face="arial,helvetica" size=2
	    color="#ffffff">Price&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Details&nbsp;&nbsp;</font></td>
	 <td><font face="arial,helvetica" size=2
	    color="#ffffff">Remove&nbsp;&nbsp;</font></td>
      </tr>
      <tr bgcolor="#eeeeee">
	 <td><font face="arial,helvetica" size=2>$row[1]</font></td>
         <td><font face="arial,helvetica" size=2>$row[2]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$row[3]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$row[4]&nbsp;</font></td>
	 <td><font face="arial,helvetica" size=2>$row[6]&nbsp;</font></td>
         <td><a href="details.php?id=$row[0]"><font face="arial,helvetica"
	    size=2>View</font></a></td>
	 <td><font face="arial,helvetica" size=2>Click</font>
	    <a href="listrem.php?id=$row[0]"
	       onclick="return confirmation()"><font face="arial,helvetica" 
	       size=2>Here</font></a>&nbsp;</td>
      </tr>
EOF;
// ----------------------------------------------------------------------------

      } else {

// ----------------------------------------------------------------------------
print <<<EOF
      <tr bgcolor="#eeeeee">
	 <td><font face="arial,helvetica" size=2>$row[1]</font></td>
         <td><font face="arial,helvetica" size=2>$row[2]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$row[3]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$row[4]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$row[6]&nbsp;</font></td>
         <td><a href="details.php?id=$row[0]"><font face="arial,helvetica"
            size=2>View</font></a></td>
         <td><font face="arial,helvetica" size=2>Click</font>
	    <a href="listrem.php?id=$row[0]"
	       onclick="return confirmation()"><font face="arial,helvetica"
	       size=2>Here</font></a>&nbsp;</td>
      </tr>
EOF;
// ----------------------------------------------------------------------------
      }
   }

// ----------------------------------------------------------------------------
print <<<EOF
   </table>
   <br>
   <table border=0 cellpadding=0 cellspacing=0>
      <tr>
	 <td>
	    <form method="link" action="printlist.php"
	       target="resource window">
	       <input type="submit" value="Printable Shopping List">
	    </form>
	 </td>
	 <td>
	    &nbsp;&nbsp;&nbsp;&nbsp;
	 </td>
	 <td>
            <form method="link" action="listclear.php">
               <input type="submit" value="Clear Shopping List">
            </form>
         </td>
      </tr>
   </table>
EOF;
// ----------------------------------------------------------------------------

// Print an extra line for non-IE browsers

if (!stristr($HTTP_SERVER_VARS['HTTP_USER_AGENT'],"MSIE")) {
   print "<br>\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
	       Approximate total price of all bottles in your shopping list:
		  <b>&#36;$total_cost</b>
	    </font>
         </td>
      </tr>
   </table>
EOF;
// ----------------------------------------------------------------------------

} else if (($result) && (!mysql_affected_rows($link))) {

// ----------------------------------------------------------------------------
print <<<EOF
   <font face="arial,helvetica" size=2 color="#ff0000">
      Your shopping list is currently empty.
   </font>
   <font size=1><br></font>
EOF;
// ----------------------------------------------------------------------------

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
