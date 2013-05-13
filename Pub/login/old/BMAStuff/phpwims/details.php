<?php

// ----------------------------------------------------------------------------
// Name: details.php
// Author: R Sean Eidemiller (mojo@halcyon.cc)
// Last Modified: January 25, 2003
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

// Browser-dependent formatting

if (!stristr($HTTP_SERVER_VARS['HTTP_USER_AGENT'],"MSIE")) {
   $area = 78;
} else {
   $area = 75;
}

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


function confirmation()
{
   return confirm("Are you SURE you want to completely remove this entry?");
}

function validateForm(form)
{
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

   return true;
}

</script>
</head>
<body bgcolor="#ffffff">
   <table border=0 cellpadding=3 cellspacing=0 width="100%">
      <tr>
         <td bgcolor="#808080">
            <font face="arial,helvetica" size=2 color="#ffffff">
               <b><u>Bottle Description</u></b>
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

// Query database for bottle ID

$query = "SELECT * FROM bottle WHERE id=$id";
$result = @mysql_query($query);
$row = @mysql_fetch_row($result);

$notesquery = "SELECT * FROM notes WHERE id=$row[0]";
$notesresult = @mysql_query($notesquery);
$notesrow = @mysql_fetch_row($notesresult);

// Print results in table (if any)

