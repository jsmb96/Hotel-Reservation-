<?php
	session_start();

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "RegistrationSystem";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

    if(!is_null($_SESSION["ID"]))
        header('Location: UserHomepage.php');
    else
        header('Location: CentralHomepage.php');

?>