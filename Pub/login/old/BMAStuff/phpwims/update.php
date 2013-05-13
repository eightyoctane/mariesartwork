<?php

// ----------------------------------------------------------------------------
// Name: update.php
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
               <b><u>Description Update</u></b>
            </font>
         </td>
         <td bgcolor="#808080" align="right">
            <font face="arial,helvetica" size=2 color="#ffffff">
               PHP Wine Inventory Management System v0.4.2&nbsp
            </font>
         </td>
      </tr>
   </table>
   <font size=1><br></font>
EOF;
// ----------------------------------------------------------------------------

// Get original data for bottle

$origquery = "SELECT * FROM bottle WHERE id=$id";
$origresult = @mysql_query($origquery);

$notesquery = "SELECT * FROM notes WHERE id=$id";
$notesresult = @mysql_query($notesquery);

if ($origresult) {
   $row = @mysql_fetch_row($origresult);
   $origtype = $row[1];
   $origvintage = $row[2];
   $origproducer = $row[3];
   $origvineyard = $row[4];
   $origvarietal = $row[5];
   $origregion = $row[6];
   $origcountry = $row[7];
   $origdatecellared = $row[8];
   $origbeginconsume = $row[9];
   $origconsumeby = $row[10];
   $origstore = $row[11];
   $origprice = $row[12];
   $origquantity = $row[13];
   $origappellation = $row[14];
   $origsize = $row[15];
   $origscore1 = $row[16];
   $origscore2 = $row[17];
   $origscore3 = $row[18];
}

if ($notesresult) {
   $notesrow = @mysql_fetch_row($notesresult);
   $orignotes = $notesrow[1];
}

// Generate Update Statement

$update = "UPDATE bottle SET";
$change = 0;

if ($vintage != $origvintage) {
   if (($vintage == "NV") || ($vintage == "nv")) {
      $update .= " vintage=1001";
   } else {
      $update .= " vintage=$vintage";
   }
   $change = $change + 1;
}

if ($type == "New") {
   if ($change > 0) {
      $update .= ", type=\"$typenew\"";
   } else {
      $update .= " type=\"$typenew\"";
   }
   $change = $change + 1;
} else if ($type != $origtype) {
   if ($change > 0) {
      $update .= ", type=\"$type\"";
   } else {
      $update .= " type=\"$type\"";
   }
   $change = $change + 1;
}

if ($producer == "New") {
   if ($change > 0) {
      $update .= ", producer=\"$producernew\"";
   } else {
      $update .= " producer=\"$producernew\"";
   }
   $change = $change + 1;
} else if ($producer != $origproducer) {
   if ($change > 0) {
      $update .= ", producer=\"$producer\"";
   } else {
      $update .= " producer=\"$producer\"";
   }
   $change = $change + 1;
}

if ($vineyard == "New") {
   if ($change > 0) {
      $update .= ", vineyard=\"$vineyardnew\"";
   } else {
      $update .= " vineyard=\"$vineyardnew\"";
   }
   $change = $change + 1;
} else if ($vineyard != $origvineyard) {
   if ($change > 0) {
      $update .= ", vineyard=\"$vineyard\"";
   } else {
      $update .= " vineyard=\"$vineyard\"";
   }
   $change = $change + 1;
}

if ($varietal == "New") {
   if ($change > 0) {
      $update .= ", varietal=\"$varietalnew\"";
   } else {
      $update .= " varietal=\"$varietalnew\"";
   }
   $change = $change + 1;
} else if ($varietal != $origvarietal) {
   if ($change > 0) {
      $update .= ", varietal=\"$varietal\"";
   } else {
      $update .= " varietal=\"$varietal\"";
   }
   $change = $change + 1;
}

if ($region == "New") {
   if ($change > 0) {
      $update .= ", region=\"$regionnew\"";
   } else {
      $update .= " region=\"$regionnew\"";
   }
   $change = $change + 1;
} else if ($region != $origregion) {
   if ($change > 0) {
      $update .= ", region=\"$region\"";
   } else {
      $update .= " region=\"$region\"";
   }
   $change = $change + 1;
}

if ($country != $origcountry) {
   if ($change > 0) {
      $update .= ", country=\"$country\"";
   } else {
      $update .= " country=\"$country\"";
   }
   $change = $change + 1;
}

if ($datecellared != $origdatecellared) {
   if ($change > 0) {
      $update .= ", datecellared='$datecellared'";
   } else {
      $update .= " datecellared='$datecellared'";
   }
   $change = $change + 1;
}

if ($beginconsume != $origbeginconsume) {
   if ($change > 0) {
      $update .= ", beginconsume='$beginconsume'";
   } else {
      $update .= " beginconsume='$beginconsume'";
   }
   $change = $change + 1;
}

if ($consumeby != $origconsumeby) {
   if ($change > 0) {
      $update .= ", consumeby='$consumeby'";
   } else {
      $update .= " consumeby='$consumeby'";
   }
   $change = $change + 1;
}

