<?php

// ----------------------------------------------------------------------------
// Name: error.php
// Author: R Sean Eidemiller (mojo@halcyon.cc)
// Last Modified: February 01, 2003
// ----------------------------------------------------------------------------

function serverError()
{

// ----------------------------------------------------------------------------
print <<<EOF
   <font face="arial,helvetica" size=2 color="#ff0000">
      ERROR: THE SERVER ENCOUNTERED AN ERROR WHILE PROCESSING YOUR REQUEST
   </font>
   <font size=1><br></font>
EOF;
// ----------------------------------------------------------------------------

}

function yearError()
{

// ----------------------------------------------------------------------------
print <<<EOF
   <font face="arial,helvetica" size=2 color="#ff0000">
      ERROR: YOU MUST CHOOSE A SPECIFIC YEAR FROM THE REPORT GENERATION FORM
   </font>
   <font size=1><br></font>
EOF;
// ----------------------------------------------------------------------------

}

function logError()
{

// ----------------------------------------------------------------------------
print <<<EOF
   <font face="arial,helvetica" size=2 color="#ff0000">
      ERROR: THE SERVER WAS UNABLE TO UPDATE THE LOG DATABASE
   </font>
   <font size=1><br></font>
EOF;
// ----------------------------------------------------------------------------

}

function updateError()
{

// ----------------------------------------------------------------------------
print <<<EOF
   <font face="arial,helvetica" size=2 color="#ff0000">
      ERROR: DUDE, YOU HAVE TO ACTUALLY UPDATE SOMETHING
   </font>
   <font size=1><br></font>
EOF;
// ----------------------------------------------------------------------------

}

function listExistError()
{
   printTitle();
   serverError();

// ----------------------------------------------------------------------------
print <<<EOF
   <font face="arial,helvetica" size=2 color="#ff0000">
      ERROR: The bottle you requested already exists in the shopping list
   </font>
   <font size=1><br></font>
EOF;
// ----------------------------------------------------------------------------

   print "<font size=1><br></font>";
   include("footer.php");
}
   
function genError()
{
   printTitle();
   serverError();
   print "<font size=1><br></font>";
   include("footer.php");
}

function printTitle()
{

// ----------------------------------------------------------------------------
print <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<body bgcolor="#ffffff">
   <table class="custom" border=0 cellpadding=3 cellspacing=0 width="100%">
      <tr>
         <td bgcolor="#808080">
            <font face="arial,helvetica" size=2 color="#ffffff">
               <b><u>Server Error</u></b>
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
EOF;
// ----------------------------------------------------------------------------

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
