<?php require_once "includes/connect.php"; 
mysql_select_db($database_localhost, $localhost);
$item_desc = addslashes(ereg_replace("(\r\n|\n|\r)", "<br />", $_POST['Description']));

$rew = "UPDATE ".$items_table." SET item_desc='".$item_desc."' WHERE id = ".$_POST['ItemId'];
$Rsrew = mysql_query($rew, $localhost) or die(mysql_error());

$rew2 = "DELETE FROM ".$tags_table." WHERE id_item = ".$_POST['ItemId'];
$Rsrew2 = mysql_query($rew2, $localhost) or die(mysql_error());

$item_tags = addslashes($_POST['Tags']);
$item_tags = explode(",", $item_tags);
foreach($item_tags as $item)
{	

	$rew1 = "INSERT INTO ".$tags_table." (tags_tags,id_item,item_status) values ('".trim($item)."',". $_POST['ItemId'] .",". $_POST['ItemStatus'] .")";
$Rsrew1 = mysql_query($rew1, $localhost) or die(mysql_error());
}


header("Location:index.php");
?>