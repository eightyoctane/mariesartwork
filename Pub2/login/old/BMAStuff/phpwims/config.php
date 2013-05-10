<?php

// ----------------------------------------------------------------------------
// Name: config.php
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
</head>
<body bgcolor="#ffffff">
   <table border=0 cellpadding=3 cellspacing=0
      width="100%">
      <tr>
	 <td bgcolor="#808080">
	    <font face="arial,helvetica" size=2 color="#ffffff">
	       <b><u>Configuration Options</u></b>
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
	       You may customize several of the default configuration options
		  using the form provided below...
	    </font>
	 </td>
      </tr>
   </table>
   <br>
   <form method="post" action="updateconfig.php">
      <font face="arial,helvetica" size=2>
	 Default Submit Type:&nbsp;
	 <select name="type" size=1>
	    <option selected>$WIMS_DEFAULT_TYPE
EOF;
// ----------------------------------------------------------------------------

$query = "SELECT DISTINCT type FROM bottle ORDER BY type";
$result = @mysql_query($query);

while ($row = @mysql_fetch_row($result)) {
   if ($row[0] != $WIMS_DEFAULT_TYPE) {
      print "<option>$row[0]\n";
   }
}

// ----------------------------------------------------------------------------
print <<<EOF
	 </select>
	 <font size=1><br><br></font>
	 Default Submit Country:&nbsp;
	 <select name="country" size=1>
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

if ($WIMS_DEFAULT_COUNTRY != "Italy") {
   print "<option>Italy\n";
}

if ($WIMS_DEFAULT_COUNTRY != "Hungary") {
   print "<option>Hungary\n";
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
	 </select>
	 <font size=1><br><br></font>
	 Score Reference #1:&nbsp;
	 <input type="text" name="scoreref1" value="$WIMS_SOURCE1" size=20>
	 <font size=1><br><br></font>
	 Score Reference #2:&nbsp;
	 <input type="text" name="scoreref2" value="$WIMS_SOURCE2" size=20>
	 <font size=1><br><br></font>
	 Score Reference #3:&nbsp;
	 <input type="text" name="scoreref3" value="$WIMS_SOURCE3" size=20>
	 <br><br>
      </font>
      <table border=0 cellpadding=0 cellspacing=0>
	 <tr>
	    <td>
	       <input type="submit" value="Submit Changes">
	       &nbsp;&nbsp;
	       <input type="reset" value="Reset Form">
	       </form>
	    </td>
	    <td>
               &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td>
	       <form method="link" action="admin.php">
		  <input type="submit" value="Cancel">
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
	       <b>Note:</b> None of the changes made on this page will take
		  effect until your next login.
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
