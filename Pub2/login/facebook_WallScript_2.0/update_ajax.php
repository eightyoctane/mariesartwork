
 <?php
		//function update_ajax_inside($lf) {

		include("db.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/homedata.php");

		include("tolink.php");

        include_once($rootlocation."logFileClass.php");        	
        $lf = new logFile();

		//ob_start();
		
		if(isSet($_POST['content']))
		
		{
		$id=time();//Demo Use
		$msg=$_POST['content'];
		$u_id=$_POST['u_id'];
		$lf->logMessage("update_ajax: u_id ".$u_id." Inserting message ".$msg." into messages table\n");	
		$sql=mysql_query("insert into messages(message,u_id)values('$msg','$u_id')");
		
		$selectstring = "select messages.msg_id as msg_id, messages.message as message, user.name as name from user, messages where messages.u_id = user.rowID  order by msg_id desc";
		$result=mysql_query($selectstring);
		
		//$result=mysql_query("select * from messages order by msg_id desc");
		
		$row=mysql_fetch_array($result);
		$id=$row['msg_id'];
		$msg=$row['message'];
		$msg=toLink($msg);
		$u_id=$row['u_id'];
		$name=$row['name'];
		}
		
		
		?>
		
		 
		
		
		<li class="bar<?php echo $id; ?>">
		<div align="left" class="post_box">
		<span style="padding:10px"><?php echo $name.": ".$msg; ?> </span>
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
		//		$buffer = ob_get_contents();
		//		ob_end_clean();
		//		return($buffer);
		//}
?>
