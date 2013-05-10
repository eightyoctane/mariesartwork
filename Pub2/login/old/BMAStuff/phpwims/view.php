<?php

// ----------------------------------------------------------------------------
// Name: view.php
// Author: R Sean Eidemiller (mojo@halcyon.cc)
// Last Modified: January 24, 2003
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
               <b><u>Search Results</u></b>
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

// Modify type field from form data

if ($view == "Entire Inventory") {
   $view = "Any";
}

// Generate query statement

$query = "SELECT * FROM bottle";

if (($view != "Any") || ($country != "Any") || ($varietal != "Any") ||
   ($vintage != "Any") || ($price != "Any") || ($producer != "Any") || 
   ($region != "Any") || ($appellation != "Any") ||
   ($score != "Any Score") || ($source != "Any Source") ||
   ($viewpref != "all")) {

   $query .= " WHERE ";
}

if ($view != "Any") {
   if ($view == "All Others") {
      $query .= "type != 'red' AND type != 'white'";
   } else if ($view == "All Reds") {
      $query .= "type = 'red'";
   } else if ($view == "All Whites") {
      $query .= "type = 'white'";
   }
}

if ($country != "Any") {
   if ($view != "Any") {
      $query .= " AND ";
   }
   $query .= "country = '$country'";
}

if ($varietal != "Any") {
   if (($view != "Any") || ($country != "Any")) { 
      $query .= " AND ";
   }
   $query .= "varietal = '$varietal'";
}

if (($vintage != "Any") && ($vintage != "NV")) {
   if (($view != "Any") || ($country != "Any") || ($varietal != "Any")) {
      $query .= " AND ";
   }
   $query .= "vintage = $vintage";
} else if ($vintage == "NV") {
   if (($view != "Any") || ($country != "Any") || ($varietal != "Any")) {
      $query .= " AND ";
   }
   $query .= "vintage=1001";
}

if ($producer != "Any") {
   if (($view != "Any") || ($country != "Any") || ($varietal != "Any") ||
      ($vintage != "Any")) {

      $query .= " AND ";
   }
   $query .= "producer = '$producer'";
}

if ($price != "Any") {
   if (($view != "Any") || ($country != "Any") || ($varietal != "Any") ||
      ($vintage != "Any") || ($producer != "Any")) { 

      $query .= " AND ";
   }
   $query .= "price = '$price'";
}

if ($appellation != "Any") {
   if (($view != "Any") || ($country != "Any") || ($varietal != "Any") ||
      ($vintage != "Any") || ($producer != "Any") || ($price != "Any")) {

      $query .= " AND ";
   }
   $query .= "appellation = '$appellation'";
}

if ($region != "Any") {
   if (($view != "Any") || ($country != "Any") || ($varietal != "Any") ||
      ($vintage != "Any") || ($producer != "Any") || ($price != "Any") ||
      ($appellation != "Any")) {

      $query .= " AND ";
   }
   $query .= "region = '$region'";
}

if ($viewpref != "all") {
   if (($view != "Any") || ($country != "Any") || ($varietal != "Any") ||
      ($vintage != "Any") || ($producer != "Any") || ($price != "Any") ||
      ($appellation != "Any") || ($region != "Any")) {

      $query .= " AND ";
   }

   $query .= "quantity > 0";
}

