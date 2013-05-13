<?php

// ----------------------------------------------------------------------------
// Name: generate.php
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
<body bgcolor="#ffffff">
   <table border=0 cellpadding=3 cellspacing=0 width="100%">
      <tr>
         <td bgcolor="#808080">
            <font face="arial,helvetica" size=2 color="#ffffff">
	       <b><u>Report Generation Results</u></b>
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

// Obtain total number of bottles

$total = 0;

$query = "SELECT * FROM bottle";
$result = @mysql_query($query);

while ($row = @mysql_fetch_row($result)) {
   $total = $total + $row[13];
}

// Make sure all fields were filled out correctly

if (!$report) {
   print "<font face='arial,helvetica' size=2 color='#ff0000'>ERROR: ";
   print "Dude, you have to choose one of the two report types for the ";
   print "server to generate.</font><br>\n";
}

// Determine report type (to be generated)

if ($report == "count") {

   if ($quanper == "Vintage") {

// ----------------------------------------------------------------------------
print <<<EOF
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
	       Your report was generated successfully and the server returned
		  the following results...
	    </font>
         </td>
      </tr>
   </table>
   <br>
   <table cellpadding=0 cellspacing=0 border=0>
      <tr>
	 <td>
            &nbsp;
         </td>
	 <td valign="middle">
	    <img src="graphs/quanvin.php">
	 </td>
	 <td>
	    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	 </td>
	 <td valign="middle">
	    <img src="graphs/pervin.php">
	 </td>
      </tr>
   </table>
   <font size=1><br></font>
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
	       <u>Total</u> number of bottles currently getting their groove on
		  in your wine cellar: <b>$total</b>
	    </font>
         </td>
      </tr>
   </table>
EOF;
// ----------------------------------------------------------------------------

   } else if ($quanper == "Type") {

// ----------------------------------------------------------------------------
print <<<EOF
    <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
	 <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
	       Your report was generated successfully and the server returned
		  the following results...
	    </font>
	 </td>
      </tr>
   </table>
   <br>
   <table cellpadding=0 cellspacing=0 border=0>
      <tr>
	 <td>
	    &nbsp;
	 </td>
         <td valign="middle">
            <img src="graphs/quantype.php">
         </td>
         <td>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         </td>
         <td valign="middle">
            <img src="graphs/pertype.php">
         </td>
      </tr>
   </table>
   <font size=1><br></font>
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
               <u>Total</u> number of bottles currently getting their groove on
                  in your wine cellar: <b>$total</b>
            </font>
         </td>
      </tr>
   </table>
EOF;
// ----------------------------------------------------------------------------

   } else if ($quanper == "Producer") {

// ----------------------------------------------------------------------------
print <<<EOF
   <font face="arial,helvetica" size=2>
      Your report was generated successfully and the server returned the
	 following results...
   </font>
   <br><br>
   <table cellpadding=2 cellspacing=2 border=0>
      <tr bgcolor="#808080">
	 <td><font face="arial,helevetica" size=2
	    color="#ffffff">Producer&nbsp;&nbsp;</font></td>
	 <td><font face="arial,helevetica" size=2
	    color="#ffffff">Quantity&nbsp;&nbsp;</font></td>
         <td><font face="arial,helevetica" size=2
	    color="#ffffff">Percentage&nbsp;&nbsp;</font></td>
         <td><font face="arial,helevetica" size=2
	    color="#ffffff">Details&nbsp;&nbsp;</font></td>
      </tr>
EOF;
// ----------------------------------------------------------------------------

      $query = "SELECT DISTINCT producer FROM bottle ";
      $query .= " WHERE quantity>0 ORDER BY producer";
      $result = @mysql_query($query);

      $batman = 0;

      while ($row = @mysql_fetch_row($result)) {

	 if (($batman % 2) == 0) {
	    $color = "aliceblue";
	 } else {
	    $color = "#eeeeee";
	 }

	 $batman++;

         print "<tr bgcolor='$color'>\n";
	 print "<td><font face='arial,helvetica' ";
	 print "size=2>$row[0]&nbsp;&nbsp;&nbsp;&nbsp;</font></td>\n";

         $newquery = "SELECT * FROM bottle WHERE producer=\"$row[0]\"";
         $newresult = @mysql_query($newquery);

         $num = 0;

         while ($newrow = @mysql_fetch_row($newresult)) {
            $num = $num + $newrow[13];
         }

         $percent = round(100 * ($num / $total));

         print "<td><font face='arial,helvetica' ";
	 print "size=2>$num&nbsp;&nbsp;&nbsp;&nbsp;</font></td>\n";
         print "<td><font face='arial,helvetica' ";
	 print "size=2>$percent%&nbsp;&nbsp;</font></td>\n";
         print "<td><a href='countdetails.php?producer=$row[0]'>";
	 print "<font face='arial,helvetica' size=2>View</font></a>";
	 print "<font size=2>&nbsp;&nbsp;</font></td></tr>\n";
      }

// ----------------------------------------------------------------------------
print <<<EOF
   </table>
   <br>
   <font face="arial,helvetica" size=2>
      <u>Total</u> number of bottles currently getting their groove on in your
	 wine cellar: <b>$total</b>
   </font>
   <font size=1><br></font>
EOF;
// ----------------------------------------------------------------------------

   } else if ($quanper == "Varietal") {

// ----------------------------------------------------------------------------
print <<<EOF
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
               Your report was generated successfully and the server returned
                  the following results...
            </font>
         </td>
      </tr>
   </table>
   <br>
   &nbsp;<img src="graphs/quanvar.php">
   <font size=1><br><br><br></font>
   &nbsp;<img src="graphs/pervar.php">
   <font size=1><br><br></font>
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
               <u>Total</u> number of bottles currently getting their groove on
                  in your wine cellar: <b>$total</b>
            </font>
         </td>
      </tr>
   </table>
EOF;
// ----------------------------------------------------------------------------

   } else if ($quanper == "Region") {

      $regquery = "SELECT DISTINCT region FROM bottle WHERE ";
      $regquery .= "quantity>0 ORDER BY region";
      $regresult = @mysql_query($regquery);

      if ((@mysql_affected_rows($link)) <= 40) {

// ----------------------------------------------------------------------------
print <<<EOF
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
               Your report was generated successfully and the server returned
                  the following results...
            </font>
         </td>
      </tr>
   </table>
   <br>
   &nbsp;<img src="graphs/quanreg.php">
   <font size=1><br><br><br></font>
   &nbsp;<img src="graphs/perreg.php">
   <font size=1><br><br></font>
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
               <u>Total</u> number of bottles currently getting their groove on
                  in your wine cellar: <b>$total</b>
            </font>
         </td>
      </tr>
   </table>
EOF;
// ----------------------------------------------------------------------------

      } else {

// ----------------------------------------------------------------------------
print <<<EOF
   <font face="arial,helvetica" size=2>
      Your report was generated successfully and the server returned the
	 following results...
   </font>
   <br><br>
   <table cellpadding=2 cellspacing=2 border=0>
      <tr bgcolor="#808080">
	 <td><font face="arial,helevetica" size=2
            color="#ffffff">Region&nbsp;&nbsp;</font></td>
         <td><font face="arial,helevetica" size=2
            color="#ffffff">Quantity&nbsp;&nbsp;</font></td>
         <td><font face="arial,helevetica" size=2
            color="#ffffff">Percentage&nbsp;&nbsp;</font></td>
         <td><font face="arial,helevetica" size=2
            color="#ffffff">Details&nbsp;&nbsp;</font></td>
      </tr>
EOF;
// ----------------------------------------------------------------------------

	 $query = "SELECT DISTINCT region FROM bottle ";
	 $query .= " WHERE quantity>0 ORDER BY region";
	 $result = @mysql_query($query);

	 $batman = 0;

	 while ($row = @mysql_fetch_row($result)) {

	    if (($batman % 2) == 0) {
	       $color = "aliceblue";
	    } else {
	       $color = "#eeeeee";
	    }

	    $batman++;

	    print "<tr bgcolor='$color'>\n";
	    print "<td><font face='arial,helvetica' ";
	    print "size=2>$row[0]&nbsp;&nbsp;&nbsp;&nbsp;</font></td>\n";

	    $newquery = "SELECT * FROM bottle WHERE region=\"$row[0]\"";
	    $newresult = @mysql_query($newquery);

	    $num = 0;

	    while ($newrow = @mysql_fetch_row($newresult)) {
	       $num = $num + $newrow[13];
	    }

	    $percent = round(100 * ($num / $total));

	    print "<td><font face='arial,helvetica' ";
	    print "size=2>$num&nbsp;&nbsp;&nbsp;&nbsp;</font></td>\n";
	    print "<td><font face='arial,helvetica' ";
	    print "size=2>$percent%&nbsp;&nbsp;</font></td>\n";
	    print "<td><a href='countdetails.php?region=$row[0]'>";
	    print "<font face='arial,helvetica' size=2>View</font></a>";
	    print "<font size=2>&nbsp;&nbsp;</font></td></tr>\n";
	 }

// ----------------------------------------------------------------------------
print <<<EOF
   </table>
   <br>
   <font face="arial,helvetica" size=2>
      <u>Total</u> number of bottles currently getting their groove on in your
	 wine cellar: <b>$total</b>
   </font>
   <font size=1><br></font>
EOF;
// ----------------------------------------------------------------------------

      }
   } else if ($quanper == "Country") {

// ----------------------------------------------------------------------------
print <<<EOF
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
               Your report was generated successfully and the server returned
                  the following results...
            </font>
         </td>
      </tr>
   </table>
   <br>
   <table cellpadding=0 cellspacing=0 border=0>
      <tr>
         <td>
            &nbsp;
         </td>
         <td valign="middle">
            <img src="graphs/quancoun.php">
         </td>
         <td>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         </td>
         <td valign="middle">
            <img src="graphs/percoun.php">
         </td>
      </tr>
   </table>
   <font size=1><br></font>
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
               <u>Total</u> number of bottles currently getting their groove on
                  in your wine cellar: <b>$total</b>
            </font>
         </td>
      </tr>
   </table>
EOF;
// ----------------------------------------------------------------------------

   } else if ($quanper == "Price") {

// ----------------------------------------------------------------------------
print <<<EOF
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
               Your report was generated successfully and the server returned
                  the following results...
            </font>
         </td>
      </tr>
   </table>
   <br>
   <table cellpadding=0 cellspacing=0 border=0>
      <tr>
         <td>
            &nbsp;
         </td>
         <td valign="middle">
            <img src="graphs/quanprice.php">
         </td>
         <td>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         </td>
         <td valign="middle">
            <img src="graphs/perprice.php">
         </td>
      </tr>
   </table>
   <font size=1><br></font>
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
               <u>Total</u> number of bottles currently getting their groove on
                  in your wine cellar: <b>$total</b>
            </font>
         </td>
      </tr>
   </table>
EOF;
// ----------------------------------------------------------------------------

   } else if ($quanper == "Appellation") {

// ----------------------------------------------------------------------------
print <<<EOF
   <font face="arial,helvetica" size=2>
      Your report was generated successfully and the server returned the
	 following results...
   </font>
   <br><br>
   <table cellpadding=2 cellspacing=2 border=0>
      <tr bgcolor="#808080">
	 <td><font face="arial,helevetica" size=2
            color="#ffffff">Appellation&nbsp;&nbsp;</font></td>
         <td><font face="arial,helevetica" size=2
            color="#ffffff">Quantity&nbsp;&nbsp;</font></td>
         <td><font face="arial,helevetica" size=2
            color="#ffffff">Percentage&nbsp;&nbsp;</font></td>
         <td><font face="arial,helevetica" size=2
            color="#ffffff">Details&nbsp;&nbsp;</font></td>
      </tr>
EOF;
// ----------------------------------------------------------------------------

      $query = "SELECT DISTINCT appellation FROM bottle ";
      $query .= " WHERE quantity>0 ORDER BY appellation";
      $result = @mysql_query($query);

      $batman = 0;

      while ($row = @mysql_fetch_row($result)) {

         if (($batman % 2) == 0) {
            $color = "aliceblue";
         } else {
            $color = "#eeeeee";
         }

         $batman++;

	 print "<tr bgcolor='$color'>\n";
         print "<td><font face='arial,helvetica' ";
         print "size=2>$row[0]&nbsp;&nbsp;&nbsp;&nbsp;</font></td>\n";

         $newquery = "SELECT * FROM bottle WHERE appellation=\"$row[0]\"";
         $newresult = @mysql_query($newquery);

         $num = 0;

         while ($newrow = @mysql_fetch_row($newresult)) {
            $num = $num + $newrow[13];
         }

         $percent = round(100 * ($num / $total));

	 print "<td><font face='arial,helvetica' ";
         print "size=2>$num&nbsp;&nbsp;&nbsp;&nbsp;</font></td>\n";
         print "<td><font face='arial,helvetica' ";
         print "size=2>$percent%&nbsp;&nbsp;</font></td>\n";
         print "<td><a href='countdetails.php?appellation=$row[0]'>";
         print "<font face='arial,helvetica' size=2>View</font></a>";
         print "<font size=2>&nbsp;&nbsp;</font></td></tr>\n";
      }

// ----------------------------------------------------------------------------
print <<<EOF
   </table>
   <br>
   <font face="arial,helvetica" size=2>
      <u>Total</u> number of bottles currently getting their groove on in your
	 wine cellar: <b>$total</b>
   </font>
   <font size=1><br></font>
EOF;
// ----------------------------------------------------------------------------

   } else if ($quanper == "Bottle Size") {

// ----------------------------------------------------------------------------
print <<<EOF
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
               Your report was generated successfully and the server returned
                  the following results...
            </font>
         </td>
      </tr>
   </table>
   <br>
   <table cellpadding=0 cellspacing=0 border=0>
      <tr>
         <td>
            &nbsp;
         </td>
         <td valign="middle">
            <img src="graphs/quansize.php">
         </td>
         <td>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         </td>
         <td valign="middle">
            <img src="graphs/persize.php">
         </td>
      </tr>
   </table>
   <font size=1><br></font>
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
               <u>Total</u> number of bottles currently getting their groove on
                  in your wine cellar: <b>$total</b>
            </font>
         </td>
      </tr>
   </table>
EOF;
// ----------------------------------------------------------------------------

   }
} else if ($report == "ready") {

   if ($year != "Year") {
      $totalquery = "SELECT * FROM bottle WHERE beginconsume=$year";
      $totalresult = @mysql_query($totalquery);

      $total = 0;

      while ($totrow = @mysql_fetch_row($totalresult)) {
         $total = $total + $totrow[13];
      }
   }

   if (($year != "Year") && ($readyper == "Type")) {

// ----------------------------------------------------------------------------
print <<<EOF
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
               Your report was generated successfully and the server returned
                  the following results...
            </font>
         </td>
      </tr>
   </table>
   <br>
   <table cellpadding=0 cellspacing=0 border=0>
      <tr>
         <td>
            &nbsp;
         </td>
         <td valign="middle">
            <img src="graphs/rqtype.php?year=$year">
         </td>
         <td>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         </td>
         <td valign="middle">
            <img src="graphs/rptype.php?year=$year">
         </td>
      </tr>
   </table>
   <font size=1><br></font>
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
               <u>Total</u> number of bottles in your cellar that will be
                  entering maturity in the year $year: <b>$total</b>
            </font>
         </td>
      </tr>
   </table>
EOF;
// ----------------------------------------------------------------------------

   } else if (($year != "Year") && ($readyper == "Varietal")) {

// ----------------------------------------------------------------------------
print <<<EOF
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
               Your report was generated successfully and the server returned
                  the following results...
            </font>
         </td>
      </tr>
   </table>
   <br>
   &nbsp;<img src="graphs/rqvar.php?year=$year">
   <font size=1><br><br><br></font>
   &nbsp;<img src="graphs/rpvar.php?year=$year">
   <br><br>
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
	       <u>Total</u> number of bottles in your cellar that will be
		  entering maturity in the year $year: <b>$total</b>
	    </font>
         </td>
      </tr>
   </table>
EOF;
// ----------------------------------------------------------------------------

   } else if (($year != "Year") && ($readyper == "Appellation")) {

// ----------------------------------------------------------------------------
print <<<EOF
   <font face="arial,helvetica" size=2>
      Your report was generated successfully and the server returned the
	 following results...
   </font>
   <br><br>
   <table cellpadding=2 cellspacing=2 border=0>
      <tr bgcolor="#808080">
	 <td><font face="arial,helevetica" size=2
            color="#ffffff">Appellation&nbsp;&nbsp;</font></td>
         <td><font face="arial,helevetica" size=2
            color="#ffffff">Quantity&nbsp;&nbsp;</font></td>
         <td><font face="arial,helevetica" size=2
            color="#ffffff">Percentage&nbsp;&nbsp;</font></td>
         <td><font face="arial,helevetica" size=2
            color="#ffffff">Details&nbsp;&nbsp;</font></td>
      </tr>
EOF;
// ----------------------------------------------------------------------------

      $query = "SELECT DISTINCT appellation FROM bottle WHERE";
      $query .= " beginconsume=$year AND quantity>0 ORDER BY appellation";
      $result = @mysql_query($query);

      $batman = 0;

      while ($row = @mysql_fetch_row($result)) {

         if (($batman % 2) == 0) {
            $color = "aliceblue";
         } else {
            $color = "#eeeeee";
         }

         $batman++;

	 print "<tr bgcolor='$color'>\n";
         print "<td><font face='arial,helvetica' ";
         print "size=2>$row[0]&nbsp;&nbsp;&nbsp;&nbsp;</font></td>\n";

         $newquery = "SELECT * FROM bottle WHERE appellation=\"$row[0]\"";
         $newquery .= " AND quantity>0 AND beginconsume=$year";
         $newresult = @mysql_query($newquery);

         $num = 0;

         while ($newrow = @mysql_fetch_row($newresult)) {
            $num = $num + $newrow[13];
         }

         $percent = round(100 * ($num / $total));

	 print "<td><font face='arial,helvetica' ";
         print "size=2>$num&nbsp;&nbsp;&nbsp;&nbsp;</font></td>\n";
         print "<td><font face='arial,helvetica' ";
         print "size=2>$percent%&nbsp;&nbsp;</font></td>\n";
         print "<td><a href='readydetails.php?appellation=$row[0]&year=$year'>";
         print "<font face='arial,helvetica' size=2>View</font></a>";
         print "<font size=2>&nbsp;&nbsp;</font></td></tr>\n";
      }

// ----------------------------------------------------------------------------
print <<<EOF
   </table>
   <br>
   <font face="arial,helvetica" size=2>
      <u>Total</u> number of bottles in your cellar that will be entering
	 maturity in the year $year: <b>$total</b>
   </font>
   <font size=1><br></font>
EOF;
// ----------------------------------------------------------------------------

   } else if (($year != "Year") && ($readyper == "Bottle Size")) {

// ----------------------------------------------------------------------------
print <<<EOF
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
               Your report was generated successfully and the server returned
                  the following results...
            </font>
         </td>
      </tr>
   </table>
   <br>
   <table cellpadding=0 cellspacing=0 border=0>
      <tr>
         <td>
            &nbsp;
         </td>
         <td valign="middle">
            <img src="graphs/rqsize.php?year=$year">
         </td>
         <td>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         </td>
         <td valign="middle">
            <img src="graphs/rpsize.php?year=$year">
         </td>
      </tr>
   </table>
   <font size=1><br></font>
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
               <u>Total</u> number of bottles in your cellar that will be
                  entering maturity in the year $year: <b>$total</b>
            </font>
         </td>
      </tr>
   </table>
EOF;
// ----------------------------------------------------------------------------

   } else if (($year != "Year") && ($readyper == "Price")) {

// ----------------------------------------------------------------------------
print <<<EOF
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
               Your report was generated successfully and the server returned
                  the following results...
            </font>
         </td>
      </tr>
   </table>
   <br>
   <table cellpadding=0 cellspacing=0 border=0>
      <tr>
         <td>
            &nbsp;
         </td>
         <td valign="middle">
            <img src="graphs/rqprice.php?year=$year">
         </td>
         <td>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         </td>
         <td valign="middle">
            <img src="graphs/rpprice.php?year=$year">
         </td>
      </tr>
   </table>
   <font size=1><br></font>
   <table border=0 cellspacing=0 cellpadding=1 width="100%">
      <tr>
         <td bgcolor="#eeeeee">
	    <font face="arial,helvetica" size=2>
               <u>Total</u> number of bottles in your cellar that will be
                  entering maturity in the year $year: <b>$total</b>
            </font>
         </td>
      </tr>
   </table>
EOF;
// ----------------------------------------------------------------------------

   } else {
      serverError();
   }

} else if ($report == "value") {
   $bottle_count = 0;
   $total_value = 0;

   $value_query = "SELECT price, quantity FROM bottle"; 
   $value_result = @mysql_query($value_query);

   if ($value_result) {
      while ($value_row = @mysql_fetch_row($value_result)) {
	 if ($value_row[0] == "$0 - $10") {
	    $total_value += (8 * $value_row[1]);
	 } else if ($value_row[0] == "$10 - $20") {
	    $total_value += (15 * $value_row[1]);
	 } else if ($value_row[0] == "$20 - $30") {
	    $total_value += (25 * $value_row[1]);
	 } else if ($value_row[0] == "$30 - $50") {
	    $total_value += (40 * $value_row[1]);
	 } else if ($value_row[0] == "$50 - $100") {
	    $total_value += (75 * $value_row[1]);
	 } else if ($value_row[0] == "$100+") {
	    $total_value += (150 * $value_row[1]);
	 }

	 $bottle_count += $value_row[1];
      }

// ----------------------------------------------------------------------------
print <<<EOF
   <font face="arial,helvetica" size=2>
      Total number of bottles currently residing in your cellar:&nbsp;
	 <b>$bottle_count</b>
      <br>
      Approximate total value of your cellar (based on purchase price):&nbsp;
	 <b>&#36;$total_value</b>
      <br>
   </font>
EOF;
// ----------------------------------------------------------------------------

   } else {
      serverError();
   }
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
