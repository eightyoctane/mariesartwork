<?php

// ----------------------------------------------------------------------------
// Name: password.php
// Author: R Sean Eidemiller (mojo@halcyon.cc)
// Last Modified: January 24, 2003
// ----------------------------------------------------------------------------

session_start();

// Check to make sure current session is valid

if (!session_is_registered("WIMS_SESSION")) {
   header("Location: /phpwims/index.php?error=4");
   exit();
}

// ----------------------------------------------------------------------------
print <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<body bgcolor="#ffffff">
   <table border=0 cellpadding=3 cellspacing=0 width="100%">
      <tr>
         <td bgcolor="#808080">
            <font face="arial,helvetica" size=2 color="#ffffff">
	       <b><u>Password Change</u></b>
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
	       Please enter you current password and desired password using the
		  form below...
	    </font>
         </td>
      </tr>
   </table>
   <br>
EOF;
// ----------------------------------------------------------------------------

if ($status == 2) {
   print "<font face='arial,helvetica' color='#ff0000' size=1>\n";
   print "ERROR: INVALID PASSWORD. PLEASE TRY AGAIN...<br><br></font>\n";
} else if ($status == 3) {
   print "<font face='arial,helvetica' color='#ff0000' size=1>\n";
   print "ERROR: PASSWORDS DO NOT MATCH. PLEASE TRY AGAIN...<br><br></font>\n";
} else if ($status == 4) {
   print "<font face='arial,helvetica' color='#ff0000' size=1>\n";
   print "ERROR: AN UNKNOWN ERROR HAS OCCURRED. PLEASE TRY AGAIN...";
   print "<br><br></font>\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
   <form action="pwchange.php" method="post">
      <font face="arial,helvetica" size=2>
	 Password:
	 <input type="password" size=12 name="oldpass">
	 &nbsp;&nbsp;
	 New Password:
	 <input type="password" size=12 name="newpass">
	 &nbsp;&nbsp;
	 Verify:
	 <input type="password" size=12 name="verify">
	 <br><br>
      </font>
      <table border=0 cellpadding=0 cellspacing=0>
	 <tr>
	    <td align="center">
	       <input type="submit" value="Change Password">
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
   print "<font size=1><br></font>\n";
}

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
