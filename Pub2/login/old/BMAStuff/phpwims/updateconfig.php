<?php

// ----------------------------------------------------------------------------
// Name: updateconfig.php
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

// Query database for current settings

$query = "SELECT type, country, scoreref1, scoreref2, scoreref3 ";
$query .= "FROM options WHERE user='$WIMS_UNAME'";

$result = @mysql_query($query);
$row = @mysql_fetch_row($result);

$origtype = $row[0];
$origcountry = $row[1];
$origref1 = $row[2];
$origref2 = $row[3];
$origref3 = $row[4];

// Generate Update Statement

$update = "UPDATE options SET";
$change = 0;

if ($type != $origtype) {
   $update .= " type=\"$type\"";
   $change = $change + 1;
}

if ($country != $origcountry) {
   if ($change > 0) {
      $update .= ", country=\"$country\"";
   } else {
      $update .= " country=\"$country\"";
   }

   $change = $change + 1;
}

if ($scoreref1 != $origref1) {
   if ($change > 0) {
      $update .= ", scoreref1=\"$scoreref1\"";
   } else {
      $update .= " scoreref1=\"$scoreref1\"";
   }
   
   $change = $change + 1;
}

if ($scoreref2 != $origref2) {
   if ($change > 0) {
      $update .= ", scoreref2=\"$scoreref2\"";
   } else {
      $update .= " scoreref2=\"$scoreref2\"";
   }

   $change = $change + 1;
}

if ($scoreref3 != $origref3) {
   if ($change > 0) {
      $update .= ", scoreref3=\"$scoreref3\"";
   } else {
      $update .= " scoreref3=\"$scoreref3\"";
   }

   $change = $change + 1;
}


$update .= " WHERE user='$WIMS_UNAME'";

// Submit update request

if ($change > 0) {
   $result = @mysql_query($update);
}

// View results (if any)

if ($result) {
   header("Location: /phpwims/admin.php?status=6");
} else if ($change == 0) {
   header("Location: /phpwims/config.php?status=1");
} else {
   header("Location: /phpwims/admin.php?status=5");
}

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
