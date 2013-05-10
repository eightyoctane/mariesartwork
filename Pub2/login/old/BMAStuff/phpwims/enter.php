<?php

// ----------------------------------------------------------------------------
// Name: enter.php
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

// Make sure user isn't a smartass

if (!is_uploaded_file($HTTP_POST_FILES['filename']['tmp_name'])) {
   if (($producer == "==================================") ||
      ($vineyard == "==================================") ||
      ($appellation == "==================================") ||
      ($varietal == "==================================") ||
      ($region == "==================================") ||
      ($store == "==================================")) {

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
               <b><u>Bottle Submission</u></b>
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
   <font face="arial,helvetica" size=2 color="#ff0000">
      ERROR: Don't be a smart ass. Try entering a 'valid' name next time...
      <font size=1><br></font>
      Click <a href="submit.php">here</a> to give it the old college try once
	 more.
   </font>
   <font size=1><br><br></font>
EOF;
// ----------------------------------------------------------------------------

      include("footer.php");
      exit();
   }
}

// Connect to the database

$link = @mysql_connect("$sql_hostname", "$sql_username", "$sql_password")
   or die("ERROR: Could not connect to server");

@mysql_select_db("$sql_dbname")
   or die("ERROR: Could not connect to database");

// ----------------------------------------------------------------------------
print <<<EOF
<html>
<head>
</head>
<body bgcolor="#ffffff">
   <table border=0 cellpadding=3 cellspacing=0 width="100%">
      <tr>
         <td bgcolor="#808080">
            <font face="arial,helvetica" size=2 color="#ffffff">
               <b><u>Bottle Submission</u></b>
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

// Import data from file (if applicable)

