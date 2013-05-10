<?php if ($format == "yes") { ?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $LangUI->getEncoding();?>"/>
	<title><?php echo $g_rb_project_name; ?></title>
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/themes/redmond/jquery-ui.css" type="text/css" media="screen">
	<link rel="stylesheet" href="themes/<?php echo $g_rb_theme;?>/style.css" type="text/css" />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
</head>

<?php
require_once("classes/DBUtils.class.php");

// If we are print mode, then do not do most of the header
if ($print == "no") {
	include("includes/menu_items.php");
?>

<body background="themes/<?php echo $g_rb_theme;?>/images/bground.png">

<div class="topnav">
	<img src="themes/<?php echo $g_rb_theme;?>/images/logo.png" alt="" />&nbsp;
	<img src="themes/<?php echo $g_rb_theme;?>/images/pixel.png" width="20" height="1" border="0" alt="" align="top" />
</div>

<div>
	<div class="sidenav">

	<?php if ($SMObj->checkAccessLevel("AUTHOR")) { ?>
		<b><a href="index.php?m=recipes&amp;a=addedit" >[<?php echo $LangUI->_('Add Recipe'); ?>]</a></b>
	<?php }	else { ?>
		<img src="themes/<?php echo $g_rb_theme;?>/images/pixel.png" width="122" height="1" border="0" alt="" align="top" />
	<?php } ?>
	
	<br/><b><?php echo $LangUI->_('Course');?>:</b><?php
	$sql = "SELECT course_id,course_desc FROM $db_table_courses ORDER BY course_desc";
	$rc = $DB_LINK->Execute( $sql );
	DBUtils::checkResult($rc, NULL, NULL, $sql);
	
	while (!$rc->EOF) {
		echo '<br /><a href="index.php?m=recipes&amp;a=search&amp;search=yes&amp;course_id='.$rc->fields['course_id'].'">'.$rc->fields['course_desc'].'</a>';
		$rc->MoveNext();
	}
	?>
	<br/><b><?php echo $LangUI->_('Base');?>:</b>
	
	<?php
	$sql = "SELECT base_id,base_desc FROM $db_table_bases ORDER BY base_desc";
	$rc = $DB_LINK->Execute( $sql );
	DBUtils::checkResult($rc, NULL, NULL, $sql);
	
	while (!$rc->EOF) {
		echo '<br /><a href="index.php?m=recipes&amp;a=search&amp;search=yes&amp;base_id='.$rc->fields['base_id'].'">'.$rc->fields['base_desc'].'</a>';
		$rc->MoveNext();
	}
	
	if ($SMObj->isSecureLogin())
	{
		$SMObj->getLoginForm(NULL,"./index.php?m=admin&a=account");
	}
	?>
	</div>
	
	<div class="mainContent">
		<?php 
		printMenu($menu_items);
		// Print out a submenu for the user to navigate
		printSubMenu($menu_items);
		} ?>
<?php } // end no format
?>

