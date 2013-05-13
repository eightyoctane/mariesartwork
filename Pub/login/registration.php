<?php
/*
Has the user submitted data?
If not, display the registration form.
*/
if (! isset($_POST['submitbutton'])){
	echo file_get_contents("./Templates/registrationa.html");
	/* Form data has been submitted. */
	} else {
		/* echo "input detected"; */
		/* Connect to the MySQL server and select database. */
		mysql_connect("localhost","bluemov2_vadmin","1200"); 
		/*mysql_connect("localhost","root",""); */
		mysql_select_db("bluemov2_visitors");
		/* Ensure that the password and password verifier match. */
		if ($_POST['pwd'] != $_POST['pwdv']){
			echo "<p> The passwords do not match. Please go back and try again.</p>";
		/* Passwords match, so attempt to insert information into user table. */
		} else {
				//echo "<p>Type: $_POST[D1]</p>";
				$query = "INSERT INTO user SET rowID=NULL, name='$_POST[name]',
				email='$_POST[email]', username='$_POST[uname]',
				pwd=MD5('$_POST[pwd]')";
				$result = mysql_query($query);
				echo "<p>Registration was successful!</p>";
				// send mail to the new 80octane user
				mail("$_POST[email]", "Newly Registered User @bluemountainairpark.com", "Hi,\n You are now registered at
http://www.bluemountainairpark.com . \nLogin name: $_POST[uname]\nPassword: $_POST[pwd]\n\nWe can usually establish
your account within a day or two.\n");
				mail("eightyoctane@yahoo.com", "Newly Registered User @bluemountainairpark.com", "Login name: $_POST[uname]\n
				Email address: $_POST[email]\n\n");
				echo (include('indexli.html'));

		} #endIF
}
/* echo "no input detected." */
?>
