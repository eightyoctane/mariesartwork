<?php
//////////////////////////////////////////////////////////////////////////
// ltwCatMgr
// $Id: ltwcatmgr.php 5 2006-03-12 08:15:51Z mootinator $
//
// used to manage the Event Categories on the site.
// these allow you give different colors to different events
//////////////////////////////////////////////////////////////////////////

class ltwCatMgr {
var $auth	= '';
var $cat      	= '';
var $table	= '';
var $loglevel	= '';
var $def_fgcolor= '';
var $def_bgcolor= '';
var $catA    	= array();
var $catA_cnt	= '';
var $php_self	= '';

// constructor
function ltwCatMgr (){
	global $ltw_config;
  	global $_POST;
	global $_SERVER;
		
	$this->auth		= new ltwAuth;
	$this->cat        	= new ltwCategory;
	$this->table		= $ltw_config['db_table_category'];
	$this->def_fgcolor	= $ltw_config['cat_fgcolor'];
	$this->def_bgcolor	= $ltw_config['cat_bgcolor'];
	$this->loglevel		= $ltw_config['cloglevel'];
	$this->php_self		= $_SERVER['PHP_SELF'];

	$this->loadArray();
} // end constructor

function loadArray(){
	$this->catA 	= array();
	$this->catA_cnt = '';

	// read the category table into an array
	$query = "SELECT * from ".$this->table." order by name";
	$result = $this->cat->db->db_query($query);
	while ( $row = $this->cat->db->db_fetch_array($result) ){
		$this->catA[$row['id']] = array(stripslashes($row['name']),stripslashes($row['fgcolor']),stripslashes($row['bgcolor']) );
	 	$this->catA_cnt = $this->catA_cnt + 1;
	}
} // end function.loadArray()


function manage (){
	global $_POST;

	$errors = '';
	$logm	= '';
	$logd	= '';
	$updateit = 0;
	$inisertit= 0;

	if ( !$this->auth->checkLogin() ){
		$this->auth->notLoggedIn();
		return 0;
	}

	if ( !$this->auth->user->getPrivledge(UPADMIN) ){
	  $this->auth->notPrivledged();
		return 0;
	}

	// 1st time thru 

	if ( !empty($_POST['Done']) ){
		jsClosePopupReloadMain($this->php_self);
		return;
	}

	if ( !empty($_POST['Submit']) ){
		// if any categories defined
		if ( $this->catA_cnt != 0 ){
			foreach ( $this->catA as $id => $value ){
				$logm	= '';
				$logd	= '';
				$updateit = 0;
				// if delete box checked
				if ( !empty($_POST['checkbox'.$id]) && ($_POST['checkbox'.$id] == 'delete') ){
					$this->cat->findbyid($id);
					$this->cat->deletebyid($id);
					$logm = "<b>Cat: Deleted ".$this->cat->name."</b>(".$id.")<br>";
					$logd .= "FgColor= ".$this->cat->fgcolor."<br>BgColor= ".$this->cat->bgcolor;
					$this->notifier($logm,$logd);
				}else{
					// check if any changes have been made to the
					// name, fgcolor or bgcolor for this cat_id
					// (saves a db access if not)
		
					// Set up the category data
					$this->cat->id       = $id;
					$this->cat->name     = $this->catA[$id][0]; 
					$this->cat->fgcolor  = $this->catA[$id][1];
					$this->cat->bgcolor  = $this->catA[$id][2];
					$logm = "<b>Cat: Updated ".$this->cat->name."</b>(".$id.")";

					// see if the name set and is different
					if ( !empty($_POST['name'.$id]) && ($_POST['name'.$id] != $this->cat->name) ){
						$updateit = 1; 
						$logd .= "<br>Name: ".$this->cat->name." => ".$_POST['name'.$id];
						$this->cat->name = $_POST['name'.$id];
					}

					// see if the fgcolor changed
					if ( !empty($_POST['fgcolor'.$id]) && ($_POST['fgcolor'.$id] != $this->cat->fgcolor) ){
					  	if ( $this->checkColorString($_POST['fgcolor'.$id]) ){
							$updateit = 1; 
							$logd .= "<br>FgColor: ".$this->cat->fgcolor." => ".$_POST['fgcolor'.$id];
							$this->cat->fgcolor = $_POST['fgcolor'.$id];
					  	}else{
					  		$errors .= $this->cat->name.": fgcolor (".$_POST['fgcolor'.$id].") invalid<br>";
							$updateIt = 0;
						}
					}
	
					// see if the bgcolor changed
					if ( !empty($_POST['bgcolor'.$id]) && ($_POST['bgcolor'.$id] != $this->cat->bgcolor) ){
						if ( $this->checkColorString($_POST['bgcolor'.$id]) ){
							$updateit = 1; 
							$logd .= "<br>BgColor: ".$this->cat->bgcolor." => ".$_POST['bgcolor'.$id];
							$this->cat->bgcolor = $_POST['bgcolor'.$id];
					 	}else{
						   	$errors .= $this->cat->name.": bgcolor (".$_POST['bgcolor'.$id].") invalid<br>\n";
							$updateIt = 0;
						}
					}

					// if any of the three fields changed, update the database
					if ( ($updateit == 1) && (empty($errors)) ){
						$this->cat->update();
						$this->notifier($logm,$logd);
					}
				}
			} //end foreach
		}	

		// now check the new category
		// First the name
		if ( (isset($_POST['newname'])) && !empty($_POST['newname']) ){
			$this->cat->name = $_POST['newname'];
			$logm = "<b>Cat: Added ".$this->cat->name."</b><br>";
				

			// then the fgcolor
			if ( !empty($_POST['newfgcolor']) ){
				if ( $this->checkColorString($_POST['newfgcolor']) ){
					$insertit = 1; 
					$logd .= "FgColor: ".$_POST['newfgcolor']."<br>";
					$this->cat->fgcolor = $_POST['newfgcolor'];
				}else{
					$errors .= $this->cat->name.": fgcolor (".$_POST['newfgcolor'].") invalid<br>\n";
					$insertIt = 0;
				}
			}else{
				$insertit = 1; 
				$fgcolor = $this->def_fgcolor;
			}

			// then the bgcolor
			if ( !empty($_POST['newbgcolor']) ){
				if ( $this->checkColorString($_POST['newbgcolor']) ){
			  		$insertit = 1; 
					$logd .= "BgColor: ".$_POST['newbgcolor']."<br>";
					$this->cat->bgcolor = $_POST['newbgcolor'];
				}else{
					$errors .= $this->cat->name.": bgcolor (".$_POST['newbgcolor'].") invalid<br>\n";
					$insertit = 0;
				}
			}else{
				$insertit = 1; 
				$bgcolor = $this->def_bgcolor;
			}
			if ( ($insertit == 1)  && empty($errors) ){
				$this->cat->create();
				$this->notifier($logm,$logd);
			}
		}
	} 	 
	if ( empty($errors) ) $this->loadArray();
	$this->display($errors);

} // end function.manage


function display($errors = ''){
	// display form
	//
	header("Cache-control: no-cache");
	header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");
		
	echo "
	<html><head><title>Manage Categories</title></head><body>
	<form name=\"categories\" method=\"post\" action=\"".$this->php_self."?display=admin&task=categories\">
	<table border=\"1\">			
	<tr>
	<td align=\"center\"><b>Category Name (Id)</b></td>
	<td align=\"center\"><b>FG Color<br>(#nnnnnn)</b></td>
	<td align=\"center\"><b>BG Color<br>(#nnnnnn)</b></td>
	<td align=\"center\"><b>Delete</b><td>
	</tr>
	 ";

	if ( $this->catA_cnt != 0 ){
		foreach ( $this->catA as $key => $value ){
			$name    = $this->catA[$key][0];
			$fgcolor = $this->catA[$key][1];
			$bgcolor = $this->catA[$key][2];
			echo "
			<tr>
			<td align=\"left\"><input size=\"30\" maxlength=\"30\" type=\"text\" name=\"name".$key."\" value=\"".$name."\">($key)</td>
			<td align=\"left\"><input size=\"7\" maxlength=\"7\" type=\"text\" name=\"fgcolor".$key."\" value=\"".$fgcolor."\"></td>
			<td align=\"left\"><input size=\"7\" maxlength=\"7\" type=\"text\" name=\"bgcolor".$key."\" value=\"".$bgcolor."\"></td>
			<td align=\"center\"><input type=\"checkbox\" name=\"checkbox".$key."\" value=\"delete\"></td></tr>
			 ";
		}
	}

	echo "
	<tr><td colspan=\"4\"><b>Add new category</b></td></tr>
	<tr><td align=\"left\"><input size=\"30\" maxlength=\"30\" type=\"text\" name=\"newname\"></td>
	<td align=\"left\"><input size=\"7\" maxlength=\"7\" type=\"text\" name=\"newfgcolor\"></td>
	<td align=\"left\"><input size=\"7\" maxlength=\"7\" type=\"text\" name=\"newbgcolor\"></td><td>&nbsp;</td></tr>
	<tr><td colspan=\"4\">&nbsp;</td></tr>
	<tr><td colspan=\"4\"><input type=\"submit\" name=\"Submit\" value=\"Submit\">
	&nbsp;&nbsp;&nbsp;<input type=\"submit\" name=\"Done\" value=\"Done\">
	</table>
	".$errors."
	</body></html>
        ";

} // end function.display


function notifier($logm,$logd){
	if ( $this->loglevel != 0 ) {
		if ( $this->loglevel & CLDETAIL ){
			$this->cat->log($this->auth->user->username,$logm.$logd);
		}else{
			$this->cat->log($this->auth->user->username,$logm);
		}
	}
}
	
// checks a color string matches the format #xxxxxx
// where xxxxxx are valid hex digits
function checkColorString ($color = ""){
	if ( preg_match ('/^#[0-9a-f]{6}$/i',$color) ){
		return 1;
	}else{
		return 0;
	}
} // end function.checkColorString

} // end class.ltwCatMgr

?>
