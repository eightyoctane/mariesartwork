	</td>
</tr>
<tr>
	<td>
		<?php
			if ($SMObj->isSecureLogin())
				$SMObj->getLoginForm(NULL,"./index.php?m=admin&a=account");
		?>
	</td
</tr>

</table>
<?php
	$DB_LINK->Close();
?>
<p><br><font size=1><center>
<a href="http://<?php echo $g_rb_project_website . '">' . $g_rb_project_name . " " . $g_rb_project_version;?></a>
</center>
</font>
</body>
</html>
