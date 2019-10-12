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

	$fname = $_POST["fname"];
	$mname = $_POST["mname"];
	$lname = $_POST["lname"];
	$phone = $_POST["phone"];
    $dob = $_POST["dob"];

    $sql = "SELECT * FROM user WHERE ID = '" . $_SESSION["ID"] . "'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();

    $usertype = $row["type"];
   	
   	if($fname != "")
   	{
   		$sql = "UPDATE user SET first_Name = '" . $fname . "' WHERE ID = '" . $_SESSION["ID"] . "'";

        if($usertype == "Admin"){$sql2 = "UPDATE admin SET first_Name = '" . $fname . "' WHERE ID = '" . $_SESSION["ID"] . "'";}
        else if($usertype == "Student"){$sql2 = "UPDATE student SET first_Name = '" . $fname . "' WHERE ID = '" . $_SESSION["ID"] . "'";}
        else if($usertype == "Faculty"){$sql2 = "UPDATE faculty SET first_Name = '" . $fname . "' WHERE ID = '" . $_SESSION["ID"] . "'";}
        else if($usertype == "Research"){$sql2 = "UPDATE researchstaff SET first_Name = '" . $fname . "' WHERE ID = '" . $_SESSION["ID"] . "'";}

   		if (($conn->query($sql) === TRUE) && ($conn->query($sql2) === TRUE)) {
    		echo "Record updated successfully";
		} else {
    		echo "Error updating record: " . $conn->error;
		}
   	}
   	if($mname != "")
   	{
   		$sql = "UPDATE user SET middle_Initial = '" . $mname . "' WHERE ID = '" . $_SESSION["ID"] . "'";

        if($usertype == "Admin"){$sql2 = "UPDATE admin SET middle_Initial = '" . $mname . "' WHERE ID = '" . $_SESSION["ID"] . "'";}
        else if($usertype == "Student"){$sql2 = "UPDATE student SET middle_Initial = '" . $mname . "' WHERE ID = '" . $_SESSION["ID"] . "'";}
        else if($usertype == "Faculty"){$sql2 = "UPDATE faculty SET middle_Initial = '" . $mname . "' WHERE ID = '" . $_SESSION["ID"] . "'";}
        else if($usertype == "Research"){$sql2 = "UPDATE researchstaff SET middle_Initial = '" . $mname . "' WHERE ID = '" . $_SESSION["ID"] . "'";}

   		if (($conn->query($sql) === TRUE) && ($conn->query($sql2) === TRUE)) {
    		echo "Record updated successfully";
		} else {
    		echo "Error updating record: " . $conn->error;
		}
   	}
   	if($lname != "")
   	{
   		$sql = "UPDATE user SET last_Name = '" . $lname . "' WHERE ID = '" . $_SESSION["ID"] . "'";

        if($usertype == "Admin"){$sql2 = "UPDATE admin SET last_Name = '" . $lname . "' WHERE ID = '" . $_SESSION["ID"] . "'";}
        else if($usertype == "Student"){$sql2 = "UPDATE student SET last_Name = '" . $lname . "' WHERE ID = '" . $_SESSION["ID"] . "'";}
        else if($usertype == "Faculty"){$sql2 = "UPDATE faculty SET last_Name = '" . $lname . "' WHERE ID = '" . $_SESSION["ID"] . "'";}
        else if($usertype == "Research"){$sql2 = "UPDATE researchstaff SET last_Name = '" . $lname . "' WHERE ID = '" . $_SESSION["ID"] . "'";}

   		if (($conn->query($sql) === TRUE) && ($conn->query($sql2) === TRUE)) {
    		echo "Record updated successfully";
		} else {
    		echo "Error updating record: " . $conn->error;
		}
   	}
   	if($phone != "")
   	{
   		$sql = "UPDATE user SET phone_Number = '" . $phone . "' WHERE ID = '" . $_SESSION["ID"] . "'";

        if($usertype == "Admin"){$sql2 = "UPDATE admin SET phone_Number = '" . $phone . "' WHERE ID = '" . $_SESSION["ID"] . "'";}
        else if($usertype == "Student"){$sql2 = "UPDATE student SET phone_Number = '" . $phone . "' WHERE ID = '" . $_SESSION["ID"] . "'";}
        else if($usertype == "Faculty"){$sql2 = "UPDATE faculty SET phone_Number = '" . $phone . "' WHERE ID = '" . $_SESSION["ID"] . "'";}
        else if($usertype == "Research"){$sql2 = "UPDATE researchstaff SET phone_Number = '" . $phone . "' WHERE ID = '" . $_SESSION["ID"] . "'";}

   		if (($conn->query($sql) === TRUE) && ($conn->query($sql2) === TRUE)) {
    		echo "Record updated successfully";
		} else {
    		echo "Error updating record: " . $conn->error;
		}
   	}
    if($dob != "")
    {
        $sql = "UPDATE user SET DOB = '" . $dob . "' WHERE ID = '" . $_SESSION["ID"] . "'";

        if($usertype == "Admin"){$sql2 = "UPDATE admin SET DOB = '" . $dob . "' WHERE ID = '" . $_SESSION["ID"] . "'";}
        else if($usertype == "Student"){$sql2 = "UPDATE student SET DOB = '" . $dob . "' WHERE ID = '" . $_SESSION["ID"] . "'";}
        else if($usertype == "Faculty"){$sql2 = "UPDATE faculty SET DOB = '" . $dob . "' WHERE ID = '" . $_SESSION["ID"] . "'";}
        else if($usertype == "Research"){$sql2 = "UPDATE researchstaff SET DOB = '" . $dob . "' WHERE ID = '" . $_SESSION["ID"] . "'";}

        if (($conn->query($sql) === TRUE) && ($conn->query($sql2) === TRUE)) {
            $_SESSION["Error"] =  "Personal info updated successfully.";
        } else {
            $_SESSION["Error"] =  "Error updating record: " . $conn->error;
        }
    }
   	
   	header('Location: ViewPersonalInfo.php');

?>