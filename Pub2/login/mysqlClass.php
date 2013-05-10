<?php

include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
include_once($rootlocation."logFileClass.php");


class mysql {
	private $linkid;	//MySQL link identifier
	private $host;		// MySQL server host
	private $user;		// MySQL user
	private $pwd;		// MySQL password
	var $db;		// MySQL database
	private $result;	// Query result
	var $querycount; // Total queries executed
	private $lf;
	var $conn;
	
	/* Class constructor. Initializes the $host, $user, $pwd
	and $db fields. */
	
	public function __construct($host, $user, $pwd, $db) {
		$this->host = $host;
		$this->user = $user;
		$this->pwd  = $pwd;
		$this->db = $db;
		$this->lf = new logFile();
	}
	
	//function __destruct() {	
		//Close the mysql connection.
		//mysql_close($this->conn);
	
	//}
	
	/* Connects to the MySQL database server. */
	public function connect() {
		//$this->lf->logMessage("mysqlClass::connect(): Attempting mysql connection\n");
		
		$this->conn = mysql_connect($this->host, $this->user, $this->pwd);		
		if (!$this->conn) {
  			// the connection failed so quit the script
  			$this->lf->logMessage("MySql could not connect\n");
  			die("Could not connect !");
		} 
	}

	public function CheckForDuplicates($reference, $field, $table) {
		$query = "SELECT $field FROM $table";
		$this->query($query);
		//$rows = mysql_numrows($result);
		//echo "rows: $rows";
		$tryagainval = 0;
		for ($count=0; $count < $this->numRows(); $count++) {
			$dbentry = $this->getItems($field, $count);
			//$dbentry = mysql_result($result, $count, "$field");
			if($dbentry == $reference) {
				//echo "<p>Duplicate entry</p>";
				$tryagainval = 1;
				break;
			}
		}
		return($tryagainval);
	}
	
	public function duplicateEntryString($reference, $field, $table) {
		$query = "SELECT $field FROM $table";
		$this->query($query);
		//$rows = mysql_numrows($result);
		//echo "rows: $rows";
		$tryagainval = 0;
		for ($count=0; $count < $this->numRows(); $count++) {
			$dbentry = $this->getItems($field, $count);
			//$dbentry = mysql_result($result, $count, "$field");
			if($dbentry === $reference) {
				//echo "<p>Duplicate entry</p>";
				$tryagainval = 1;
				break;
			}
		}
		return($tryagainval);
	}

	
	private function getItems($item, $count) {
		return mysql_result($this->result, $count, "$item");
	}
	
	public function select() {
		mysql_select_db($this->db);	
	}
	
	public function testquery($q) {
		throw new Exception("mysqlClass testquery: threw exception\n");
	}
	
	public function query($q) {
		
		//$this->lf->logMessage("mysqlClass/query: ".$q."\n");
			
		if ( !($this->result = mysql_query($q) )) {
			$msg = "mysqlClass/query failed\n".$q."\n";
			//$this->lf->logMessage($msg);
			throw new Exception ($msg);
		} else {		
			$this->querycount++;
		}
		return;
	}
	
	public function getItem($item) {
		return mysql_result($this->result, 0, $item);
	}

	
	public function getResult() {
		return $this->result;
	}
	
	public function affectedRows() {
		$count= mysql_affected_rows($this->result);
		return $count;
	}
	
	public function numRows() {
		$count = mysql_num_rows($this->result);
		//echo "<p>*******mysqlclass numrows".$count."</p>";
		return $count;
	}
	
	public function fetchObject() {
		$row = mysql_fetch_object($this->result);
		return $row;
	}
	
	/* this doesn't work right unless you're expecting only one row returned */
	public function fetchRow() {
		if (! $this->result) {
			$this->lf->logMessage("mysqlClass:fetchRow(): nothing to fetch.\n");
			return 0;
		}
		$row = mysql_fetch_row($this->result);
		//echo "fetch_row: $row->rowID <br />";
		return $row;
	}
	
	public function fetchArray() {
		$row = mysql_fetch_array($this->result);
		return $row;
	}
	
	public function numQueries() {
		return $this->querycount;
	}
	
}
?>
