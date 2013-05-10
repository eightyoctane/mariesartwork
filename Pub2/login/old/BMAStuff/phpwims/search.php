<?php

// ----------------------------------------------------------------------------
// Name: search.php
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

// ----------------------------------------------------------------------------
print <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
</head>
<body bgcolor="#ffffff">
   <table border=0 cellpadding=3 cellspacing=0 width="100%">
      <tr>
         <td bgcolor="#808080">
            <font face="arial,helvetica" size=2 color="#ffffff">
	       <b><u>Notes Search</u></b>
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
	       Please enter the keword or keywords that you would like to
		  search for using the form provided below...
	    </font>
         </td>
      </tr>
   </table>
EOF;
// ----------------------------------------------------------------------------

if ($status == 1) {

// ----------------------------------------------------------------------------
print <<<EOF
   <br>
   <font face="arial,helvetica" size=2 color="#ff0000">
      Unfortunately, the server was unable to find any entries matching your
         query.
   </font>
   <br>
EOF;
// ----------------------------------------------------------------------------

}

// ----------------------------------------------------------------------------
print <<<EOF
   <br>
   <form method="post" action="results.php">
      <font face="arial,helvetica" size=2>
	 Search for keyword(s):&nbsp;
	 <input type="text" name="search" size="60">
	 <br><br>
	 <input type="submit" value="Begin Search">
	 &nbsp;&nbsp;&nbsp;
	 <input type="reset" value="Clear Form">
      </font>
   </form>
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
	       <b>Note:</b> Please be patient as it may take several seconds
		  for the server to process your query.&nbsp;
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
