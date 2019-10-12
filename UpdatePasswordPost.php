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

	$oldPassword = $_POST["oldPassword"];
	$newPassword = $_POST["newPassword"];
	$newPassword2 = $_POST["newPassword2"];

    $sql = "SELECT password, email FROM user WHERE ID = '" . $_SESSION["ID"] . "'"; //change to login?
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
   	
   	$dbPassword = $row["password"];

    if($dbPassword != $oldPassword)
    {
        $_SESSION["Error"] = "Error: Current password is not correct.";
    }
    else if($newPassword != $newPassword2)
    {
        $_SESSION["Error"] = "Error: Both new password fields must match.";
    }
   	else
   	{
   		$sql = "UPDATE login SET password = '" . $newPassword . "' WHERE email = '" . $row["email"] . "'";
   		if ($conn->query($sql) === TRUE) {
    		$_SESSION["Error"] = "Password updated successfully";
		} else {
    		$_SESSION["Error"] = "Error updating record: " . $conn->error;
		}
   	}
   	header('Location: ViewPersonalInfo.php');

?>