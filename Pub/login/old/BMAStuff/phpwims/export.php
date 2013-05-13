<?php

// ----------------------------------------------------------------------------
// Name: export.php
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
               <b><u>Bottle Description Export</u></b>
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
	       Right-click on the file listed below to download the bottle
		  description to your computer...
	    </font>
         </td>
      </tr>
   </table>
   <br>
EOF;
// ----------------------------------------------------------------------------

// Create export file

$filename = "./export/bottle_desc.id$id";
$fp = fopen($filename, "w");

// Query database for bottle description

$query = "SELECT * FROM bottle WHERE id=$id";
$getnotes = "SELECT usernotes FROM notes WHERE id=$id";

$result = @mysql_query($query);
$notesresult = @mysql_query($getnotes);

// Make sure the operation was successful

if (!$result) {
   serverError();
} else {

   while ($row = @mysql_fetch_row($result)) {
      $type = $row[1];
      $vintage = $row[2];  
      $producer = $row[3];
      $vineyard = $row[4];
      $varietel = $row[5];
      $region = $row[6];
      $country = $row[7];
      $datecellared = $row[8];
      $beginconsume = $row[9];
      $consumeby = $row[10];
      $store = $row[11];
      $price = $row[12];
      $quantity = 0;
      $appellation = $row[14];
      $size = $row[15];
      $score1 = "NULL";
      $score2 = "NULL";
      $score3 = "NULL";
   }

   while ($row = @mysql_fetch_row($notesresult)) {
      $desc = $row[0];
   }

   $desc = ereg_replace("(\r\n|\n|\r)", "<nl>", $desc);

   // Write data to file

   fwrite($fp, $type);
   fwrite($fp, "<field>");
   fwrite($fp, $vintage);
   fwrite($fp, "<field>");
   fwrite($fp, $producer);
   fwrite($fp, "<field>");
   fwrite($fp, $vineyard);
   fwrite($fp, "<field>");
   fwrite($fp, $varietel);
   fwrite($fp, "<field>");
   fwrite($fp, $region);
   fwrite($fp, "<field>");
   fwrite($fp, $country);
   fwrite($fp, "<field>");
   fwrite($fp, $datecellared);
   fwrite($fp, "<field>");
   fwrite($fp, $beginconsume);
   fwrite($fp, "<field>");
   fwrite($fp, $consumeby);
   fwrite($fp, "<field>");
   fwrite($fp, $store);
   fwrite($fp, "<field>");
   fwrite($fp, $price);
   fwrite($fp, "<field>");
   fwrite($fp, $quantity);
   fwrite($fp, "<field>");
   fwrite($fp, $appellation);
   fwrite($fp, "<field>");
   fwrite($fp, $size);
   fwrite($fp, "<field>");
   fwrite($fp, $score1);
   fwrite($fp, "<field>");
   fwrite($fp, $score2);
   fwrite($fp, "<field>");
   fwrite($fp, $score3);
   fwrite($fp, "<field>");
   fwrite($fp, $desc);
   fwrite($fp, "<field>");

   // Close the description file

   fclose($fp);

   // Provide a link for download

   print "<a href='export/bottle_desc.id$id'><font size=2\n";
   print "face='arial,helvetica'>bottle_desc.id$id</font></a>\n";
   print "<br><br>\n";
}

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
