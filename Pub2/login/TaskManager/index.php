<?php 
require_once "header.php";
if(isset($_COOKIE['demo']))
{
	//print_r($_COOKIE['demo']);
//exit;
print_r($_COOKIE['demo'] . 'abc');
$_COOKIE = array();

}
 ?>
	<script type="text/javascript" src="js/webtoolkit.drag.js"></script>
	<script type="text/javascript">
	
	function getY( oElement )
	{
		var iReturnValue = 0;
		while( oElement != null ) 
		{
			iReturnValue += oElement.offsetTop;
			oElement = oElement.offsetParent;
		}
		alert("iReturnValue");
		return iReturnValue;
		
	}
	function begin (element, x, y) {
		var s = '#' + element.id + ' (begin drag)' + ' x:' + x + ', y:' + y;
		
	}

	function drag (element, x, y) {
		var s = '#' + element.id + ' (dragging)' + ' x:' + x + ', y:' + y;
		
	}

	function end (element, x, y) {
		var s = '#' + element.id + ' (end drag)' + ' x:' + x + ', y:' + y;
		if(x > 500 && x < 600)
		{	
			
			window.location = "MarkItem.php?Action=Edit&ItemId=" + element.id;
		}
		
	}


</script>
	

<link href="styles.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
<!--

function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
//-->

function submitform()
{
  MM_validateForm('Description','','R');
  if(document.MM_returnValue)
	  document.form1.submit();
}

function submitEdform()
{
  MM_validateForm('Description','','R');
  if(document.MM_returnValue)
	  document.EditForm.submit();
}


function subSearchForm()
{
  MM_validateForm('Keyword','','R');
  if(document.MM_returnValue)
	  document.SearchForm.submit();
}
</script>
<script language="javascript" type="text/javascript">
<!--
function newPrompt(id) 
{
var answer = confirm ("This will delete all the info related to the Item. Are you sure you want to proceed?")
if (answer)
window.location = "DelItem.php?Id="+id
}

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

//-->
</script><table width="100%" border="0">
  <tr>
    <td colspan="2"><form action="index.php?Action=Search" method="post" name="SearchForm" id="SearchForm">
      <table width="100%" border="0">
        <tr>
          <td colspan="2" class="sectiontitle">Search  Item</td>
        </tr>
        <tr class="SectionLabels">
          <td width="34%">Enter keywords or tags</td>
          <td width="66%">&nbsp;</td>
        </tr>
        <tr>
          <td><input name="Keyword" type="text" id="Keyword" value="" size="45" /></td>
          <td valign="top"><a href="#" class="green_button" onclick="subSearchForm();">&nbsp;&nbsp;&nbsp;&nbsp;SEARCH&nbsp;&nbsp;&nbsp;&nbsp;</a> </td>
        </tr>
      </table>
    </form></td>
  </tr>
           <?php
if (!empty($_REQUEST['Action']) && $_REQUEST['Action'] == "Search") 
{ 
//	$ListItems = "Select * from ".$tags_table." where tags_tags like '%".$_REQUEST['Keyword']."%' AND item_status=1";

	//$ListItems = "Select tdl_tags.tags_tags, tdl_tags.id_item, tdl_items.id,tdl_items.item_desc, tdl_items.item_status from tdl_tags, tdl_items where tdl_tags.tags_tags like '%".$_REQUEST['Keyword']."%' AND tdl_items.item_desc like '%".$_REQUEST['Keyword']."%' AND tdl_items.item_status=1 AND tdl_tags.item_status=1";
	
	$ListItems = "SELECT id as \"id_item\" FROM tdl_items WHERE item_desc like '%".$_REQUEST['Keyword']."%' AND item_status=1 UNION SELECT id_item FROM tdl_tags WHERE tags_tags like '%".$_REQUEST['Keyword']."%' AND item_status=1 ";
	
	

} 
else 
{
	$ListItems = "Select * from ".$items_table." WHERE item_status=1";
}
$RsItems = mysql_query($ListItems, $localhost) or die(mysql_error());
$totalItems = mysql_num_rows($RsItems);
		
