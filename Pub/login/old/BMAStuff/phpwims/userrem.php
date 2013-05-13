<?php

// ----------------------------------------------------------------------------
// Name: userrem.php
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
<script language="JavaScript">

function verifyRemove()
{
   return confirm("Are you SURE you want to remove this user?");
}

</script>
</head>
<body bgcolor="#ffffff">
   <table border=0 cellpadding=3 cellspacing=0 width="100%">
      <tr>
         <td bgcolor="#808080">
            <font face="arial,helvetica" size=2 color="#ffffff">
	       <b><u>User Management</u></b>
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
   <table class="custom" border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
	       Please enter the user account to be deleted using the form
		  below...
	    </font>
	 </td>
      </tr>
   </table>
   <br>

EOF;
// ----------------------------------------------------------------------------

if ($status == 2) {
   print "<font face='arial,helvetica' color='#ff0000' size=1>\n";
   print "ERROR: USER DOES NOT EXIST. PLEASE TRY AGAIN...<br><br></font>\n";
} else if ($status == 3) {
   print "<font face='arial,helvetica' color='#ff0000' size=1>\n";
   print "ERROR: AN UNKNOWN ERROR HAS OCCURRED. PLEASE TRY AGAIN...";
   print "<br><br></font>\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
   <form action="deleteuser.php" method="post"
      onsubmit="return verifyRemove();">
      <font face="arial,helvetica" size=2>
	 Username:
	 <input type="text" size=12 name="username">
	 <br><br>
      </font>
      <table border=0 cellpadding=0 cellspacing=0>
	 <tr>
	    <td align="center">
	       <input type="submit" value="Remove User">
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
