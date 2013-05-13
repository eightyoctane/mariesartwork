<?php

// ----------------------------------------------------------------------------
// Name: submit.php
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

// Generate current year

$curyear = trim(`/bin/date +%Y`);

// ----------------------------------------------------------------------------
print <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript">

function valSelection(entry, label)
{
   if (entry.value == "==================================") {
      alert('Dude, you have to choose a valid '+label+'...');
      entry.focus();
      return false;
   }

   return true;
}

function valDate(year, label)
{
   digits = "0123456789";

   for (i = 0; i < 4; i++) {
      if (digits.indexOf(year.value.charAt(i)) < 0) {
	 alert('"'+label+'" must contain numeric digits only...');
	 year.focus();
	 return false;
      }
   }

   if (year.value.length != 4) {
      alert('"'+label+'" must contain 4 numeric digits...');
      year.focus();
      return false;
   }

   return true;
}

function valQuantity(quan, label)
{
   digits = "0123456789";
   var len = quan.value.length;

   if (len == 0) {
      alert('"'+label+'" must not be left empty...');
      quan.focus();
      return false;
   } else if (quan.value <= 0) {
      alert('"'+label+'" must be a value greater than 0...');
      quan.focus();
      return false;
   }

   for (i = 0; i < len; i++) {
      if (digits.indexOf(quan.value.charAt(i)) < 0) {
	 alert('"'+label+'" must contain numeric digits only...');
	 quan.focus();
	 return false;
      }
   }

   return true;
}

function validateForm(form)
{
   if (!form.filename.value) {

      if (!valDate(form.datecellared, "Year Cellared")) {
	 return false;
      }

      if (!valDate(form.beginconsume, "Begin Consumption")) {
	 return false;
      }

      if (!valDate(form.consumeby, "Consume Before")) {
	 return false;
      }

      if (!valQuantity(form.quantity, "Quantity")) {
	 return false;
      }

      if (!valSelection(form.producer, "Producer")) {
	 return false;
      }

      if (!valSelection(form.appellation, "Appellation")) {
	 return false;
      }

      if (!valSelection(form.varietal, "Varietal")) {
	 return false;
      }

      if (!valSelection(form.vineyard, "Vineyard")) {
	 return false;
      }

      if (!valSelection(form.region, "Region")) {
	 return false;
      }

      if (!valSelection(form.store, "Store")) {
	 return false;
      }
   }

   return true;
}

</script>
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
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
	       Please enter the bottle description using the form provided
		  below and [try to] be as concise as possible...&nbsp;
	    </font>
         </td>
      </tr>
   </table>
   <br>
   <form method="post" action="enter.php"
      onsubmit="return validateForm(this)" enctype="multipart/form-data">
      <font face="arial,helvetica" size=2>
	 Type:&nbsp;
	 <select name="type" size="1">
EOF;
// ----------------------------------------------------------------------------

$query = "SELECT DISTINCT type FROM bottle ORDER BY type";
$result = @mysql_query($query);

