
<?php
 
//session_start();
include_once($_SERVER['DOCUMENT_ROOT']."/homedata.php");
include("db.php");

include_once($rootlocation."logFileClass.php");
$lf = new logFile();

if(isSet($_POST['comment_content'])) {


	
	$id=time();// Demo Use
	$u_id = $_POST['u_id'];
	$comment=$_POST['comment_content'];
	$msg_id=$_POST['msg_id'];

	$lf->logMessage("comment_ajax.php: Updating comments table u_id:".$u_id." comment: ".$comment." msg_id: ".$msg_id."\n");	
	
	$sql=mysql_query("insert into comments(dtandtime,u_id,comment,msg_id_fk)values('".date("Y-m-d H:i:s")."','$u_id','$comment','$msg_id')");
	//$sql=mysql_query("insert into comments(comment,msg_id_fk)values('$comment','$msg_id')");	

	$selectstring = "select comments.dtandtime as dtandtime, comments.com_id as com_id, comments.comment as comment, user.name as name from user, comments where comments.u_id = user.rowID order by com_id desc";
	$result=mysql_query($selectstring);
	
	//$result=mysql_query("select * from comments order by com_id desc");

	$row=mysql_fetch_array($result);
	$id=$row['com_id'];
	$comment=$row['comment'];
	$username = $row['name'];
	$dtandtime = $row['dtandtime'];

	//$u_idg=$row['u_id'];	
	//$result2 = mysql_query("select * from user where rowID = '$u_idg')");
	//$row=mysql_fetch_array($result);	
	//$username = $row['username'];
}


?>

 <div class="comment_load" id="comment<?php echo $id; ?>">
 <?php echo $dtandtime." ".$username.": ".$comment;  ?>
 <span class="cdelete_button"><a href="#" id="<?php echo $id; ?>" class="cdelete_update">X</a></span>
 </div>
