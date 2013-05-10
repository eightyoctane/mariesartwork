<?php require_once "includes/connect.php"; 
mysql_select_db($database_localhost, $localhost);

$rew = "UPDATE ".$items_table." SET item_status=2 WHERE id = ".$_REQUEST['ItemId'];
$Rsrew = mysql_query($rew, $localhost) or die(mysql_error());

$rew = "UPDATE ".$tags_table." SET item_status=2 WHERE id_item = ".$_REQUEST['ItemId'];
$Rsrew = mysql_query($rew, $localhost) or die(mysql_error());

Header("Location:index.php");
?>