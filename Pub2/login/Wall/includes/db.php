<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'artmarie_user');
define('DB_PASSWORD', '1200');
define('DB_DATABASE', 'artmarie_visitors');
$connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
$database = mysql_select_db(DB_DATABASE) or die(mysql_error());
?>
