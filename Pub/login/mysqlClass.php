<?php

include_once($_SERVER['DOCUMENT_ROOT']."/homedata.php");
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
	
	public function select() {
		mysql_select_db($this->db);	
	}
	
	public function query($q) {
		//$this->lf->logMessage("query: ".$q."\n");
		
		if ( !($this->result = mysql_query($q) )) {
			throw new Exception ("mysqlClass: query failed\n");
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
		return $count;
	}
	
	public function fetchObject() {
		$row = mysql_fetch_object($this->result);
		return $row;
	}
	
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
		$row = @mysql_fetch_array($this->result);
		return $row;
	}
	
	public function numQueries() {
		return $this->querycount;
	}
	
}
?>