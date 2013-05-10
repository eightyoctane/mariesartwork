<?php

// ----------------------------------------------------------------------------
// Name: report.php
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

// ----------------------------------------------------------------------------
print <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript">

function valYear(year, report)
{
   if ((year.value == "Year") && (report[1].checked)) {
      alert("Dude, you have to choose a specific year...");
      return false;
   }

   return true;
}

function valRadio(report)
{
   for (i=0; i<report.length; i++) {
      if (report[i].checked) {
	 return true;
      }
   }

   alert("Dude, you have to select one of the three reports...");
   return false;
}

function validate(form)
{
   if (!valRadio(form.report)) {
      return false;
   } else if (!valYear(form.year, form.report)) {
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
                  <b><u>Report Generation</u></b>
               </font>
            </td>
            <td bgcolor="#808080" align="right">
               <font face="arial,helvetica" size=2 color="#ffffff">
                  PHP Wine Inventory Management System v0.4.2&nbsp;
               </font>
            </td>
         </tr>
      </table>
   </font>
   <font size=1><br></font>
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
	       Please select the type of report you would like to generate
		  using the form provided below...
	    </font>
         </td>
      </tr>
   </table>
   <br>
   <form method="post" action="generate.php"
      onsubmit="return validate(this)">
      <font face="arial,helvetica" size=2>
	 <input type="radio" name="report" value="count">
	 Produce a list of individual bottle quantities per&nbsp;
	 <select name="quanper" size=1>
	    <option selected>Appellation
	    <option>Bottle Size
	    <option>Country
	    <option>Price
	    <option>Producer
	    <option>Region
	    <option>Type
	    <option>Varietal
	    <option>Vintage
	 </select>
	 <font size=1><br><br></font>
	 <input type="radio" name="report" value="ready">
	 Produce a list of wines entering maturity in&nbsp;
	 <select name="year" size=1>
EOF;
// ----------------------------------------------------------------------------

$query = "SELECT DISTINCT beginconsume FROM bottle WHERE quantity>0 ";
$query .= "ORDER BY beginconsume";
$result = @mysql_query($query);

if ($result) {
   print "<option selected>Year\n";

   while ($row = @mysql_fetch_row($result)) {
      print "<option>$row[0]\n";
   }
} else {
   print "<option selected>ERROR\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
	 </select>&nbsp;
	 per&nbsp;
	 <select name="readyper" size=1>
	    <option selected>Appellation
	    <option>Bottle Size
	    <option>Price
	    <option>Type
	    <option>Varietal
	 </select>
	 <font size=1><br><br></font>
	 <input type="radio" name="report" value="value">
	 Produce an approximate total inventory value report.
	 <br><br>
	 <input type="submit" value="Generate Report">
	 <br>
      </font>
   </form>
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
