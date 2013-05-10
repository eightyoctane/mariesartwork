<?php
include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
include_once($rootlocation.'logFileClass.php');
include("db.php");
$lf = new logFile();
$lf->logMessage("delete_update.php: Started hello!\n");
$u_id = $_GET['u_id'];
$lf->logMessage("delete_update.php: u_id: ".$u_id."\n");
if($_POST['msg_id'])
{
$id=$_POST['msg_id'];
$id = mysql_escape_String($id);
$sql = "delete from messages where msg_id='$id'";
mysql_query( $sql);
}
?>