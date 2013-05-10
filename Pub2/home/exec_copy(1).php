<?php

{
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT']."/homedataV0.php");
	include_once($rootlocation."PresenterClass.php");
	include_once($rootlocation."logFileClass.php");
	include_once($rootlocation."login/sessionSetup.php");
		
	$lf = new logFile();

	$lf->logMessage("exec.php: ".$rootlocation."\n");
	
	$sp = new sessionPageBrowser($_SERVER['DOCUMENT_ROOT']."/".$simpleregion);
	
	sessionSetup($sp,$lf,$_SESSION['u_type'],1,$homelocationpart.$homelocationsub.$homelanding[$_SESSION['u_type']]);
	
	//sessionSetup($sp,$lf,2,1,$homelocationpart.$homelocationsub.'index.php');
	$_SESSION['prevpage'] = $_SESSION['homepage'];
	
	$thislocation = $homelocationpart.$homelocationsub."exec.php";

	$sp->addHeadTextContent("<META HTTP-EQUIV=\"Refresh\" CONTENT=\"5; URL=$thislocation\">");

	$_SESSION['index'] = $_SESSION['index'] + 1;	
	$sp->addBodyText("Index: ".$_SESSION['index']."<br>");
	$mycmd = $rootlocation."mycmd.ksh";
	$array =  my_exec($mycmd);
	$sp->addBodyText($array['stdout']);
	
	
	$sp->renderPage();
}

 function display_exec() {
	ob_start();
?>
\<\?\=\$output = my_exec('.$rootlocation."mycmd.ksh".'); echo $output[\'stdout\']."<br>".$output[\'stderr\']."<br>";?>';
<?php
	$output = my_exec($rootlocation.'mycmd.ksh');
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

?> 