if ($totalItems > 0)    
{      
	  
	  ?>
	  
 <tr>
    <td width="64%"><form id="form2" name="form2" method="post" action="">
      <table width="100%" border="0" >
        <tr>
          <td colspan="3" class="sectiontitle">To do Items</td>
        </tr>
        <tr class="SectionLabels">
          <td width="12%">Actions</td>
          <td width="35%">Items <strong>(<?php echo $totalItems; ?>)</strong></td>
          <td>Tags</td>
          </tr>
        <?php while ($RsItem = mysql_fetch_assoc($RsItems)) 
			{
				if (!empty($_REQUEST['Action']) && $_REQUEST['Action'] == "Search") 
				{
				  $SqlItm = "select * from ".$items_table." where id = ".$RsItem['id_item']." AND item_status=1";
				  //print_r($SqlItm);
				  //exit;
				  $RsItms = mysql_query($SqlItm, $localhost) or die(mysql_error());
				  $RsItm = mysql_fetch_assoc($RsItms);
				  $ItemDesc = stripslashes($RsItm['item_desc']);
				  $ItemId = $RsItem['id_item'];
				} 
				else 
				{
				  $ItemDesc = stripslashes($RsItem['item_desc']);
				  $ItemId = $RsItem['id'];
				}
		?>
        <tr >
          <td><!--<img src="images/icon_move.gif" alt="Move" width="22" height="22" border="0" />--><a href="index.php?Action=Edit&amp;ItemId=<?php echo $ItemId; ?>"><img src="images/icon_edit.gif" alt="Edit" width="18" height="22" border="0" /></a><a href="#" onclick="newPrompt(<?php echo $ItemId;?>)"><img src="images/icon_del.gif" alt="Delete" width="18" height="20" border="0" /></a><a href="MarkItem.php?Action=Edit&amp;ItemId=<?php echo $ItemId; ?>"><img src="images/icon_mark.gif" alt="Mark Completed" width="21" height="20" border="0" /></a></td>
		           <td><div id="<?php echo $ItemId; ?>" style="position: relative;cursor:move;" title="Move The Task to Archive Box" ><?php echo $ItemDesc; ?>&nbsp;</div></td>
				   	<script type="text/javascript">
						var <?php echo (it . $ItemId ); ?> = DragHandler.attach(document.getElementById('<?php echo $ItemId; ?>'));
						<?php echo (it . $ItemId ); ?>.dragBegin = begin;
						<?php echo (it . $ItemId ); ?>.drag = drag;
						<?php echo (it . $ItemId ); ?>.dragEnd = end;
					</script>
          <td>
		  <?php
		  	$myArr = array();
			$ListTags = "Select * from ".$tags_table." where id_item = '".$ItemId."' AND item_status=1 ";	
			$RsTags = mysql_query($ListTags, $localhost) or die(mysql_error());		  
			while ($RsTag = mysql_fetch_assoc($RsTags)) 
			{
				$myArr[] = $RsTag['tags_tags'];
		  
		  	} 
		 	$new = implode(", ", $myArr);
		 	echo $new;
		 ?>          </td>
          </tr> <?php 
		} ?>
      </table>
	  	 

        </form>    </td>
		<td height="50px" colspan="2" align="left"><?php

function tag_info() 
{
	$result = mysql_query("SELECT * FROM tdl_tags LIMIT 100");
	/*print_r(mysql_num_rows($result));
	exit;
	*/	
	while($row = mysql_fetch_assoc($result)) 
	{
		$result2 = mysql_query("SELECT * FROM tdl_tags where tags_tags = '".$row['tags_tags']."' ");
		$row2 = mysql_fetch_assoc($result2);
		$TagsNum = mysql_num_rows($result2);
		
		$arr[$row['tags_tags']] = $TagsNum;
	}
	ksort($arr);
	
	//$arr = array_unique($arr);
	return $arr;
}	

function tag_cloud() 
{
// Define the min and max size of the fonts 
	$min_size = 10;
	$max_size = 30;
	$tags = tag_info();
	
	$minimum_count = min(array_values($tags));
	$maximum_count = max(array_values($tags));
	$spread = $maximum_count - $minimum_count;
	if($spread == 0) 	
	{
		$spread = 1;
	}

	$cloud_html = '';
	$cloud_tags = array();

	$forUnique = array();
	foreach ($tags as $tag => $count) 
	{
		// My records have a + between each word, I'm removing these with the line below
		
		$tagshow = str_replace("+"," ",$tag);
		$size = $min_size + ($count - $minimum_count) * ($max_size - $min_size) / $spread;
		
		
		if(!in_array(strtolower($tag),$forUnique))
		{
			$forUnique[] = strtolower($tag);
			// create the html output - make sure to adapt tout you url
			$cloud_tags[] = '<a style="font-size: '. floor($size) . 'px'
			. '" class="tag_cloud'. floor($size) . '" href="index.php?Action=Search&Keyword=' . $tag
			. '" title="\'' . $tag . '\' has ' . $count . '" items >'
			. htmlspecialchars(stripslashes($tagshow)) . '</a>';
		}
	}
	
	$cloud_html = join("\n", $cloud_tags) . "\n";
	return $cloud_html;
	
	
}
?>
		  <div class="myDiv">
        <?php print tag_cloud(); ?>			</div></td>
  </tr>
  <?php } ?>
  <?php if (empty($_REQUEST['Action'])) 
  
  { ?>
  <tr>
    <td colspan="2"><form name="form1" method="post" action="AddItem.php">
      <a name="AddItem" id="AddItem"></a>
      <table width="100%" border="0">
        <tr>
          <td colspan="3" id="threshold" class="sectiontitle">Add New  Item</td>
        </tr>
        <tr class="SectionLabels">
          <td width="31%">Description</td>
          <td width="38%">Tags (separated by comma)</td>
          <td width="31%">&nbsp;</td>
        </tr>
        <tr>
          <td><input name="Description" type="text" id="Description" value="" size="45" /></td>
          <td><input name="Tags" type="text" id="Tags" value="" size="45" /></td>
          <td valign="top">
            <!--<select name="Priority" id="Priority">
              <option value="3">Low</option>
              <option value="2" selected>Medium</option>
              <option value="1">High</option>
            </select>-->            <input name="button" type="submit" class="green_button" id="button" value="Save" />          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
        </form>    </td>
  </tr>
  <?php 
  } 
  elseif (!empty($_REQUEST['Action']) && $_REQUEST['Action'] == "Edit") 
  { 
	$EdtItem = "Select * from ".$items_table." Where id = ".$_REQUEST['ItemId'];
	$RsEdt = mysql_query($EdtItem, $localhost) or die(mysql_error());		  
	$RsEdtItem = mysql_fetch_assoc($RsEdt)
  ?>
  <tr>
    <td colspan="2"><form action="EditItem.php" method="post" name="EditForm" id="EditForm">
      <a name="Edit" id="Edit"></a>
      <table width="100%" border="0">
        <tr>
          <td colspan="3" class="sectiontitle">Edit   Item</td>
        </tr>
        <tr class="SectionLabels">
          <td width="31%">Description</td>
          <td width="38%">Tags (separated by comma)</td>
          <td width="31%">&nbsp;</td>
        </tr>
        <tr>
          
          <td><input name="Description" type="text" id="Description" value="<?php echo stripslashes($RsEdtItem['item_desc']) ; ?>" size="45" /></td>
          <?php
		  $myArr2 = array();
$ListTags = "Select * from ".$tags_table." where id_item = '".$RsEdtItem['id']."' ";	
$RsTags = mysql_query($ListTags, $localhost) or die(mysql_error());		  
while ($RsTag = mysql_fetch_assoc($RsTags)) 
{
		  $myArr2[] = trim($RsTag['tags_tags']);
		  
		 $new2 = implode(", ", $myArr2); 
		  ?>

		 <?php } ?>
          <td><input name="Tags" type="text" id="Tags" value="<?php echo $new2; ?>" size="45" /></td>
          <td valign="top"><p>
            <a href="#" class="green_button" onClick="submitEdform();">&nbsp;&nbsp;&nbsp;&nbsp;UPDATE&nbsp;&nbsp;&nbsp;&nbsp;</a>            
              <input name="ItemId" type="hidden" id="ItemId" value="<?php echo $_REQUEST['ItemId'] ; ?>" />
              <input name="ItemStatus" type="hidden" id="ItemStatus" value="<?php echo $RsEdtItem['item_status'] ; ?>" />
          </p>            </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
        </form>    </td>
  </tr>
  <?php } ?>
</table>

<?php require_once "archive.php"; ?>
<?php require_once "footer.php"; ?>

