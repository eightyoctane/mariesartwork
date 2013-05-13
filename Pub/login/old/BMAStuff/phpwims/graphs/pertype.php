<?php

// ----------------------------------------------------------------------------
// Name: pertype.php
// Author: R Sean Eidemiller (mojo@halcyon.cc)
// Last Modified: January 24, 2003
// ----------------------------------------------------------------------------

include("../include/jpgraph.php");
include("../include/jpgraph_bar.php");
include("../include/sqlinfo.php");

// Connect to the database

$link = @mysql_connect("$sql_hostname", "$sql_username", "$sql_password")
   or die("ERROR: Could not connect to server");

@mysql_select_db("$sql_dbname")
   or die("ERROR: Could not connect to database");

// Generate query and obtain results

$query = "SELECT DISTINCT type FROM bottle ";
$query .= "WHERE quantity>0 ORDER BY type";
$result = @mysql_query($query);

// Find total number of bottles

$total = 0;

$totquery = "SELECT * FROM bottle";
$totresult = @mysql_query($totquery);

while ($totrow = @mysql_fetch_row($totresult)) {
   global $total;
   $total = $total + $totrow[13];
}

// Calculate percentage and range

$range = array();
$percent = array();

while ($row = @mysql_fetch_row($result)) {
   
   $newquery = "SELECT * FROM bottle WHERE type=\"$row[0]\"";
   $newresult = @mysql_query($newquery);

   $num = 0;

   while ($newrow = @mysql_fetch_row($newresult)) {
      $num = $num + $newrow[13];
   }

   global $range, $percent, $total;

   $range[] = $row[0];
   $percent[] = (round(100 * ($num / $total)));
}

$i = 0;

// Generate the graph itself

$graph = new Graph(320,260);
$graph->SetScale("textlin");

$bplot = new BarPlot($percent);
$bplot->SetFillColor("slategray2");

$graph->img->SetMargin(35,30,30,80);
$graph->title->Set("Percentage (%) of Bottles per Type");
$graph->xaxis->SetTickLabels($range);
$graph->xaxis->SetLabelAngle(90);
$graph->SetShadow();
$graph->Add($bplot);
$graph->Stroke();

mysql_close($link);

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
