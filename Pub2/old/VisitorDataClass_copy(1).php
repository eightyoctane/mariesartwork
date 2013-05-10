<?php
		//include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
class VisitorData {


	var $mysqldb;
//REVISIT
	function VisitorData() {
		$rootlocation = ROOTLOCATION;
		require_once($rootlocation."login/libfunctions.php");
		include_once($rootlocation."login/mysqlClass.php");
		include_once($rootlocation."logFileClass.php");
		include_once($rootlocation."login/dbdata.php");
	
		$lf = new logFile();	
		
		$this->mysqldb = new mysql($system,$dbuser,$dbpwd, $database);
		$this->mysqldb->connect();
		$this->mysqldb->select();
	}
	
	function getNumNonOwnerResidents() {

		//$query = "SELECT rowID, name, username, phone, email, add_apt, add_street, add_city,".
		//" add_state, add_zip, add_country, u_type, u_priv, phone FROM user ORDER BY name";
		
		$query = "SELECT rowID FROM user WHERE u_type=1";

		$this->mysqldb->query($query);

		return($this->mysqldb->numRows());
	}
	
		function getNumOwnerResidents() {

		//$query = "SELECT rowID, name, username, phone, email, add_apt, add_street, add_city,".
		" add_state, add_zip, add_country, u_type, u_priv, phone FROM user ORDER BY name";
		
		$query = "SELECT rowID FROM user WHERE u_type=2 AND u_priv != 6";

		$this->mysqldb->query($query);

		return($this->mysqldb->numRows());
	}

}
