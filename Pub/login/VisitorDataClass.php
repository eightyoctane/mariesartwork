<?php

class VisitorData {
	var $mysqldb;

	function VisitorData() {

		require_once($_SERVER['DOCUMENT_ROOT']."/OSA/login/libfunctions.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/OSA/login/mysqlClass.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/OSA/logFileClass.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/OSA/login/dbdata.php");
	
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