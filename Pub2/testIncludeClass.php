<?php

class testInclude {
	public function __construct() {
		$path = $_SERVER['DOCUMENT_ROOT']."/homedatapub2.php";
		require_once $path;
		echo "testinclude: $rootlocation";
	}
}
?>