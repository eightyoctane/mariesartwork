<?php

// ----------------------------------------------------------------------------
// Name: main.php
// Author: R Sean Eidemiller (mojo@halcyon.cc)
// Last Modified: January 25, 2003
// ----------------------------------------------------------------------------

session_start();

// Check to make sure current session is valid

if (!session_is_registered("WIMS_SESSION")) {
   header("Location: /phpwims/index.php?error=4");
   exit();
}

// Increase frame size for Internet Exploder users

if (stristr($HTTP_SERVER_VARS['HTTP_USER_AGENT'],"MSIE")) {
   $size = "200";
} else {
   $size = "190";
}

// ----------------------------------------------------------------------------
print <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
   <title>PHP Wine Inventory Management System</title>
   <frameset cols="$size,*">
      <frame name="navbar" scrolling="yes" src="navbar.php">
      <frame name="main" scrolling="yes" src="navigator.php">
   </frameset>
</head>
</html>
EOF;
// ----------------------------------------------------------------------------

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
