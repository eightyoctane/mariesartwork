<?php
	include_once($_SERVER['DOCUMENT_ROOT'].'/gPageClass.php');
	
	$gp = new gPage($_SERVER['DOCUMENT_ROOT'].'/bma_RegionAndHeader.htm');
	
	// Add a menu appropriate for this page and state
	$gp->addMenu($_SERVER['DOCUMENT_ROOT'].'/homepageMenu.html');
	
	if (! isset($_POST['Submit'])){

	
			$gp->addBodyContent("login/htmlfiles/resetpassword.htm");
			
			$gp->addFooter($_SERVER['DOCUMENT_ROOT'].'/generalFooter.htm');
			
			$gp->renderGeneralPage();
			
	} else {
	
		echo "email: ".$_POST['T1'];
	}
?>