if ($result) {

// ----------------------------------------------------------------------------
print <<<EOF
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
	       Please use the form provided below to make any necessary updates
		  or changes to the selected entry...&nbsp;
	    </font>
         </td>
      </tr>
   </table>
   <br>
   <form method="post" action="update.php"
      onsubmit="return validateForm(this)">
      <font face="arial,helvetica" size=2>
	 Type:&nbsp;
	 <select name="type" size="1">
EOF;
// ----------------------------------------------------------------------------

   $newquery = "SELECT DISTINCT type FROM bottle ORDER BY type";
   $newresult = @mysql_query($newquery);

   if ($newresult) {
      print "<option selected>$row[1]\n";

      while ($newrow = @mysql_fetch_row($newresult)) {
	 if ($newrow[0] != $row[1]) {
	    print "<option>$newrow[0]\n";
	 }
      }

      print "<option>New\n";

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
	    <option selected>$row[7]
EOF;
// ----------------------------------------------------------------------------

   if ($row[7] != "Argentina") {
      print "<option>Argentina\n";
   }

   if ($row[7] != "Australia") {
      print "<option>Australia\n";
   }

   if ($row[7] != "Austria") {
      print "<option>Austria\n";
   }

   if ($row[7] != "Chile") {
      print "<option>Chile\n";
   }

   if ($row[7] != "France") {
      print "<option>France\n";
   }

   if ($row[7] != "Germany") {
      print "<option>Germany\n";
   }

   if ($row[7] != "Greece") {
      print "<option>Greece\n";
   }

   if ($row[7] != "Italy") {
      print "<option>Italy\n";
   }

   if ($row[7] != "Hungary") {
      print "<option>Hungary\n";
   }

   if ($row[7] != "Portugal") {
      print "<option>Portugal\n";
   }

   if ($row[7] != "New Zealand") {
      print "<option>New Zealand\n";
   }

   if ($row[7] != "South Africa") {
      print "<option>South Africa\n";
   }

   if ($row[7] != "Spain") {
      print "<option>Spain\n";
   }

   if ($row[7] != "Switzerland") {
      print "<option>Switzerland\n";
   }

   if ($row[7] != "United States") {
      print "<option>United States\n";
   }

   if ($row[2] == "1001") {
      $row[2] = "NV";
   }

// ----------------------------------------------------------------------------
print <<<EOF
	 </select>&nbsp&nbsp;&nbsp;
	 Vintage:&nbsp;
	 <input type="text" name="vintage" value="$row[2]" size="8">
	 <font size=1><br><br></font>
	 Producer:&nbsp;
	 <select name="producer" size="1">
EOF;
// ----------------------------------------------------------------------------

   $newquery = "SELECT DISTINCT producer FROM bottle ORDER BY producer";
   $newresult = @mysql_query($newquery);

   if ($newresult) {
      print "<option selected>$row[3]\n";
      print "<option>==================================\n";
      print "<option>New\n";

      while ($newrow = @mysql_fetch_row($newresult)) {
	 if ($newrow[0] != $row[3]) {
	    print "<option>$newrow[0]\n";
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

   $newquery = "SELECT DISTINCT vineyard FROM bottle ORDER BY vineyard";
   $newresult = @mysql_query($newquery);

   if ($newresult) {
      print "<option selected>$row[4]\n";
      print "<option>==================================\n";
      print "<option>New\n";
      print "<option>Unknown\n";

      while ($newrow = @mysql_fetch_row($newresult)) {
	 if (($newrow[0] != "Unknown") && ($newrow[0] != $row[4])) {
	    print "<option>$newrow[0]\n";
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

   $newquery = "SELECT DISTINCT appellation FROM bottle ORDER BY appellation";
   $newresult = @mysql_query($newquery);

   if ($newresult) {
      print "<option selected>$row[14]\n";
      print "<option>==================================\n";
      print "<option>New\n";
      print "<option>Unknown\n";

      while ($newrow = @mysql_fetch_row($newresult)) {
	 if (($newrow[0] != "Unknown") && ($newrow[0] != $row[14])) {
	    print "<option>$newrow[0]\n";
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

   $newquery = "SELECT DISTINCT varietal FROM bottle ORDER BY varietal";
   $newresult = @mysql_query($newquery);

   if ($newresult) {
      print "<option selected>$row[5]\n";
      print "<option>==================================\n";
      print "<option>New\n";

      while ($newrow = @mysql_fetch_row($newresult)) {
	 if ($newrow[0] != $row[5]) {
	    print "<option>$newrow[0]\n";
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

   $newquery = "SELECT DISTINCT region FROM bottle ORDER BY region";
   $newresult = @mysql_query($newquery);

   if ($newresult) {
      print "<option selected>$row[6]\n";
      print "<option>==================================\n";
      print "<option>New\n";
      print "<option>Unknown\n";

      while ($newrow = @mysql_fetch_row($newresult)) {
	 if (($newrow[0] != "Unknown") && ($newrow[0] != $row[6])) {
	    print "<option>$newrow[0]\n";
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

   $newquery = "SELECT DISTINCT store FROM bottle ORDER BY store";
   $newresult = @mysql_query($newquery);

   if ($newresult) {
      print "<option selected>$row[11]\n";
      print "<option>==================================\n";
      print "<option>New\n";
      print "<option>Unknown\n";

      while ($newrow = @mysql_fetch_row($newresult)) {
	 if (($newrow[0] != "Unknown") && ($newrow[0] != $row[11])) {
	    print "<option>$newrow[0]\n";
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
	    <option selected>$row[12]
EOF;
// ----------------------------------------------------------------------------

   if ($row[12] != "$0 - $10") {
      print "<option>$0 - $10\n";
   }

   if ($row[12] != "$10 - $20") {
      print "<option>$10 - $20\n";
   }

   if ($row[12] != "$20 - $30") {
      print "<option>$20 - $30\n";
   }

   if ($row[12] != "$30 - $50") {
      print "<option>$30 - $50\n";
   }

   if ($row[12] != "$50 - $100") {
      print "<option>$50 - $100\n";
   }

   if ($row[12] != "$100+") {
      print "<option>$100+\n";
   }

// ----------------------------------------------------------------------------
print <<<EOF
	 </select>&nbsp;&nbsp;&nbsp;
	 Bottle Size:&nbsp;
	 <select name="size" size=1>
	    <option selected>$row[15]
EOF;
// ----------------------------------------------------------------------------

   if ($row[15] != "Standard") {
      print "<option>Standard\n";
   }

   if ($row[15] != "Demi") {
      print "<option>Demi\n";
   }

   if ($row[15] != "Magnum") {
      print "<option>Magnum\n";
   }

// ----------------------------------------------------------------------------
print <<<EOF
	 </select>&nbsp;&nbsp;&nbsp;
	 Quantity:&nbsp;
	 <input type="text" name="quantity" value="$row[13]" size="5">
	 <font size=1><br><br></font>
	 Year Cellared:&nbsp;
	 <input type="text" name="datecellared" value="$row[8]" size=8>
	 &nbsp;&nbsp;
	 Begin Consumption:&nbsp;
	 <input type="text" name="beginconsume" value="$row[9]" size="8">
	 &nbsp;&nbsp;
	 Consume Before:&nbsp;
	 <input type="text" name="consumeby" value="$row[10]" size="8">
	 <br><br>
	 <textarea name="notes" rows=8 cols=$area>$notesrow[1]</textarea>
	 <br><br>
	 $WIMS_SOURCE1:&nbsp;
	 <input type="text" name="score1" value="$row[16]" size=5>/100
	 &nbsp;&nbsp;&nbsp;&nbsp;
	 $WIMS_SOURCE2:&nbsp;
	 <input type="text" name="score2" value="$row[17]" size=5>/100
	 &nbsp;&nbsp;&nbsp;&nbsp;
	 $WIMS_SOURCE3:&nbsp;
	 <input type="text" name="score3" value="$row[18]" size=5>/100
	 <br><br>
      </font>
      <table border=0 cellspacing=0 cellpadding=0>
	 <tr>
	    <td>
	       <input type="hidden" name="id" value=$row[0]>
	       <input type="submit" value="Submit Changes">
	       &nbsp;&nbsp;
	       <input type="reset" value=" Reset Form ">
	       &nbsp;&nbsp;&nbsp;
	    </form>
	 </td>
	 <td>
	    <form method="post" action="delete.php"
	       onclick="return confirmation()">
	       <input type="submit" value=" Delete Record ">
	       <input type="hidden" name="id" value=$row[0]>
	    </form>
	 </td>
	 <td>
	    <form method="post" action="checkout.php">
	       &nbsp;&nbsp;&nbsp;
	       <input type="submit" value=" Checkout ">
	       &nbsp;&nbsp;
	       <input type="text" name="outquan" value=1 size=4>
	       <input type="hidden" name="origquan" value=$row[13]>
	       <input type="hidden" name="origid" value=$row[0]>
	    </form>
	 </tr>
      </tr>
   </table>
EOF;
// ----------------------------------------------------------------------------

// Print an extra line for non-IE browsers

if (!stristr($HTTP_SERVER_VARS['HTTP_USER_AGENT'],"MSIE")) {
   print "<font size=1><br></font>\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
   <table border=0 cellspacing=0 cellpadding=0>
      <tr>
	 <td>
	    <form method="post" action="export.php">
	       <input type="hidden" name="id" value="$row[0]">
	       <input type="submit" value=" Export Description ">
	       &nbsp;&nbsp;&nbsp;
	    </form>
	 </td>
	 <td>
            <form method="post" action="printdesc.php"
	       target="resource window">
               <input type="hidden" name="id" value="$row[0]">
               <input type="submit" value=" Printable Description ">
               &nbsp;&nbsp;&nbsp;
            </form>
         </td>
	 <td>
	    <form method="post" action="listadd.php">
	       <input type="hidden" name="id" value="$row[0]">
	       <input type="submit" value="Add To Shopping List">
	       &nbsp;&nbsp;&nbsp;
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
	       For your convenience, you may also
		  <a href="export.php?id=$row[0]"><font face="arial,helvetica"
		     size=2>export</font></a>
		  this bottle description to a file on your computer.&nbsp;
	    </font>
	 </td>
      </tr>
   </table>
   <font size=1><br></font>
EOF;
// ----------------------------------------------------------------------------

} else {
   serverError();
   print "</font size=1><br></font>\n";
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
