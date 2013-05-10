<?php
$mysql_hostname = "localhost";
$mysql_user = "artmarie_user";
$mysql_password = "1200";
$mysql_database = "artmarie_bmasitevisitors";
$prefix = "";
$bd = mysql_connect($mysql_hostname, $mysql_user, $mysql_password) or die("Opps some thing went wrong");
mysql_select_db($mysql_database, $bd) or die("Opps some thing went wrong");

?>
