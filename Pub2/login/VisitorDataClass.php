<?php

class VisitorData {
	private $mysqldb;
	private $lf;
	private $displayfile;

	public function __construct($lf) {
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include($rootlocation."dbdata.php");
		include_once($rootlocation."login/mysqlClass.php");
		$this->mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
		$this->mysqldb->connect();
		$this->mysqldb->select();

		$this->lf = $lf;	

	}
	
	protected function bufferEval($filename) {
		ob_start();
		include($filename);
		$buffer .= ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
	
	public function setDisplayFile($filename) {
		$this->displayfile = $filename;
	}
	
	public function displayFile() {
		return $this->bufferEval($this->displayfile);
	}
	
		
	public function myUserNameFromID($myid) {		
		$this->mysqldb->query("SELECT rowID, name FROM user WHERE rowID='$myid'");
		$row = $this->mysqldb->fetchObject();
		return($row->name);
	}
	
	public function myUserPrivFromID($myid) {
		$this->mysqldb->query("SELECT rowID, u_priv FROM user WHERE rowID='$myid'");
		$row = $this->mysqldb->fetchObject();
		return($row->u_priv);
	}
	
	public function getUserName($vid) {
		$q = "select rowID, name from user where vid='$vid'";
		//print("<p>getUserName *******query ".$q."</p>");

		$this->mysqldb->query($q);
		
		$row = $this->mysqldb->fetchArray();
		$name = $row[name];
		//print("<p>getUserName ******* ".$name."</p>");

		//$this->lf->logMessage("VisitorDataClass/getUserName: ".$name."\n");
		return($name);
	}


	
	public function getNumNonOwnerResidents() {
	
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");


		$query = "SELECT rowID FROM user WHERE u_type='".$utype[visitor]."'";

		$this->mysqldb->query($query);

		return($this->mysqldb->numRows());
	}
	
		function getNumOwnerResidents() {
		
			include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		
			$query = "SELECT rowID FROM user WHERE u_type='".$utype['resident']."' AND u_priv != '".$upriv['webadmin']."'";
	
			$this->mysqldb->query($query);
	
			return($this->mysqldb->numRows());
	}

	public function getIdNamePairs($usertype, &$userpriv, &$valuearray) {
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		$index=0;
		$idx=0;		
		while($userpriv[$idx]) {
			$query = "SELECT rowID, name FROM user WHERE u_type='".$usertype."' AND u_priv='".$userpriv[$idx]."'";
			//echo "<p>***** query: ".$query."</p>";
			$this->mysqldb->query($query);
			
			while( ($row=$this->mysqldb->fetchObject()) ) {
				$valuearray[$index]['id']= $row->rowID;
				$valuearray[$index]['name'] = $row->name;
				$index++;
			}
			$idx++;
		}
	}
	
}

?>
