<?php

// ----------------------------------------------------------------------------
// Name: createuser.php
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

// Make sure passwords match

if ($password == $verify) {
   $newpass = crypt($password, "pw");

   $addquery = "INSERT INTO user VALUES (NULL, '$username', '$newpass')";
   $addresult = @mysql_query($addquery);

   $optionsquery = "INSERT INTO options VALUES (NULL, '$username', ";
   $optionsquery .= "'Red', 'France', 'Wine Advocate', 'Wine Spectator', ";
   $optionsquery .= "'Wine Enthusiast')";
   $optionsresult = @mysql_query($optionsquery);

   if ($addresult && $optionsresult) {
      header("Location: /phpwims/admin.php?status=2&user=$username");
   } else {
      header("Location: /phpwims/useradd.php?status=3");
   }

} else {
   header("Location: /phpwims/useradd.php?status=2");
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
