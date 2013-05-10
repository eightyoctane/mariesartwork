
<?php
 
function preload_comment_ajax($lf,$msg_id_fk) {

		include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include("db.php");
	
		$lf = new logFile();
	
		ob_start();
	
		$id=time();// Demo Use
		//$u_id = $_POST['u_id'];
		//$comment=$_POST['comment_content'];
		//$msg_id=$_POST['msg_id'];
	
		//$lf->logMessage("comment_ajax.php: Updating comments table u_id:".$u_id." comment: ".$comment." msg_id: ".$msg_id."\n");	
		
		//$sql=mysql_query("insert into comments(u_id,comment,msg_id_fk)values('$u_id','$comment','$msg_id')");
		//$sql=mysql_query("insert into comments(comment,msg_id_fk)values('$comment','$msg_id')");	
	
		//$selectstring = "select comments.dtandtime as dtandtime, comments.msg_id_fk as msg_id_fk, comments.com_id as com_id, comments.comment as comment, user.name as name from user, comments where comments.u_id = user.rowID and comments.msg_id_fk = $msg_id order by com_id desc";
		$selectstring = "select comments.msg_id_fk as msg_id_fk, comments.com_id as com_id, comments.comment as comment, user.name as name from user, comments where comments.u_id = user.rowID and comments.msg_id_fk = $msg_id order by com_id desc";

		$result=mysql_query($selectstring);
		
		while($row=mysql_fetch_array($result)) {
			$id=$row['com_id'];
			$comment=$row['comment'];
			$msg=toLink($comment);
			$name=$row['name'];
			//$dtandtime=$row['dtandtime'];
?>
	
	 <div class="comment_load" id="comment<?php echo $id; ?>">
	 <?php echo $dtandtime." ".$username.": ".$comment;  ?>
	 <span class="cdelete_button"><a href="#" id="<?php echo $id; ?>" class="cdelete_update">X</a></span>
	 </div>
	
<?php
		
		}
	
	$buffer = ob_get_contents();
	ob_end_clean();
	return($buffer);
}
?>