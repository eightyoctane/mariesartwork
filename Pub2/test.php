<?php

//$path = $_SERVER['DOCUMENT_ROOT']."/Pub2/PresenterClass.php";
//include $path;

	include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include($rootlocation."PresenterClass.php");
	//include_once($rootlocation.'logFileClass.php');

	
	$gp = new GeneralPageBrowser($regionandheaderfull);

?>