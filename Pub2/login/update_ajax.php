<?php
		include("db.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		
		include("tolink.php");

        include_once($rootlocation."logFileClass.php");        	
        $lf = new logFile();
	
		if(isSet($_POST['content']))
		
		{
			$id=time();//Demo Use
			$msg=mysql_real_escape_string ($_POST['content']);
			$u_id=$_POST['u_id'];
			$lf->logMessage("update_ajax: u_id ".$u_id." Inserting message ".$msg." into messages table\n");	
			//$sql=mysql_query("insert into messages(message,u_id)values('$msg','$u_id')");
			
			$sql=mysql_query("insert into messages(message,u_id,dtandtime)values('$msg','$u_id',NOW())");
	
			
			$selectstring = "select messages.msg_id as msg_id, messages.message as message, user.name as name from user, messages where messages.u_id = user.rowID  order by msg_id desc";
			$result=mysql_query($selectstring);
			
			$row=mysql_fetch_array($result);
			$id=$row['msg_id'];
			$msg=$row['message'];
			$msg=toLink($msg);
			$u_id=$row['u_id'];
			$name=$row['name'];
			
			$selectstring = "select user.email as email, user.e_notify as enotify from user where u_type>".$utype['visitor'];
			$result=mysql_query($selectstring);
	
	
			while($row=mysql_fetch_array($result)) {
				if($row['enotify'] == 1) {
					$mailtostring .= $row['email'].", ";
				}
			}
			
			$lf->logMessage("update_ajax.php: mail receivers: ".$mailtostring."\n");
			
			//$mailtostring = $email;
		
			$mailstring .= 
			"One of your friends at ".$nameid." has logged a comment at the ".$sitedescription."\n".
			"Click on the link below, to check it out!\n".
			">>>>> ".$homelocationnewsurl."\n".
			"\nBest wishes from your friends at ".$nameid."\n";
			
			$lf->logMessage("update_ajax.php: mailto: ".$mailtostring." message:\n".$mailstring."\n");
			
			mail($mailtostring,"New Comment from one of your friends at ".$nameid,$mailstring);	
		}	
		
		?>
		
		 
		
		
		<li class="bar<?php echo $id; ?>">
		<div align="left" class="post_box">
		<span style="text-align:left;font-family:arial;color:black;font-size:12px;padding:10px"><?php echo $name.": ".$msg; ?></span>
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


?>
