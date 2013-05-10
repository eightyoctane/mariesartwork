<?php

// ----------------------------------------------------------------------------
// Name: navigator.php
// Author: R Sean Eidemiller (mojo@halcyon.cc)
// Last Modified: January 25, 2003
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
<script language="JavaScript">

function valSelection(entry, label)
{
   if (entry.value == "========================================") {
      alert('Dude, you have to choose a VALID '+label+'...');
      entry.focus();
      return false;
   }

   return true;
}

function validateForm(form)
{
   if (!valSelection(form.producer, "producer")) {
      return false;
   }

   if (!valSelection(form.appellation, "appellation")) {
      return false;
   }

   if (!valSelection(form.varietal, "varietal")) {
      return false;
   }

   if (!valSelection(form.region, "region")) {
      return false;
   }

   return true;
}

</script>
</head>
<body bgcolor="#ffffff">
   <table border=0 cellpadding=3 cellspacing=0
      width="100%">
      <tr>
	 <td bgcolor="#808080">
	    <font face="arial,helvetica" size=2 color="#ffffff">
	       <b><u>Cellar Navigator</u></b>
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
	       Please enter search criteria and your preferred sorting
		  method using the form provided below...&nbsp;
	    </font>
	 </td>
      </tr>
   </table>
   <br>
   <form method="post" action="view.php"
      onsubmit="return validateForm(this)">
      <font face="arial,helvetica" size=2>
	 View:&nbsp;
	 <select name="view" size="1">
	    <option selected>Entire Inventory
	    <option>All Reds
	    <option>All Whites
	    <option>All Others
	 </select>&nbsp;&nbsp;&nbsp;
	 From Country:&nbsp;
	 <select name="country" size="1">
	    <option selected>Any
	    <option>Argentina
	    <option>Australia
	    <option>Austria
	    <option>Chile
	    <option>France
	    <option>Germany
	    <option>Greece
	    <option>Hungary
	    <option>Italy
	    <option>New Zealand
	    <option>Portugal
	    <option>South Africa
	    <option>Spain
	    <option>Switzerland
	    <option>United States
	 </select>
	 <font size=1><br><br></font>
	 From Producer:&nbsp;
         <select name="producer" size="1">
EOF;
// ----------------------------------------------------------------------------

$query = "SELECT DISTINCT producer FROM bottle ORDER BY producer";
$result = @mysql_query($query);

if ($result) {
   print "<option selected>Any\n";
   print "<option>========================================\n";

   while ($row = @mysql_fetch_row($result)) {
      print "<option>$row[0]\n";
   }
} else {
   print "<option selected>ERROR\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
         </select>
	 <font size=1><br><br></font>
	 From Appellation:&nbsp;
         <select name="appellation" size="1">
EOF;
// ----------------------------------------------------------------------------

$query = "SELECT DISTINCT appellation FROM bottle ORDER BY appellation";
$result = @mysql_query($query);

if ($result) {
   print "<option selected>Any\n";
   print "<option>========================================\n";

   while ($row = @mysql_fetch_row($result)) {
      print "<option>$row[0]\n";
   }
} else {
   print "<option selected>ERROR\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
         </select>
         <font size=1><br><br></font>
	 Of Varietal:&nbsp;
	 <select name="varietal" size="1">
EOF;
// ----------------------------------------------------------------------------

$query = "SELECT DISTINCT varietal FROM bottle ORDER BY varietal";
$result = @mysql_query($query);

if ($result) {
   print "<option selected>Any\n";
   print "<option>========================================\n";

   while ($row = @mysql_fetch_row($result)) {
      print "<option>$row[0]\n";
   }
} else {
   print "<option selected>ERROR\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
	 </select>
	 <font size=1><br><br></font>
	 From Region:&nbsp;
         <select name="region" size="1">
EOF;
// ----------------------------------------------------------------------------

$query = "SELECT DISTINCT region FROM bottle ORDER BY region";
$result = @mysql_query($query);

if ($result) {
   print "<option selected>Any\n";
   print "<option>========================================\n";

   while ($row = @mysql_fetch_row($result)) {
      print "<option>$row[0]\n";
   }
} else {
   print "<option selected>ERROR\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
         </select>
         <font size=1><br><br></font>
	 Receiving a Score of:&nbsp;
	 <select name="score" size="1">
	    <option selected>Any Score
	    <option>No Score
	    <option>50+
	    <option>60+
	    <option>70+
	    <option>80+
	    <option>90+
	 </select>&nbsp;&nbsp;&nbsp;
	 From:&nbsp;
	 <select name="source" size=1">
	    <option selected>Any Source
	    <option>$WIMS_SOURCE1
	    <option>$WIMS_SOURCE2
	    <option>$WIMS_SOURCE3
	 </select>
	 <font size=1><br><br></font>
	 Of Vintage:&nbsp;
	 <select name="vintage" size="1">
EOF;
// ----------------------------------------------------------------------------

$query = "SELECT DISTINCT vintage FROM bottle WHERE vintage IS NOT NULL ";
$query .= "ORDER BY vintage";
$result = @mysql_query($query);

if ($result) {
   print "<option selected>Any\n";

   while ($row = @mysql_fetch_row($result)) {
      if ($row[0] == "1001") {
	 print "<option>NV\n";
      } else {
	 print "<option>$row[0]\n";
      }
   }
} else {
   print "<option selected>ERROR\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
	 </select>&nbsp;&nbsp;&nbsp;
	 Within Price Range:&nbsp;
	 <select name="price" size="1">
	    <option selected>Any
	    <option>$0 - $10
	    <option>$10 - $20
	    <option>$20 - $30
	    <option>$30 - $50
	    <option>$50 - $100
	    <option>$100+
	 </select>
	 <br><br>
	 <input type="radio" name="viewpref" value="instock">
	 View only bottles currently stored in the cellar <i>(default)</i>
	 <br>
	 <input type="radio" name="viewpref" value="all">
	 View all bottles ever recorded
	 <br><br>
	 Sort According To:&nbsp;
	 <select name="sort" size="1">
	    <option selected>Vintage
	    <option>Appellation
	    <option>Begin Consume
	    <option>Bottle Size
	    <option>Consume By
	    <option>Country
	    <option>Date Cellared
	    <option>Price
	    <option>Producer
	    <option>Quantity
	    <option>Region
	    <option>Store
	    <option>Type
	    <option>Varietal
	    <option>Vineyard
	    <option>$WIMS_SOURCE1 Score
	    <option>$WIMS_SOURCE2 Score
	    <option>$WIMS_SOURCE3 Score
	 </select>&nbsp;&nbsp;
	 <select name="updown" size="1">
	    <option selected>Ascending
	    <option>Descending
	 </select>&nbsp;
	 <br><br>
      </font>
      <table border=0 cellpadding=0 cellspacing=0>
	 <tr>
	    <td>
	       <input type="submit" value="View Results">
	       &nbsp;&nbsp;
	       <input type="reset" value="Reset Form">
	       &nbsp;&nbsp;&nbsp;
	       </form>
	    </td>
	    <td>
	       <form method="link" action="search.php">
		  <input type="submit" value="Search Notes">
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
