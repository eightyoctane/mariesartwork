<?php

// ----------------------------------------------------------------------------
// Name: login.php
// Author: R Sean Eidemiller (mojo@halcyon.cc)
// Last Modified: January 24, 2003
// ----------------------------------------------------------------------------

include("include/sqlinfo.php");

// Connect to the database

$link = @mysql_connect("$sql_hostname", "$sql_username", "$sql_password")
   or die("ERROR: Could not connect to server");

@mysql_select_db("$sql_dbname")
   or die("ERROR: Could not connect to database");

// Authenticate user

$status = authenticate($user, $pass, $sql_hostname, $sql_username,
   $sql_password, $sql_dbname, $link);

if ($status == 1) {

   // Create the session

   session_start();

   // Initialize session variables

   session_register("WIMS_SESSION");
   session_register("WIMS_UNAME");
   session_register("WIMS_SOURCE1");
   session_register("WIMS_SOURCE2");
   session_register("WIMS_SOURCE3");
   session_register("WIMS_DEFAULT_TYPE");
   session_register("WIMS_DEFAULT_COUNTRY");

   $WIMS_UNAME = $user;

   // Obtain user defaults from options table

   $query = "SELECT type, country, scoreref1, scoreref2, scoreref3 ";
   $query .= "FROM options WHERE user='$user'";

   $result = @mysql_query($query);
   $row = @mysql_fetch_row($result);

   $WIMS_DEFAULT_TYPE = $row[0];
   $WIMS_DEFAULT_COUNTRY = $row[1];
   $WIMS_SOURCE1 = $row[2];
   $WIMS_SOURCE2 = $row[3];
   $WIMS_SOURCE3 = $row[4];

   header("Location: /phpwims/main.php");

} else {
   header("Location: /phpwims/index.php?error=$status");
}

mysql_close($link);
exit();

function authenticate($user, $pass, $sql_hostname, $sql_username,
   $sql_password, $sql_dbname, $link)
{
   // Get username and password from database

   $query = "SELECT password FROM user WHERE username='$user'";
   $result = @mysql_query($query);

   // Make sure query was successful

   if (($result) && (mysql_affected_rows($link))) {

      while ($row = @mysql_fetch_row($result)) {
	 $actualpass = $row[0];
      }

      // Compare given password to actual password

      if ($actualpass == crypt($pass, "pw")) {
	 $result = 1;
      } else {
	 $result = 2;
      }

   } else {
      $result = 3;
   }

   return $result;
}

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