if ($store == "New") {
   if ($change > 0) {
      $update .= ", store=\"$storenew\"";
   } else {
      $update .= " store=\"$storenew\"";
   }
   $change = $change + 1;
} else if ($store != $origstore) {
   if ($change > 0) {
      $update .= ", store=\"$store\"";
   } else {
      $update .= " store=\"$store\"";
   }
   $change = $change + 1;
}

if ($price != $origprice) {
   if ($change > 0) {
      $update .= ", price=\"$price\"";
   } else {
      $update .= " price=\"$price\"";
   }
   $change = $change + 1;
}

if ($quantity != $origquantity) {
   if ($change > 0) {
      $update .= ", quantity=$quantity";
   } else {
      $update .= " quantity=$quantity";
   }
   $change = $change + 1;
}

if ($appellation == "New") {
   if ($change > 0) {
      $update .= ", appellation=\"$appellationnew\"";
   } else {
      $update .= " appellation=\"$appellationnew\"";
   }
   $change = $change + 1;
} else if ($appellation != $origappellation) {
   if ($change > 0) {
      $update .= ", appellation=\"$appellation\"";
   } else {
      $update .= " appellation=\"$appellation\"";
   }
   $change = $change + 1;
}

if ($size != $origsize) {
   if ($change > 0) {
      $update .= ", size=\"$size\"";
   } else {
      $update .= " size=\"$size\"";
   }
   $change = $change + 1;
}

if (($score1 != $origscore1) || (!$score1)) {
   if ($change > 0) {
      if (!$score1) {
	 $update .= ", score1=NULL";
      } else {
	 $update .= ", score1=$score1";
      }
   } else {
      if (!$score1) {
	 $update .= " score1=NULL";
      } else {
	 $update .= " score1=$score1";
      }
   }
   $change = $change + 1;
}

if (($score2 != $origscore2) || (!$score2)) {
   if ($change > 0) {
      if (!$score2) {
         $update .= ", score2=NULL";
      } else {
         $update .= ", score2=$score2";
      }
   } else {
      if (!$score2) {
         $update .= " score2=NULL";
      } else {
         $update .= " score2=$score2";
      }
   }
   $change = $change + 1;
}

if (($score3 != $origscore3) || (!$score3)) {
   if ($change > 0) {
      if (!$score3) {
         $update .= ", score3=NULL";
      } else {
         $update .= ", score3=$score3";
      }
   } else {
      if (!$score3) {
         $update .= " score3=NULL";
      } else {
         $update .= " score3=$score3";
      }
   }
   $change = $change + 1;
}

if ($notes != $orignotes) {
   $updatenotes = "UPDATE notes SET usernotes=\"$notes\" WHERE id=$id";
}

$update .= " WHERE id=$id";

// Submit update request

if ($change > 0) {
   $result = @mysql_query($update);
}

if ($updatenotes) {
   $updatenotesresult = @mysql_query($updatenotes);
}

// View results (if any)

if ($result || $updatenotesresult) {

// ----------------------------------------------------------------------------
print <<<EOF
   <font face="arial,helvetica" size=2>
      Your update request has been completed successfully and the
	 server returned the following results...
   </font>
   <font size=1><br><br></font>
   <table border=0 cellpadding=2 cellspacing=2>
      <tr bgcolor="#808080">
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Vintage&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Producer&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Vineyard&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Varietal&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Appellation&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Quan&nbsp;&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2
	    color="#ffffff">Details&nbsp;&nbsp;</font></td>
      </tr>
EOF;
// ----------------------------------------------------------------------------

   $newquery = "SELECT * FROM bottle WHERE id=$id";
   $newresult = @mysql_query($newquery);

   $billygoat = 0;

   while ($newrow = @mysql_fetch_row($newresult)) {

      if (($billygoat % 2) == 0) {
	 $color = "aliceblue";
      } else {
	 $color = "#eeeeee";
      }

      $billygoat++;

      if ($newrow[2] == "1001") {
	 $newrow[2] = "NV";
      }

// ----------------------------------------------------------------------------
print <<<EOF
      <tr bgcolor="$color">
         <td><font face="arial,helvetica" size=2>$newrow[2]</font></td>
         <td><font face="arial,helvetica" size=2>$newrow[3]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$newrow[4]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$newrow[5]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$newrow[14]&nbsp;</font></td>
         <td><font face="arial,helvetica" size=2>$newrow[13]&nbsp;</font></td>
         <td><a href="details.php?id=$newrow[0]"><font face="arial,helvetica"
	    size=2>View</font></a></td>
      </tr>
EOF;
// ----------------------------------------------------------------------------

      // Generate transaction log entry

      $day = trim(`/bin/date +%A`);
      $month = trim(`/bin/date +%B`);
      $year = trim(`/bin/date +%Y`);
      $time = trim(`/bin/date`);

      $logupdate = "INSERT INTO translog VALUES (NULL, $row[0], '$time', ";
      $logupdate .= "'Update','$day', '$month', $year, '$WIMS_UNAME')";
      $logresult = @mysql_query($logupdate);

      if (!$logresult) {
	 logError();
      }
   }

   if ($logresult) {
      print "</table>\n";
   }

} else if ($change != 0) {
   serverError();
} else if ($change == 0) {
   updateError();
}

print "<font size=1><br></font>";

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
