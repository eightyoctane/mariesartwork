
 <?php
		function preload_msg_ajax($lf) {

		include("db.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");


		include("tolink.php");

		ob_start();
		
		$id=time();//Demo Use
		$msg="Preload Test";
		$u_id=$_SESSION['u_id'];
		
		$lf->logMessage("preload_ajax: u_id ".$u_id." Requesting all messages\n");	
		
		//$sql=mysql_query("insert into messages(message)values('$msg')");
		//$result=mysql_query("select * from messages order by msg_id desc");

		
		$selectstring = "select messages.dtandtime as dtandtime, messages.msg_id as msg_id, messages.message as message, user.name as name from user, messages where messages.u_id = user.rowID  order by msg_id desc";
		$result=mysql_query($selectstring);
	
		while($row=mysql_fetch_array($result)) {
			$id=$row['msg_id'];
			$msg=$row['message'];
			$msg=toLink($msg);
			$name=$row['name'];
			$dtandtime=$row['dtandtime'];
			$lf->logMessage("preload_msg: Found message for user ".$name." id: ".$id." date: ".$dtandtime."\n");			
		?>			
			<li class="bar<?php echo $id; ?>">
			<div align="left" class="post_box">
			<span style="padding:10px"><?php echo $dtandtime." ".$name.": ".$msg; ?> </span>
			<span class="delete_button"><a href="#" id="<?php echo $id; ?>" class="delete_update">X</a></span>
			<span class='feed_link'><a href="#" class="comment" id="<?php echo $id; ?>">comment</a></span>
			</div>
			<div id='expand_box'>
			<div id='expand_url'></div>
			</div>
			<div id="fullbox" class="fullbox<?php echo $id; ?>">
			<div id="commentload<?php echo $id; ?>" >
			
			</div>
			<div class="comment_box" id="c<?php echo $id; ?>">
			<form method="post" action="" name="<?php echo $id; ?>">
			<textarea class="text_area" name="comment_value" id="textarea<?php echo $id; ?>">
			</textarea><br />
			<input type="submit" value=" Comment " class="comment_submit" id="<?php echo $id; ?>"/>
			</form>
			</div>
			</div>
			
			
			</li>
			
<?php
			$selectstring = "select comments.dtandtime as cdtandtime, comments.msg_id_fk as msg_id_fk, comments.com_id as com_id, comments.comment as comment, user.name as name from user, comments where comments.u_id = user.rowID and comments.msg_id_fk = $id order by com_id desc";
			$resultc=mysql_query($selectstring);
		
			while($rowc=mysql_fetch_array($resultc)) {
				$msg_id = $rowc['msg_id_fk'];
				$idc=$rowc['com_id'];
				$comment=$rowc['comment'];
				$msg=toLink($comment);
				$name=$rowc['name'];
				$cdtandtime = $rowc['cdtandtime'];
				$lf->logMessage("preload_msg_ajax.php: Found comment for message id: ".$msg_id." comment id: ".$idc." date: ".$cdtandtime."\n");

?>
	 			<div class="comment_load" id="comment<?php echo $idc; ?>">
	 			<?php echo $cdtandtime." ".$name.": ".$comment;  ?>
	 			<span class="cdelete_button"><a href="#" id="<?php echo $idc; ?>" class="cdelete_update">X</a></span>
	 			</div>
<?php	
			}

		}
		$buffer = ob_get_contents();
		ob_end_clean();
		return($buffer);
}
?>
