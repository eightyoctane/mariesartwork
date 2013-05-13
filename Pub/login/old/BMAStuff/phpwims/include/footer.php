<?php

// ----------------------------------------------------------------------------
// Name: footer.php
// Author: R Sean Eidemiller (mojo@halcyon.cc)
// Last Modified: January 24, 2003
// ----------------------------------------------------------------------------

if (!stristr($HTTP_SERVER_VARS['HTTP_USER_AGENT'],"MSIE")) {

// ----------------------------------------------------------------------------
print <<<EOF
   <table class="custom" border=0 cellpadding=3 cellspacing=0 width="100%">
      <tr>
	 <td bgcolor="#808080">
	 </td>
      </tr>
   </table>
   <table border=0 cellpadding=2 cellspacing=0 width="100%">
      <tr>
	 <td align="left">
	    <font face="arial,helvetica" size=1>
	       Version 0.4.2 released January 25, 2003 by
		  <a href="mailto:mojo@halcyon.cc"><font face="arial,helvetica"
		     size=1>R Sean Eidemiller</font></a>
	    </font>
	 </td>
	 <td align="right">
	    <font face="arial,helvetica" size=1>
	       Copyright (C) 2001, 2002, 2003
	    </font>
	 </td>
      </tr>
   </table>
</body>
</html>
EOF;
// ----------------------------------------------------------------------------

} else {

// ----------------------------------------------------------------------------
print <<<EOF
   <font size=1>
      <table class="custom" border=0 cellpadding=0 cellspacing=0 width="100%">
         <tr>
            <td bgcolor="#808080">
	       <font size=1>&nbsp;</font>
            </td>
         </tr>
      </table>
      <table border=0 cellpadding=0 cellspacing=0 width="100%">
	 <tr>
	    <td align="left" valign="top">
	       <font face="arial,helvetica" size=1>
		  Version 0.4.2 released January 25, 2003 by
		     <a href="mailto:mojo@halcyon.cc"><font
			face="arial,helvetica"
			size=1>R Sean Eidemiller</font></a>
	       </font>
	    </td>
	    <td align="right" valign="top">
	       <font face="arial,helvetica" size=1>
		  Copyright (C) 2001, 2002, 2003
	       </font>
	    </td>
	 </tr>
      </table>   
   </font>
</body>
</html>
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
