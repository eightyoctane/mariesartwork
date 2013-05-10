 <?php
 //Srinivas Tamada http://9lessons.info
//Load latest update 
error_reporting(0);
include_once 'includes/db.php';
include_once 'includes/functions.php';
include_once 'includes/tolink.php';
include_once 'includes/time_stamp.php';
include_once 'session.php';
$Wall = new Wall_Updates();
if(isSet($_POST['msg_id']))
{
$msg_id=$_POST['msg_id'];
$data=$Wall->Delete_Update($uid,$msg_id);
echo $data;

}
?>