if ($result) {
   print "<option selected>$WIMS_DEFAULT_TYPE\n";

   while ($row = @mysql_fetch_row($result)) {
      if ($row[0] != $WIMS_DEFAULT_TYPE) {
	 print "<option>$row[0]\n";
      }
   }

   if ($WIMS_DEFAULT_TYPE != "New") {
      print "<option>New\n";
   }
} else {
   print "<option selected>ERROR\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
	 </select>&nbsp;&nbsp;
	 <input type="text" name="typenew" size="11">
	 &nbsp;&nbsp;
	 From Country:&nbsp;
	 <select name="country" size="1">
	    <option selected>$WIMS_DEFAULT_COUNTRY
EOF;
// ----------------------------------------------------------------------------

if ($WIMS_DEFAULT_COUNTRY != "Argentina") {
   print "<option>Argentina\n";
}

if ($WIMS_DEFAULT_COUNTRY != "Australia") {
   print "<option>Australia\n";
}

if ($WIMS_DEFAULT_COUNTRY != "Austria") {
   print "<option>Austria\n";
}

if ($WIMS_DEFAULT_COUNTRY != "Chile") {
   print "<option>Chile\n";
}

if ($WIMS_DEFAULT_COUNTRY != "France") {
   print "<option>France\n";
}

if ($WIMS_DEFAULT_COUNTRY != "Germany") {
   print "<option>Germany\n";
}

if ($WIMS_DEFAULT_COUNTRY != "Greece") {
   print "<option>Greece\n";
}

if ($WIMS_DEFAULT_COUNTRY != "Hungary") {
   print "<option>Hungary\n";
}

if ($WIMS_DEFAULT_COUNTRY != "Italy") {
   print "<option>Italy\n";
}

if ($WIMS_DEFAULT_COUNTRY != "Portugal") {
   print "<option>Portugal\n";
}

if ($WIMS_DEFAULT_COUNTRY != "New Zealand") {
   print "<option>New Zealand\n";
}

if ($WIMS_DEFAULT_COUNTRY != "South Africa") {
   print "<option>South Africa\n";
}

if ($WIMS_DEFAULT_COUNTRY != "Spain") {
   print "<option>Spain\n";
}

if ($WIMS_DEFAULT_COUNTRY != "Switzerland") {
   print "<option>Switzerland\n";
}

if ($WIMS_DEFAULT_COUNTRY != "United States") {
   print "<option>United States\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
	 </select>&nbsp&nbsp;&nbsp;
	 Vintage:&nbsp;
	 <input type="text" name="vintage" size="8">
	 <font size=1><br><br></font>
	 Producer:&nbsp;
	 <select name="producer" size="1">
EOF;
// ----------------------------------------------------------------------------

$query = "SELECT DISTINCT producer FROM bottle ORDER BY producer";
$result = @mysql_query($query);

if ($result) {
   print "<option selected>New\n";
   print "<option>==================================\n";
   print "<option>Unknown\n";

   while ($row = @mysql_fetch_row($result)) {
      if ($row[0] != "Unknown") {
         print "<option>$row[0]\n";
      }
   }
} else {
   print "<option selected>ERROR\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
	 </select>&nbsp;&nbsp;&nbsp;
	 <input type="text" name="producernew" size="32">
	 <font size=1><br><br></font>
	 Vineyard:&nbsp;
	 <select name="vineyard" size="1">
EOF;
// ----------------------------------------------------------------------------

$query = "SELECT DISTINCT vineyard FROM bottle ORDER BY vineyard";
$result = @mysql_query($query);

if ($result) {
   print "<option selected>New\n";
   print "<option>==================================\n";
   print "<option>Unknown\n";

   while ($row = @mysql_fetch_row($result)) {
      if ($row[0] != "Unknown") {
         print "<option>$row[0]\n";
      }
   }
} else {
   print "<option selected>ERROR\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
	 </select>&nbsp;&nbsp;&nbsp;
	 <input type="text" name="vineyardnew" size="32">
	 <font size=1><br><br></font>
	 Appellation:&nbsp;
	 <select name="appellation" size="1">
EOF;
// ----------------------------------------------------------------------------

$query = "SELECT DISTINCT appellation FROM bottle ORDER BY appellation";
$result = @mysql_query($query);

if ($result) {
   print "<option selected>New\n";
   print "<option>==================================\n";
   print "<option>Unknown\n";

   while ($row = @mysql_fetch_row($result)) {
      if ($row[0] != "Unknown") {
         print "<option>$row[0]\n";
      }
   }
} else {
   print "<option selected>ERROR\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
	 </select>&nbsp;&nbsp;&nbsp;
	 <input type="text" name="appellationnew" size="32">
	 <font size=1><br><br></font>
	 Varietal:&nbsp
	 <select name="varietal" size="1">
EOF;
// ----------------------------------------------------------------------------

$query = "SELECT DISTINCT varietal FROM bottle ORDER BY varietal";
$result = @mysql_query($query);

if ($result) {
   print "<option selected>New\n";
   print "<option>==================================\n";
   print "<option>Unknown\n";

   while ($row = @mysql_fetch_row($result)) {
      if ($row[0] != "Unknown") {
         print "<option>$row[0]\n";
      }
   }
} else {
   print "<option selected>ERROR\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
	 </select>&nbsp;&nbsp;&nbsp;
	 <input type="text" name="varietalnew" size="32">
	 <font size=1><br><br></font>
	 Region:&nbsp;
	 <select name="region" size="1">
EOF;
// ----------------------------------------------------------------------------

$query = "SELECT DISTINCT region FROM bottle ORDER BY region";
$result = @mysql_query($query);

if ($result) {
   print "<option selected>New\n";
   print "<option>==================================\n";
   print "<option>Unknown\n";

   while ($row = @mysql_fetch_row($result)) {
      if ($row[0] != "Unknown") {
         print "<option>$row[0]\n";
      }
   }
} else {
   print "<option selected>ERROR\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
	 </select>&nbsp;&nbsp;&nbsp;
	 <input type="text" name="regionnew" size="32">
	 <font size=1><br><br></font>
	 Store:&nbsp;
	 <select name="store" size="1">
EOF;
// ----------------------------------------------------------------------------

$query = "SELECT DISTINCT store FROM bottle ORDER BY store";
$result = @mysql_query($query);

if ($result) {
   print "<option selected>New\n";
   print "<option>==================================\n";
   print "<option>Unknown\n";

   while ($row = @mysql_fetch_row($result)) {
      if ($row[0] != "Unknown") {
         print "<option>$row[0]\n";
      }
   }
} else {
   print "<option selected>ERROR\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
	 </select>&nbsp;&nbsp;&nbsp;
	 <input type="text" name="storenew" size="32">
	 <font size=1><br><br></font>
	 Within Price Range:&nbsp;
	 <select name="price" size="1">
	    <option selected>$0 - $10
	    <option>$10 - $20
	    <option>$20 - $30
	    <option>$30 - $50
	    <option>$50 - $100
	    <option>$100+
	 </select>&nbsp;&nbsp;&nbsp;
	 Bottle Size:&nbsp;
	 <select name="size" size=1>
	    <option selected>Standard
	    <option>Demi
	    <option>Magnum
	 </select>&nbsp;&nbsp;&nbsp;
	 Quantity:&nbsp;
	 <input type="text" name="quantity" size="6">
	 <font size=1><br><br></font>
	 Year Cellared:&nbsp; 
	 <input type="text" name="datecellared" value="$curyear" size=8>
	 &nbsp;&nbsp;
	 Begin Consumption:&nbsp;
	 <input type="text" name="beginconsume" size="8">
	 &nbsp;&nbsp;
	 Consume Before:&nbsp;
	 <input type="text" name="consumeby" size="8">
	 <br><br>
	 Import File:
	 <input type="file" size="27" name="filename">
	 &nbsp;&nbsp;
	 <input type="submit" value="Submit Bottle">
	 &nbsp;&nbsp;
	 <input type="reset" value="Reset Form">
      </font>
   </form>
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
	 <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
	       <b>Note:</b> You <u>must</u> be running PHP version 4.0.x or
		  greater in order to import a bottle description from
		  file.&nbsp;
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
