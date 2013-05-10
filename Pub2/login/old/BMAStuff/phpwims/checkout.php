<?php

// ----------------------------------------------------------------------------
// Name: checkout.php
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

// Generate system timestamp

$time = trim(`/bin/date`);

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
	       <b><u>Bottle Checkout</u></b>
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

// Generate checkout query

if ($outquan <= $origquan) {
   $query = "UPDATE bottle SET quantity=quantity-$outquan WHERE id=$origid";
}

// Query the server

if ($outquan > 0) {
   $result = @mysql_query($query);
}

// Print results of query

if ($result) {
   $newquery = "SELECT * FROM bottle WHERE id = $origid";
   $newresult = @mysql_query($newquery);

   while ($row = @mysql_fetch_row($newresult)) {

// ----------------------------------------------------------------------------
print <<<EOF
   <font face="arial,helvetica" size=2>
      Your checkout request has been completed successfully and the server
	 returned the following results...
   </font>
   <font size=1><br><br></font>
   <table class="custom" border=0 cellpadding=2 cellspacing=2>
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
      <tr bgcolor="#eeeeee">
         <td><font face="arial,helvetica" size=2>$row[2]</font></td>
         <td><font face="arial,helvetica" size=2>$row[3]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$row[4]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$row[5]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$row[14]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$row[13]&nbsp;</font></td>
         <td><a href="details.php?id=$row[0]"><font face="arial,helvetica"
	    size=2>View</font></a></td>
      </tr>
   </table>
EOF;
// ----------------------------------------------------------------------------

      $day = trim(`/bin/date +%A`);
      $month = trim(`/bin/date +%B`);
      $year = trim(`/bin/date +%Y`);
      
      $logupdate = "INSERT INTO translog VALUES (NULL, $row[0], '$time', ";
      $logupdate .= "'Checkout', '$day', '$month', $year, ";
      $logupdate .= "'$WIMS_UNAME')";

      $logresult = @mysql_query($logupdate);

      if (!$logresult) {
	 logError();
      }
   }

} else {
   serverError();
}

if ($outquan > $origquan) {
   print "<font face='arial,helvetica' size=2 color='#ff0000'>";
   print "ERROR: How can you check out more bottles than what you <i>own</i>";
   print ", silly?<br></font>\n";
} else if ($outquan == 0) {
   print "<font face='arial,helvetica' size=2 color='#ff0000'>";
   print "ERROR: Why would you want to checkout 0 bottles, silly?";
   print "<br></font>\n";
} else if ($outquan < 0) {
   print "<font face='arial,helvetica' size=2 color='#ff0000'>";
   print "ERROR: How can you check out a <i>negative</i> number of bottles, ";
   print "silly?<br></font>\n";
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
