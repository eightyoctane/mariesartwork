<?php require_once "includes/connect.php"; 
mysql_select_db($database_localhost, $localhost);

$rew = "Delete From ".$items_table." where id = ". $_REQUEST['Id'];
$Rsrew = mysql_query($rew, $localhost) or die(mysql_error());

$rew2 = "Delete From ".$tags_table." where id_item = ". $_REQUEST['Id'];
$Rsrew2 = mysql_query($rew2, $localhost) or die(mysql_error());

Header("Location:index.php");
?>