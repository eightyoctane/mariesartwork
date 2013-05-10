<?php

//Make the connection to the database
$conn = mysql_connect($system,$dbuser,$dbpwd);
if (!$conn)
{
  // the connection failed so quit the script
  die("Could not connect !");
}
//Select which database you want to use.
$db = mysql_select_db($database);
if (!$db)
{
  // cannot connect to the database so quit the script
  die("Could not connect to database!");
}
?>
