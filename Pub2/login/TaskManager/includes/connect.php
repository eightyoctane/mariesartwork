<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_localhost = "localhost";
$database_localhost = "artmarie_ToDo";
$username_localhost = "artmarie_user";
$password_localhost = "1200";
/*$hostname_localhost = "mysqlblade1.starcomhosting.com";
$database_localhost = "bluemov2_ToDo";
$username_localhost = "bluemov2_vadmin";
$password_localhost = "1200";
*/
$items_table = "tdl_items";
$tags_table = "tdl_tags";
$localhost = mysql_pconnect($hostname_localhost, $username_localhost, $password_localhost) or die(mysql_error());

?>
