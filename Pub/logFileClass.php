<?php



class logFile {
	private $logfilename;
	private $maddress;
	
	public function __construct() {
		$this->logfilename = $_SERVER['DOCUMENT_ROOT']."/errorlog.txt";
	}
	
	// **FIX return val incorrect
	public function getLogFileName(&$name) {
		$name = $this->logfilename;
	}
	
	public function setLogFileName($logfile) {
		$this->logfilename = $logfile;
	}
	
	public function logMessage($message) {
		$message = date("m/d/y",mktime()).' '.$message;
		file_put_contents($this->logfilename, $message, FILE_APPEND | LOCK_EX);
	}
	
	/*
	function logMessageM($message, $subject, $type) {
		file_put_contents($this->logfilename, $message, FILE_APPEND | LOCK_EX);
		if ( $type == 'M' && $this->maddress != NULL) {
			mail($this->maddress, $subject, $message);
		}
		return;
	}

	
	function setMailAddress($address='eightyoctane@yahoo.com') {
		$this=>maddress = $address;
		return;
	}
*/	
	
	
}