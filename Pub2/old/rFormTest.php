<?php

//session_start();

require_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");

require_once($rootlocation."login/libfunctions.php");
//include_once($rootlocation."login/mysqlClass.php");
//include_once($rootlocation."login/rFormClass.php");
//include_once($_SERVER['DOCUMENT_ROOT']."/dbdata.php");
include_once($rootlocation.'logFileClass.php');
//include_once($rootlocation.'PresenterClass.php');

$lf = new logFile();
	
$lf->logMessage("rForm.php: rootlocation: ".$rootlocation);
echo "in rForm.php: rootlocation: ".$rootlocation;	
?>
