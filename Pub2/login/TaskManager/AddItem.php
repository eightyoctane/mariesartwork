<?php require_once "includes/connect.php"; 
mysql_select_db($database_localhost, $localhost);
$item_desc = addslashes(ereg_replace("(\r\n|\n|\r)", "<br />", $_POST['Description']));

$rew = "INSERT INTO ".$items_table." (item_desc,item_tags,item_added,item_status) values ('".$item_desc."','".$item_tags."', '".date("F j, Y, g:i a")."', 1)";
$Rsrew = mysql_query($rew, $localhost) or die(mysql_error());
$id = mysql_insert_id();
$item_tags = addslashes($_POST['Tags']);
$item_tags = explode(",", $item_tags);
foreach($item_tags as $item)
{	

	$rew1 = "INSERT INTO ".$tags_table." (tags_tags,id_item,item_status) values ('".trim($item)."',". $id.", 1)";
$Rsrew1 = mysql_query($rew1, $localhost) or die(mysql_error());
}

Header("Location:index.php");
?>