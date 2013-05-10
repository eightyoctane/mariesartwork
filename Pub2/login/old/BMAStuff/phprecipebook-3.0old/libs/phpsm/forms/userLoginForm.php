<p>
<form name="loginForm" action="<?php echo $submitURL;?>" method="post">

<?php if ($this->getUserLoginID()) { ?>
<div class="sidebox">
	<?php
		echo $this->_('Welcome') . " " . $this->_userName;
	?>
	<br />
	<br />
	<input type="hidden" name="sm_logout" value="logout"/>
    <input type="submit" name="logoutButton" value="<?php echo $this->_('logout');?>" class="button" />
</div>

<?php } else { ?>

<div class="sidebox">
	<script type="text/javascript">
		$(document).ready(function() {
			$("#smloginField").focus();
		});
	</script>
	
	<?php echo $this->_('Login');?>:<br />
    <input type="text" id="smloginField" name="sm_login_id" class="sidebox_text" /><br />
    <?php echo $this->_('Password');?>:<br />
    <input type="password" name="sm_password" class="sidebox_text" />

	<br/><br />
    <input type="submit" value="<?php echo $this->_('login');?>" class="button" />
    
    <?php
	// only show the register link if allowed to, use admin link if admin
	if ($this->isOpenRegistration())
		echo '<br /><a href="' . $regURL . '" rel="external">'.$this->_('register').'</a>';
	?>
    </div>
<?php } ?>

</form>
</p>
