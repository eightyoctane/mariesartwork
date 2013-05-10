
<?php
include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
include_once($rootlocation.'logFileClass.php');

include("db.php");

$lf = new logFile();
$lf->logMessage("delete_comment.php: Started\n");

if($_POST['com_id'])
{
$id=$_POST['com_id'];
$id = mysql_escape_String($id);
$sql = "delete from comments where com_id='$id'";
mysql_query( $sql);
}
?>