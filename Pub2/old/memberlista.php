<?php

	/* Connect to the MySQL server and select database. */
	mysql_connect("localhost","artmarie","nnf7vydu");
	mysql_select_db("artmarie");
	//mysql_connect("localhost","root","");
	//mysql_select_db("ggross");

	$query = "SELECT name, username, u_type, email, add_apt, add_street, add_city, add_state, add_zip, add_country, phone, start_date FROM user";
	$result = mysql_query($query);
	while (list($name,$username,$u_type, $email,$add_apt,$add_street,$add_city,$add_state, $add_zip, $add_country,$phone,$start_date) = mysql_fetch_row($result)) {
		$fdate=date('m-d-Y', $start_date);
		echo "$name, $username, $u_type, $email, $add_apt, $add_street, $add_city, $add_state, $add_zip, $add_country, $phone, $fdate<br />";
	}
?>