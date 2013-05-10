<?php
function display_exec() {
	ob_start();
?>
\<\?\=\$output = my_exec('.$rootlocation."/login/facebook_WallScript_2.0/mycmd.ksh".'); echo $output[\'stdout\']."<br>".$output[\'stderr\']."<br>";?>';
<?php
	$output = my_exec($rootlocation.'/login/facebook_WallScript_2.0/mycmd.ksh');
	echo $output['stdout'];
	echo $output['stderr'];
	$buffer .= ob_get_contents();
	ob_end_clean();
	return $buffer;	
} 
function my_exec($cmd, $input='') {
	$proc=proc_open($cmd, array(0=>array('pipe', 'r'), 1=>array('pipe', 'w'), 2=>array('pipe', 'w')), $pipes);       
	fwrite($pipes[0], $input);fclose($pipes[0]);       
    $stdout=stream_get_contents($pipes[1]);fclose($pipes[1]); 
    $stderr=stream_get_contents($pipes[2]);fclose($pipes[2]); 
    $rtn=proc_close($proc); 
    return array('stdout'=>$stdout, 'stderr'=>$stderr, 'return'=>$rtn); 
} 
//var_export(my_exec('echo -e $(</dev/stdin) | wc -l', 'h\\nel\\nlo'));

	session_start();
	include_once($_SERVER['DOCUMENT_ROOT']."/homedata.php");
	include_once($rootlocation."PresenterClass.php");
	include_once($rootlocation."logFileClass.php");
	$lf = new logFile();

	include_once($rootlocation.'/login/'."sessionSetup.php");
	$sp = new sessionPageBrowser($rootlocation.'/login/facebook_WallScript_2.0/'."Content/bma_RegionSimple.htm");
	sessionSetup($sp,$lf,2,1,$rootlocation.'login/admin.php');
	$_SESSION['prevpage'] = $_SESSION['homepage'];
	$thislocation = "login/facebook_WallScript_2.0/exec.php";

	$sp->addHeadTextContent('<META HTTP-EQUIV="Refresh" CONTENT="15; URL=$homelocationpart.$homelocationsub.$thislocation">');

	//$output = my_exec($rootlocation.'/login/facebook_WallScript_2.0/mycmd.ksh');	
	//$output = array('stdout'=>"again1", 'stderr'=>"again2");
	//$sp->addBodyText("<li>");
	//$sp->addBodyText("Here we go!\n".$output['stdout']."\n".$output['stderr']."\n");
	//$sp->addBodyText("Here we go!\n".$output['stdout']."\n".$output['stderr']."\n");	
	//$sp->addBodyText("</li>");
	//$bodytext = '<?=$output = my_exec('.$rootlocation."/login/facebook_WallScript_2.0/mycmd.ksh".'); echo $output[\'stdout\']."<br>".$output[\'stderr\']."<br>";?>';
	//$sp->addBodyText(display_exec());
	
	$bodytext = '\<\?\=\$output = "Hello there"; echo \$output\;\?\>';
	$sp->addBodyText($bodytext);
	
	
	$sp->renderPage();

 
?> 

