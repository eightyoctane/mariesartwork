<?php

// ----------------------------------------------------------------------------
// Name: upgrade.php
// Author: R Sean Eidemiller (mojo@halcyon.cc)
// Last Modified: January 24, 2003
// ----------------------------------------------------------------------------

include("include/sqlinfo.php");

// Connect to the database

$link = @mysql_connect("$sql_hostname", "$sql_username", "$sql_password")
   or die("ERROR: Could not connect to server");

@mysql_select_db("$sql_dbname")
   or die("ERROR: Could not connect to database");

$query = "SELECT DISTINCT username FROM user WHERE username!='admin'";
$result = @mysql_query($query);

if ($result) {

// ----------------------------------------------------------------------------
print <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
   <title>PHP Wine Inventory Management System</title>
</head>
<body bgcolor="#ffffff">
EOF;
// ----------------------------------------------------------------------------

   $we_fucked_up = 0;

   while ($row = @mysql_fetch_row($result)) {
      print "Applying default options for user <b>$row[0]</b>... &nbsp;";

      $newquery = "INSERT INTO options VALUES (NULL, '$row[0]', 'Red', ";
      $newquery .= "'France', 'Wine Advocate', 'Wine Spectator', ";
      $newquery .= "'Wine Enthusiast')";

      $newresult = @mysql_query($newquery);

      if ($newquery) {
	 print "done.<br>\n";
      } else {
	 print "<font color='#ff0000'>failed</font>.<br>\n";
	 $we_fucked_up++;
      }
   }

   if ($we_fucked_up == 0) {
      print "<br>Upgrade successful!<br><br>\n";
      print "You may now delete this file (upgrade.php) from your ";
      print "phpwims directory.\n";
   } else {
      print "<br><font color='#ff0000'>Upgrade was not successful.";
      print "</font>\n";
   }

} else {
   print "<font color='#ff0000'>Unable to update the options table. ";
   print "Upgrade was unsuccessful.</font>";
}

// ----------------------------------------------------------------------------
print <<<EOF
</body>
</html>
EOF;
// ----------------------------------------------------------------------------

mysql_close($link);
exit();

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
