<?php
	include_once("mysqlClass.php");
	
	
	//echo "opening the database <br />";
	$mysqldb = new mysql('localhost','bluemov2_vadmin','1200','bluemov2_visitors');
	$mysqldb->connect();
	$mysqldb->select();

	/*
	$mysqldb->query("INSERT INTO user \(rowID, name, username, email, pwd, u_type,
					add_apt, add_street, add_city, add_state,
					add_zip, add_country, phone, start_date, login_type
				  \)
				  VALUES \(NULL, '$_POST[name]', '$_POST[uname]','$_POST[email]', MD5\('$_POST[pwd]'\), '$_POST[D1]',
				  '$_POST[apt]', '$_POST[street]', '$_POST[city]', '$_POST[state]',
				  '$_POST[zip]', '$_POST[country]', '$_POST[phone]', '$start_date', '$logintype'
				  \)");
	//echo "<p> query = $query</p>";
	
			$query = "SELECT name, username, u_type, u_priv, start_date, pwd, login_type FROM user
				WHERE username= '$uname' AND
				pwd=MD5('$pwd') AND
				login_type= 'artmarie'";
				
				$rows = mysql_num_rows($result);
				$result = $mysqldb->getResult();

	*/

	$query = "SELECT * FROM user";
	$mysqldb->query($query);
	$numrows = $mysqldb->numRows();
	echo "Number of rows found: $numrows";

?>
		
		
	
