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

    $_SESSION["timeout"] = "";
    $_SESSION["Error"] = "";

	$email = $_POST["email"];
	$password = $_POST["password"];

	$sql = "SELECT type, email, password FROM login WHERE email = '" . $email . "'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	
	if($password == $row["password"])
	{
		$sql2 = "SELECT ID, hidden FROM user WHERE email = '" . $email . "'";
		$result2 = $conn->query($sql2);
		$row2 = $result2->fetch_assoc();

		$_SESSION["ID"] = $row2["ID"];
        $_SESSION["type"] = $row["type"];
        $hidden = $row2["hidden"];

        if($hidden == 1)
        {
            $_SESSION["Error"] = "Error: User account has been deactivated.";
            header('Location: Login.php');
        }
		else 
		{
			header('Location: UserHomepage.php');
		}
	}
	else
	{
        $_SESSION["attempts"]++;

        $_SESSION["Error"] = "Error: Username or password is incorrect. Attempts remaining: " . (4 - $_SESSION["attempts"]);
		header('Location: Login.php');
	}
	
?>