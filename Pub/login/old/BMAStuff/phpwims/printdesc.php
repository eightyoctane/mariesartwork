<?php

// ----------------------------------------------------------------------------
// Name: printdesc.php
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

// Query database for bottle information

$query = "SELECT * FROM bottle WHERE id=$id";
$result = @mysql_query($query);
$row = @mysql_fetch_row($result);

$notesquery = "SELECT * FROM notes WHERE id=$id";
$notesresult = @mysql_query($notesquery);
$notesrow = @mysql_fetch_row($notesresult);

$notes = $notesrow[1];
$notes = str_replace("\n", "<br>", $notes);

// Generate timestamp

$time = trim(`/bin/date`);

// ----------------------------------------------------------------------------
print <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
   <title>PHP Wine Inventory Management System</title>
</head>
<body bgcolor="#ffffff">
   <table border=0 cellpadding=0 cellspacing=0 width="100%">
      <tr>
	 <td valign="middle" align="left">
	    <font size=6><b>WIMS Bottle Description</b></font>
	 </td>
	 <td valign="middle" align="right">
	    <b>Date:</b> &nbsp;$time
	 </td>
      </tr>
   </table>
   <hr>
   <font size=1><br></font>
EOF;
// ----------------------------------------------------------------------------

if ($result && $notesresult) {

// ----------------------------------------------------------------------------
print <<<EOF
   <b>Country of Origin:</b> &nbsp;$row[7]
   <font size=1><br><br></font>
   <b>Producer:</b> &nbsp;$row[3]<br>
   <b>Vineyard:</b> &nbsp;$row[4]<br>
   <b>Varietal:</b> &nbsp;$row[5]<br>
   <b>Vintage:</b> &nbsp;$row[2]
   <font size=1><br><br></font>
   <b>Appellation:</b> &nbsp;$row[14]<br>
   <b>Region:</b> &nbsp;$row[6]
   <font size=1><br><br></font>
   <b>$WIMS_SOURCE1 Score:</b> &nbsp;$row[16]<br>
   <b>$WIMS_SOURCE2 Score:</b> &nbsp;$row[17]<br>
   <b>$WIMS_SOURCE3 Score:</b> &nbsp;$row[18]
   <font size=1><br><br></font>
   $notes
   <hr>
   Copyright (C) 2001, 2002, 2003 R Sean Eidemiller (mojo@halcyon.cc)
</body>
</html>
EOF;
// ----------------------------------------------------------------------------

}

mysql_close($link);

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