if (($score != "Any Score") && ($score != "No Score")) {
   if ($score == "50+") {
      $score = "50";
   } else if ($score == "60+") {
      $score = "60";
   } else if ($score == "70+") {
      $score = "70";
   } else if ($score == "80+") {
      $score = "80";
   } else if ($score == "90+") {
      $score = "90";
   }

   if (($view != "Any") || ($country != "Any") || ($varietal != "Any") ||
      ($vintage != "Any") || ($producer != "Any") || ($price != "Any") ||
      ($appellation != "Any") || ($region != "Any") ||
      ($viewpref != "all")) {

      $query .= " AND ";
   }

   if ($source == "Any Source") {
      $query .= "(score1>=$score OR score2>=$score OR score3>=$score)";   
   } else if ($source == $WIMS_SOURCE1) {
      $query .= "score1>=$score";
   } else if ($source == $WIMS_SOURCE2) {
      $query .= "score2>=$score";
   } else if ($source == $WIMS_SOURCE3) {
      $query .= "score3>=$score";
   }
} else if (($score == "Any Score") && ($source != "Any Source")) {
   if (($view != "Any") || ($country != "Any") || ($varietal != "Any") ||
      ($vintage != "Any") || ($producer != "Any") || ($price != "Any") ||
      ($appellation != "Any") || ($region != "Any") ||
      ($viewpref != "all")) {

      $query .= " AND ";
   }

   if ($source == $WIMS_SOURCE1) {
      $query .= "score1>=0";
   } else if ($source == $WIMS_SOURCE2) {
      $query .= "score2>=0";
   } else if ($source == $WIMS_SOURCE3) {
      $query .= "score3>=0";
   }
} else if (($score == "No Score") && ($source == "Any Source")) {
   if (($view != "Any") || ($country != "Any") || ($varietal != "Any") ||
      ($vintage != "Any") || ($producer != "Any") || ($price != "Any") ||
      ($appellation != "Any") || ($region != "Any") ||
      ($viewpref != "all")) {

      $query .= " AND score1 IS NULL AND score2 IS NULL";
      $query .= " AND score3 IS NULL ";
   } else {
      $query .= "score1 IS NULL AND score2 IS NULL AND score3 IS NULL";
   }
} else if (($score == "No Score") && ($source != "Any Source")) {
   if (($view != "Any") || ($country != "Any") || ($varietal != "Any") ||
      ($vintage != "Any") || ($producer != "Any") || ($price != "Any") ||
      ($appellation != "Any") || ($region != "Any") ||
      ($viewpref != "all")) {

      $query .= " AND ";
   }

   if ($source == $WIMS_SOURCE1) {
      $query .= "score1 IS NULL";
   } else if ($source == $WIMS_SOURCE2) {
      $query .= "score2 IS NULL";
   } else if ($source == $WIMS_SOURCE3) {
      $query .= "score3 IS NULL";
   }
}

// Generate sorting rules

if ($sort == "Vintage") {
   if ($updown == "Ascending") {
      $query .= " ORDER BY vintage, type, producer";
   } else {
      $query .= " ORDER BY vintage DESC, type, producer";
   }
} else if ($sort == "Type") {
   if ($updown == "Ascending") {
      $query .= " ORDER BY type, producer, vintage";
   } else {
      $query .= " ORDER BY type DESC, producer, vintage";
   }
} else if ($sort == "Varietal") {
   if ($updown == "Ascending") {
      $query .= " ORDER BY varietal, producer, vintage";
   } else {
      $query .= " ORDER BY varietal DESC, producer, vintage";
   }
} else if ($sort == "Producer") {
   if ($updown == "Ascending") {
      $query .= " ORDER BY producer, vineyard, vintage";
   } else {
      $query .= " ORDER BY producer DESC, vineyard, vintage";
   }
} else if ($sort == "Vineyard") {
   if ($updwon == "Ascending") {
      $query .= " ORDER BY vineyard, vintage";
   } else {
      $query .= " ORDER BY vineyard DESC, vintage";
   }
} else if ($sort == "Region") {
   if ($updown == "Ascending") {
      $query .= " ORDER BY region, producer, vintage";
   } else {
      $query .= " ORDER BY region DESC, producer, vintage";
   }
} else if ($sort == "Country") {
   if ($updown == "Ascending") {
      $query .= " ORDER BY country, producer, vintage";
   } else {
      $query .= " ORDER BY country DESC, producer, vintage";
   }
} else if ($sort == "Date Cellared") {
   if ($updown == "Ascending") {
      $query .= " ORDER BY datecellared, producer, vintage";
   } else {
      $query .= " ORDER BY datecellared DESC, producer, vintage";
   }
} else if ($sort == "Begin Consume") {
   if ($updown == "Ascending") {
      $query .= " ORDER BY beginconsume, producer, vintage";
   } else {
      $query .= " ORDER BY beginconsume DESC, producer, vintage";
   }
} else if ($sort == "Consume By") {
   if ($updown == "Ascending") {
      $query .= " ORDER BY consumeby, producer, vintage";
   } else {
      $query .= " ORDER BY consumeby DESC, producer, vintage";
   }
} else if ($sort == "Quantity") {
   if ($updown == "Ascending") {
      $query .= " ORDER BY quantity, producer, vintage";
   } else {
      $query .= " ORDER BY quantity DESC, producer, vintage";
   }
} else if ($sort == "Price") {
   if ($updown == "Ascending") {
      $query .= " ORDER BY price, producer, vintage";
   } else {
      $query .= " ORDER BY price DESC, producer, vintage";
   }
} else if ($sort == "Store") {
   if ($updown == "Ascending") {
      $query .= " ORDER BY store, producer, vintage";
   } else {
      $query .= " ORDER BY store DESC, producer, vintage";
   }
} else if ($sort == "Size") {
   if ($updown == "Ascending") {
      $query .= " ORDER BY size, producer, vintage";
   } else {
      $query .= " ORDER BY size DESC, producer, vintage";
   }
} else if ($sort == "Appellation") {
   if ($updown == "Ascending") {
      $query .= " ORDER BY appellation, producer, vintage";
   } else {
      $query .= " ORDER BY appellation DESC, producer, vintage";
   }
} else if ($sort == $WIMS_SOURCE1." Score") {
   if ($updown == "Ascending") {
      $query .= " ORDER BY score1, producer, vintage";
   } else { 
      $query .= " ORDER BY score1 DESC, producer, vintage";
   }
} else if ($sort == $WIMS_SOURCE2." Score") {
   if ($updown == "Ascending") {
      $query .= " ORDER BY score2, producer, vintage";
   } else {
      $query .= " ORDER BY score2 DESC, producer, vintage";
   }
} else if ($sort == $WIMS_SOURCE3." Score") {
   if ($updown == "Ascending") {
      $query .= " ORDER BY score3, producer, vintage";
   } else {
      $query .= " ORDER BY score3 DESC, producer, vintage";
   }
}

