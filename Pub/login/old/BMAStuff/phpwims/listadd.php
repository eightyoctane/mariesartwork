<?php

// ----------------------------------------------------------------------------
// Name: listadd.php
// Author: R Sean Eidemiller (mojo@halcyon.cc)
// Last Modified: February 01, 2003
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

// Get needed data from bottle table

$query = "SELECT vintage, producer, varietal, appellation, store, price ";
$query .= "FROM bottle WHERE id=$id";

$result = @mysql_query($query);

if ($result) {
   $row = @mysql_fetch_row($result);
} else {
   mysql_close($link);
   genError();
   exit();
}

// Make sure the bottle doesn't already exist in the shopping list

$safequery = "SELECT id FROM list";
$saferesult = @mysql_query($safequery);
$saferow = @mysql_fetch_row($saferesult);

if ($id == $saferow[0]) {
   // bottle already exists, so...
   mysql_close($link);
   listExistError();
   exit();
}

$newquery = "INSERT INTO list VALUES ($id, $row[0], \"$row[1]\", \"$row[2]\",";
$newquery .= " \"$row[3]\", \"$row[4]\", \"$row[5]\", \"$WIMS_UNAME\")";

$newresult = @mysql_query($newquery);

if ($newresult) {
   header("Location: /phpwims/list.php");
} else {
   genError();
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
