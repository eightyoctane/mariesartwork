<?php require_once "includes/connect.php"; 
mysql_select_db($database_localhost, $localhost);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BMA To Do List</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="95%" border="0">
  <tr>
    <td class="Ctrltitle">BMA To Do List</td>
  </tr>  <tr>
    <td>&nbsp;</td>
  </tr>
<tr>
  <td><a href="index.php" class="green_button">All Items</a> <a href="index.php#AddItem" class="green_button">Add New Item</a></td>
</tr>
  <tr>
    <td>