// Query the database

$result = @mysql_query($query);

// Print results of query

if (($result) && (mysql_affected_rows($link))) {
   $numrows = mysql_affected_rows($link);

// ----------------------------------------------------------------------------
print <<<EOF
   <font face="arial,helvetica" size=2>
      Your query was successful and the server returned the following
	 <b>$numrows</b> matching entry(ies)...&nbsp;
   </font>
   <font size=1><br><br></font>
   <table border=0 cellpadding=2 cellspacing=2>
      <tr bgcolor="#808080">
	 <td><font face="arial,helvetica" size=2
	    color="#ffffff">Vintage</b>&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Producer</b>&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Vineyard</b>&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Varietal</b>&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Appellation</b>&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Quan</b>&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Details</b>&nbsp;&nbsp;</font></td>
      </tr>
EOF;
// ----------------------------------------------------------------------------

   $catsrule = 0;
   
   while ($row = @mysql_fetch_row($result)) {
      if ($row[2] == "1001") {
	 $row[2] = "NV";
      }

      if (($catsrule % 2) == 0) {
	 $color="aliceblue";
      } else {
	 $color="#eeeeee";
      }

      $catsrule++;

// ----------------------------------------------------------------------------
print <<<EOF
      <tr bgcolor="$color">
	 <td><font face="arial,helvetica" size=2>$row[2]</font></td>
         <td><font face="arial,helvetica" size=2>$row[3]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$row[4]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$row[5]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$row[14]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$row[13]&nbsp;</font></td>
         <td><a href="details.php?id=$row[0]"><font face="arial,helvetica"
	    size=2>View</font></a></td>
      </tr>
EOF;
// ----------------------------------------------------------------------------
 
   }
   print "</table>\n";

} else if (($result) && (!mysql_affected_rows($link))) {

// ----------------------------------------------------------------------------
print <<<EOF
   <font face="arial,helvetica" size=2 color="#ff0000">
      Unfortunately, the server was unable to find any entries matching your
	 query.
   </font>
   <font size=1><br></font>
EOF;
// ----------------------------------------------------------------------------

} else {
   serverError();
}

print "<font size=1><br></font>\n";

mysql_close($link);
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