if (is_uploaded_file($HTTP_POST_FILES['filename']['tmp_name'])) {
   $contents = file($HTTP_POST_FILES['filename']['tmp_name']);

   $line = explode("<field>", $contents[0]);

   $type = $line[0];
   $vintage = $line[1];
   $producer = $line[2];
   $vineyard = $line[3];
   $varietal = $line[4];
   $region = $line[5];
   $country = $line[6];
   $datecellared = $line[7];
   $beginconsume = $line[8];
   $consumeby = $line[9];
   $store = $line[10];
   $price = $line[11];
   $quantity = $line[12];
   $appellation = $line[13];
   $size = $line[14];
   $description = $line[18];

   $description = str_replace('"', '\"', $description); 
   $description = str_replace("<nl><nl>", "\n\n", $description);
   $description = str_replace("<nl>", "\n", $description);

   if ($vintage == "NV") {
      $vintage = "1001";
   }

   $insert = "INSERT INTO bottle VALUES (NULL, \"$type\", $vintage, ";
   $insert .= "\"$producer\", \"$vineyard\", \"$varietal\", ";
   $insert .= "\"$region\", \"$country\", $datecellared, ";
   $insert .= "$beginconsume, $consumeby, \"$store\", \"$price\", ";
   $insert .= "$quantity, \"$appellation\", \"$size\", NULL, ";
   $insert .= "NULL, NULL)"; 

   $result = @mysql_query($insert);

   // Find new bottle id

   $idquery = "SELECT id FROM bottle WHERE type='$type' AND ";
   $idquery .= "vintage=$vintage AND producer=\"$producer\" AND ";
   $idquery .= "vineyard=\"$vineyard\" AND varietal=\"$varietal\" AND ";
   $idquery .= "region=\"$region\" AND country=\"$country\" AND ";
   $idquery .= "datecellared=$datecellared AND ";
   $idquery .= "beginconsume=$beginconsume AND consumeby=$consumeby ";
   $idquery .= "AND store=\"$store\" AND price=\"$price\" AND ";
   $idquery .= "quantity=$quantity AND appellation=\"$appellation\" ";
   $idquery .= "AND size=\"$size\"";

   $idresult = @mysql_query($idquery);

   while ($idrow = @mysql_fetch_row($idresult)) {
      $notesid = $idrow[0];
   }

   // Insert user notes

   $notesinsert = "INSERT INTO notes VALUES ($notesid, \"$description\")";
   $notesresult = @mysql_query($notesinsert);

} else {

   // Generate normal insert statement

   $insert = "INSERT INTO bottle VALUES (NULL, ";

   if (isset($type)) {
      if (($type == "New") && (isset($typenew))) {
	 $insert .= "'$typenew', ";
      } else if ($type != "New") {
	 $insert .= "'$type', ";
      }
   } else {
      $insert .= "NULL, ";
   }

   if (isset($vintage)) {
      if (($vintage == "NV") || ($vintage == "nv")) {
	 $insert .= "1001, ";
      } else {
	 $insert .= "$vintage, ";
      }
   } else {
      $insert .= "NULL, ";
   }

   if (isset($producer)) {
      if (($producer == "New") && (isset($producernew))) {
	 $insert .= "'$producernew', ";
      } else if ($producer != "New") {
	 $insert .= "'$producer', ";
      }
   } else {
      $insert .= "NULL, ";
   }

   if (isset($vineyard)) {
      if (($vineyard == "New") && (isset($vineyardnew))) {
	 $insert .= "'$vineyardnew', ";
      } else if ($vineyard != "New") {
	 $insert .= "'$vineyard', ";
      }
   } else {
      $insert .= "NULL, ";
   }

   if (isset($varietal)) {
      if (($varietal == "New") && (isset($varietalnew))) {
	 $insert .= "'$varietalnew', ";
      } else if ($varietal != "New") {
	 $insert .= "'$varietal', ";
      }
   } else {
      $insert .= "NULL, ";
   }

   if (isset($region)) {
      if (($region == "New") && (isset($regionnew))) {
	 $insert .= "'$regionnew', ";
      } else if ($region != "New") {
	 $insert .= "'$region', ";
      }
   } else {
      $insert .= "NULL, ";
   }

   $insert .= "'$country', ";

   if (isset($datecellared)) {
      $insert .= "$datecellared, ";
   } else {
      $insert .= "NULL, ";
   }

   if (isset($beginconsume)) {
      $insert .= "$beginconsume, ";
   } else {
      $insert .= "NULL, ";
   }

   if (isset($consumeby)) {
      $insert .= "$consumeby, ";
   } else {
      $insert .= "NULL, ";
   }

   if (isset($store)) {
      if (($store == "New") && (isset($storenew))) {
	 $insert .= "'$storenew', ";
      } else if ($store != "New") {
	 $insert .= "'$store', ";
      }
   } else {
      $insert .= "NULL, ";
   }

   $insert .= "'$price', ";

   if (isset($quantity)) {
      $insert .= "$quantity, ";
   } else {
      $insert .= "0, ";
   }

   if (isset($appellation)) {
      if (($appellation == "New") && (isset($appellationnew))) {
	 $insert .= "'$appellationnew', ";
      } else if ($appellation != "New") {
	 $insert .= "'$appellation', ";
      }
   } else {
      $insert .= "NULL, ";
   }

   $insert .= "'$size', NULL, NULL, NULL)";

   // Submit insert request

   $result = @mysql_query($insert);
}

// View results (if any)

if ($result) {

// ----------------------------------------------------------------------------
print <<<EOF
   <font face="arial,helvetica" size=2>
      Your submission request has been completed successfully and the server
	 returned the following results...
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

   if ($idresult) {
      $query = "SELECT * FROM bottle WHERE id=$notesid";
   } else {
      $newid = mysql_insert_id($link);
      $query = "SELECT * FROM bottle WHERE id=$newid";
   }

   $newresult = @mysql_query($query);

   while ($row = @mysql_fetch_row($newresult)) {
      if ($row[2] == "1001") {
	 $row[2] = "NV";
      }

// ----------------------------------------------------------------------------
print <<<EOF
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
EOF;
// ----------------------------------------------------------------------------

      $insertnotes = "INSERT INTO notes VALUES ($row[0], '')";
      @mysql_query($insertnotes);

      $day = trim(`/bin/date +%A`);
      $month = trim(`/bin/date +%B`);
      $year = trim(`/bin/date +%Y`);
      $time = trim(`/bin/date`);

      $logupdate = "INSERT INTO translog VALUES (NULL, $row[0], '$time', ";
      $logupdate .= "'Submission', '$day', '$month', $year, '$WIMS_UNAME')";
      $logresult = mysql_query($logupdate);

      if (!$logresult) {
	 logError();
      }
   }

   if ($logresult) {
      print "</table>\n";
   }

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
