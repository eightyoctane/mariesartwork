<?php
function facebookwall($lf) {
		include("db.php");
		$lf->logMessage("In facebookwall.php\n");

		ob_start();
?>

		<table cellpadding="0" cellspacing="0" width="500px">
		<tr>
		<td>
		
		
		<div align="left">
		<form  method="post" name="form" action="">
		<table cellpadding="0" cellspacing="0" width="500px">
		
		<tr><td align="left"><div align="left"><h3>What's on your mind regarding airpark care?</h3></div></td></tr>
		<tr>
		<td style="padding:4px; padding-left:10px;" class="update_box">
		<textarea cols="30" rows="2" style="width:480px;font-size:14px; font-weight:bold" name="content" id="content" maxlength="145" ></textarea><br />
		<input type="submit"  value="Update"  id="v" name="submit" class="wall_update"/>
		</td>
		
		</tr>
		
		</table>
		</form>
		
		</div>
		<div style="height:7px"></div>
		<div id="flash" align="left"  ></div>
		
		
		
		<ol  id="update" class="timeline">
		
		
		
		</ol>
		<?php
		
		// Display old updates and comments
		
		?>
		<ol id="oldupdate" class="timeline">
		
		</ol>
		
		</td>
		</tr>
		</table>
		
		
		
		
		
		
		</div>
		
		


<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return($buffer);
}
?>

