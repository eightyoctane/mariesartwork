<!DOCTYPE html> 
<html> 
	<head> 
	<title><?php echo $g_rb_project_name; ?></title> 
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a2/jquery.mobile-1.0a2.min.css" />
	<link rel="stylesheet" href="themes/<?php echo $g_rb_theme;?>/style_mob.css" type="text/css" />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.0a2/jquery.mobile-1.0a2.min.js"></script>
</head> 
<body>

<?php 
	$format = isset( $_REQUEST['format'] ) ? $_REQUEST['format'] : 'yes';
	$aNav = $_GET['a'];
	$activeItem = "az";
	
	switch ($aNav)
	{
		case "index_mob":
			$activeItem = "az";
			break;
		case "login_mob":
			$activeItem = "login";
			break;
		case "search_mob":
			$activeItem = "search";
			break;
		case "current_mob":
			$activeItem = "list";
			break;
	}
?>

<div data-role="page">
<?php if ($format == "yes") {?>
	<div data-role="header">
		<h1>Cookbook</h1>
		<?php if ($SMObj->checkAccessLevel("AUTHOR")) { ?>
		<a href="./index.php?m=admin&a=index" rel="external" data-icon="gear" class="ui-btn-right">Options</a>
		<?php } ?>
		<div data-role="navbar">
			<ul>
				<li><a href="index.php?m=recipes&a=index_mob" <?php echo ($activeItem == "az") ? 'class="ui-btn-active"' : '';?>>A-Z</a></li>
				<li><a href="index.php?m=recipes&a=search_mob" <?php echo ($activeItem == "search") ? 'class="ui-btn-active"' : '';?>>Search</a></li>
				<?php if ($SMObj->getUserLoginID() != "") { ?>
				<li><a href="index.php?m=lists&amp;a=current_mob" <?php echo ($activeItem == "list") ? 'class="ui-btn-active"' : '';?>><?php echo $LangUI->_('List'); ?></a></li>
				<?php } 
				if ($SMObj->getUserLoginID() != "") { ?>
				<li><a href="index.php?m=login&a=login_mob" <?php echo ($activeItem == "login") ? 'class="ui-btn-active"' : '';?>>Log Out</a></li>
				<?php } else { ?>
				<li><a href="index.php?m=login&a=login_mob" <?php echo ($activeItem == "login") ? 'class="ui-btn-active"' : '';?>>Log In</a></li>
				<?php } ?>
			</ul>
		</div><!-- /navbar -->
		
	</div><!-- /header -->

	<div data-role="content">	
<?php } ?>