<?php

// ----------------------------------------------------------------------------
// Name: index.php
// Author: R Sean Eidemiller (mojo@halcyon.cc)
// Last Modified: January 24, 2003
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
print <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PHP Wine Inventory Management System</title>
</head>
<body bgcolor="#ffffff">
   <table border=0 cellpadding=3 cellspacing=0 width="100%">
      <tr>
         <td bgcolor="#808080">
            <font face="arial,helvetica" size=2 color="#ffffff">
               <b><u>Login Screen</u></b>
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
	       Please enter your username and password using the form provided
		  below...
	    </font>
	 </td>
      </tr>
   </table>
   <br>
EOF;
// ----------------------------------------------------------------------------

if ($error == 2) {
   print "<font face='arial,helvetica' color='#ff0000' size=1>\n";
   print "ERROR: INVALID PASSWORD. PLEASE TRY AGAIN...<br><br></font>\n";
} else if ($error == 3) {
   print "<font face='arial,helvetica' color='#ff0000' size=1>\n";
   print "ERROR: INVALID USERNAME. PLEASE TRY AGAIN...<br><br></font>\n";
} else if ($error == 4) {
   print "<font face='arial,helvetica' color='#ff0000' size=1>\n";
   print "ERROR: ALL USERS MUST LOGIN BEFORE ACCESSING THE DATABASE...";
   print "<br><br></font>\n";
}

// ----------------------------------------------------------------------------
print <<<EOF
   <table border=0 cellpadding=0 cellspacing=0>
      <form action="login.php" method="post">
      <tr>
         <td align="right">
	    <font face="arial,helvetica" size=2>
	       Username:
	       <input type="text" size=15 name="user">
	       &nbsp;&nbsp;
	       Password:
	       <input type="password" size=15 name="pass">
	    </font>
         </td>
         <td>
	    &nbsp&nbsp;&nbsp;&nbsp;&nbsp;
	    <input type="submit" value="Enter Database">
         </td>
      </tr>
      </form>
   </table>
   <br>
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
	       Click <a href="http://phpwims.sourceforge.net">here</a> to
		  visit the project homepage, graciously hosted by
		  <a href="http://sourceforge.net">sourceforge.net</a>
	    </font>
         </td>
      </tr>
   </table>
   <font size=1><br></font>
EOF;
// ----------------------------------------------------------------------------

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
