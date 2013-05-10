
 <?php
		function preload_msg_ajax($lf) {

		include("db.php");
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");

		include("tolink.php");

        //include_once($rootlocation."logFileClass.php");        	
        //$lf = new logFile();

		ob_start();
		
		$id=time();//Demo Use
		$msg="Preload Test";
		$u_id=$_SESSION['u_id'];
		
		$lf->logMessage("preload_ajax: u_id ".$u_id." Requesting all messages\n");	
		
		//$sql=mysql_query("insert into messages(message)values('$msg')");
		//$result=mysql_query("select * from messages order by msg_id desc");

		$maxentries = $walldisplaymsglimit;
		$lf->logMessage("preload_msg_ajax.php: walldisplaylimit: ".$maxentries."\n");
		
		$selectstring = "select messages.dtandtime as dtandtime, messages.u_id as mu_id, messages.msg_id as msg_id, messages.message as message, user.name as name from user, messages where messages.u_id = user.rowID  order by msg_id desc limit $maxentries";
		$result=mysql_query($selectstring);
	
		while($row=mysql_fetch_array($result)) {

			$mu_id=$row['mu_id'];
			$id=$row['msg_id'];
			$msg=$row['message'];
			$msg=toLink($msg);
			$name=$row['name'];
			$dtandtime=$row['dtandtime'];

			//$lf->logMessage("preload_msg: Found message for user ".$name." userid: ".$mu_id." id: ".$id." date: ".$dtandtime."\n");
			
			$refid="muid".$id;
		?>
				
			<li class="bar<?php echo $id; ?>">
			<div align="left" class="post_box">
			<span style="text-align:left;font-family:arial;color:black;font-size:12px;padding:10px"><?php echo $dtandtime." ".$name.": ".$msg; ?> </span>
			<form><input type=hidden id="<?php echo $refid?>" name="<?php echo $refid?>" value="<?php echo $mu_id?>"></form> 
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
			$selectstring = "select comments.dtandtime as cdtandtime, comments.u_id as cmu_id, comments.msg_id_fk as msg_id_fk, comments.com_id as com_id, comments.comment as comment, user.name as name from user, comments where comments.u_id = user.rowID and comments.msg_id_fk = $id order by com_id desc";
			$resultc=mysql_query($selectstring);
		
			while($rowc=mysql_fetch_array($resultc)) {
				$cmu_id = $rowc['cmu_id'];
				$msg_id = $rowc['msg_id_fk'];
				$idc=$rowc['com_id'];
				$comment=$rowc['comment'];
				$msg=toLink($comment);
				$name=$rowc['name'];
				$cdtandtime = $rowc['cdtandtime'];

				//$lf->logMessage("preload_msg_ajax.php: Found comment for userid: ".$cmu_id." message id: ".$msg_id." comment id: ".$idc." date: ".$cdtandtime."\n");
				
				$crefid = "u_id".$idc;

?>
	 			<form><input type=hidden id="<?php echo $crefid?>" name="<?php echo $crefid?>" value="<?php echo $cmu_id?>"></form> 
	 			
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
