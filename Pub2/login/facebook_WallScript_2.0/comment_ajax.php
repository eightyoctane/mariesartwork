
<?php
 
//session_start();
include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
include("db.php");

include_once($rootlocation."logFileClass.php");
$lf = new logFile();

if(isSet($_POST['comment_content'])) {


	
	$id=time();// Demo Use
	$u_id = $_POST['u_id'];
	$comment=$_POST['comment_content'];
	$msg_id=$_POST['msg_id'];

	$lf->logMessage("comment_ajax.php: Updating comments table u_id:".$u_id." comment: ".$comment." msg_id: ".$msg_id."\n");	
	
	$sql=mysql_query("insert into comments(u_id,comment,msg_id_fk)values('$u_id','$comment','$msg_id')");
	//$sql=mysql_query("insert into comments(comment,msg_id_fk)values('$comment','$msg_id')");	

	$selectstring = "select comments.com_id as com_id, comments.comment as comment, user.name as name from user, comments where comments.u_id = user.rowID order by com_id desc";
	$result=mysql_query($selectstring);
	
	//$result=mysql_query("select * from comments order by com_id desc");

	$row=mysql_fetch_array($result);
	$id=$row['com_id'];
	$comment=$row['comment'];
	$username = $row['name'];

	//$u_idg=$row['u_id'];	
	//$result2 = mysql_query("select * from user where rowID = '$u_idg')");
	//$row=mysql_fetch_array($result);	
	//$username = $row['username'];
}


?>

 <div class="comment_load" id="comment<?php echo $id; ?>">
 <?php echo $username.": ".$comment;  ?>
 <span class="cdelete_button"><a href="#" id="<?php echo $id; ?>" class="cdelete_update">X</a></span>
 </div>
