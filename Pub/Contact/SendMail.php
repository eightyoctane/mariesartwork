<?php

if (!isset($_POST['B1'])) {
	echo "Mail input failed.";
	$mailtext = $_POST['S1'];
	echo "whats this: $mailtext";
	
} else {
		$mailtext = $_POST['S1'];
		echo $mailtext;
}

?>