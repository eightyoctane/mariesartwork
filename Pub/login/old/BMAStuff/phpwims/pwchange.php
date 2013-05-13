<?php

// ----------------------------------------------------------------------------
// Name: pwchange.php
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

// Validate given password

$pwquery = "SELECT password FROM user WHERE username='$WIMS_UNAME'";
$pwresult = @mysql_query($pwquery);

if (($pwresult) && (mysql_affected_rows($link))) {
   $pwrow = @mysql_fetch_row($pwresult);
   $actualpass = $pwrow[0];

   if ($actualpass == (crypt($oldpass, "pw"))) {

      if ($verify == $newpass) {
	 $password = crypt($newpass, "pw");
	 $updatequery = "UPDATE user SET password='$password' WHERE ";
	 $updatequery .= "username='$WIMS_UNAME'";
	 $updateresult = @mysql_query($updatequery);

	 if (($updateresult) && (mysql_affected_rows($link))) {
	    header("Location: /phpwims/admin.php?status=1");
	 } else {
	    header("Location: /phpwims/password.php?status=4");
	 }

      } else {
         header("Location: /phpwims/password.php?status=3");
      }

   } else {
      header("Location: /phpwims/password.php?status=2");
   }

} else {
   header("Location: /phpwims/password.php?status=4");
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
