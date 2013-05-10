<script language="javascript" type='text/javascript'>
function toggleLayer( whichLayer )
{
  var elem, vis;
  if( document.getElementById ) // this is the way the standards work
    elem = document.getElementById( whichLayer );
  else if( document.all ) // this is the way old msie versions work
      elem = document.all[whichLayer];
  else if( document.layers ) // this is the way nn4 works
    elem = document.layers[whichLayer];
  vis = elem.style;
  // if the style.display value is blank we try to figure it out here
  if(vis.display==''&&elem.offsetWidth!=undefined&&elem.offsetHeight!=undefined)
    vis.display = (elem.offsetWidth!=0&&elem.offsetHeight!=0)?'block':'none';
  vis.display = (vis.display==''||vis.display=='block')?'none':'block';
}
</script>
<style >
div#archive
{
  margin: 0px 20px 0px 20px;
  display: none;
}
</style>
<a href="javascript:toggleLayer('archive');" >
  Toggle Archives
</a> <br />
	  <div id="archive" >

<table width="100%" border="0">
           <?php
if (!empty($_REQUEST['Action']) && $_REQUEST['Action'] == "Search") { 
$ListItems = "SELECT id as \"id_item\" FROM tdl_items WHERE item_desc like '%".$_REQUEST['Keyword']."%' AND item_status=2 UNION SELECT id_item FROM tdl_tags WHERE tags_tags like '%".$_REQUEST['Keyword']."%' AND item_status=2 ";

} else {
$ListItems = "Select * from ".$items_table." WHERE item_status=2";
}
$RsItems = mysql_query($ListItems, $localhost) or die(mysql_error());
$totalItems = mysql_num_rows($RsItems);

		
if ($totalItems > 0)    
   {      
	  ?>

 <tr>
    <td><form id="form2" name="form2" method="post" action="">
      <table width="100%" border="0">
        <tr>
          <td colspan="3" class="sectiontitle">To do Items (ARCHIVE)</td>
        </tr>
        <tr class="SectionLabels">
          <td width="12%">Actions</td>
          <td width="35%">Items <strong>(<?php echo $totalItems; ?>)</strong></td>
          <td>Tags</td>
          </tr>
        <?php while ($RsItem = mysql_fetch_assoc($RsItems)) {
		if (!empty($_REQUEST['Action']) && $_REQUEST['Action'] == "Search") {
		  $SqlItm = "select * from ".$items_table." where id = ".$RsItem['id_item']." AND item_status=2";
		  $RsItms = mysql_query($SqlItm, $localhost) or die(mysql_error());
		  $RsItm = mysql_fetch_assoc($RsItms);
		  $ItemDesc = stripslashes($RsItm['item_desc']);
		  $ItemId = $RsItem['id_item'];
		  } else {
		  $ItemDesc = stripslashes($RsItem['item_desc']);
		  $ItemId = $RsItem['id'];
		  }
		?>
        <tr class="ListItems">
          <td><!--<img src="images/icon_move.gif" alt="Move" width="22" height="22" border="0" />--><a href="index.php?Action=Edit&amp;ItemId=<?php echo $ItemId; ?>"><img src="images/icon_edit.gif" alt="Edit" width="18" height="22" border="0" /></a><a href="#" onclick="newPrompt(<?php echo $ItemId;?>)"><img src="images/icon_del.gif" alt="Delete" width="18" height="20" border="0" /></a></td>
          <td><?php echo $ItemDesc; ?></td>
          <td>
		  <?php
		  $myArr = array();
$ListTags = "Select * from ".$tags_table." where id_item = '".$ItemId."' AND item_status=2 ";	
$RsTags = mysql_query($ListTags, $localhost) or die(mysql_error());		  
while ($RsTag = mysql_fetch_assoc($RsTags)) 
{
		  $myArr[] = $RsTag['tags_tags'];
		  
		  
		  ?>

		 <?php } 
		 $new = implode(", ", $myArr);
		 echo $new;
		 ?>          </td>
          </tr> <?php } ?>
      </table>
        </form>    </td>
  </tr>
  <?php } ?>
  <?php if (empty($_REQUEST['Action'])) { ?>
  <?php } elseif (!empty($_REQUEST['Action']) && $_REQUEST['Action'] == "Edit") { 
$EdtItem = "Select * from ".$items_table." Where id = ".$_REQUEST['ItemId'];
$RsEdt = mysql_query($EdtItem, $localhost) or die(mysql_error());		  
$RsEdtItem = mysql_fetch_assoc($RsEdt)
  ?>
  <?php } ?>
  
</table>
</div>
