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

    $ID = $_POST["ID"];
	$fname = $_POST["fname"];
	$mname = $_POST["mname"];
	$lname = $_POST["lname"];
	$phone = $_POST["phone"];
    $dob = $_POST["dob"];

    $sql = "SELECT * FROM user WHERE ID = '" . $ID . "'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();

    $usertype = $row["type"];

    if($usertype == "Student")
    {
        $studentYear = $_POST["studentYear"];
        $major = $_POST["major"];
        $minor = $_POST["minor"];
        $enrollType = $_POST["enrollType"];
        $enrollStatus = $_POST["enrollStatus"];
    }
    else if($usertype == "Faculty")
    {
        $employStatus = $_POST["employStatus"];
        $department = $_POST["department"];
        $office = $_POST["office"];
    }

   	if($fname != "")
    {
        $sql = "UPDATE user SET first_Name = '" . $fname . "' WHERE ID = '" . $ID . "'";

        if($usertype == "Admin"){$sql2 = "UPDATE admin SET first_Name = '" . $fname . "' WHERE ID = '" . $ID. "'";}
        else if($usertype == "Student"){$sql2 = "UPDATE student SET first_Name = '" . $fname . "' WHERE ID = '" . $ID. "'";}
        else if($usertype == "Faculty"){$sql2 = "UPDATE faculty SET first_Name = '" . $fname . "' WHERE ID = '" . $ID. "'";}
        else if($usertype == "Research"){$sql2 = "UPDATE researchstaff SET first_Name = '" . $fname . "' WHERE ID = '" . $ID. "'";}

        $conn->query($sql);
        $conn->query($sql2);
    }
    if($mname != "")
    {
        $sql = "UPDATE user SET middle_Initial = '" . $mname . "' WHERE ID = '" . $ID. "'";

        if($usertype == "Admin"){$sql2 = "UPDATE admin SET middle_Initial = '" . $mname . "' WHERE ID = '" . $ID. "'";}
        else if($usertype == "Student"){$sql2 = "UPDATE student SET middle_Initial = '" . $mname . "' WHERE ID = '" . $ID. "'";}
        else if($usertype == "Faculty"){$sql2 = "UPDATE faculty SET middle_Initial = '" . $mname . "' WHERE ID = '" . $ID. "'";}
        else if($usertype == "Research"){$sql2 = "UPDATE researchstaff SET middle_Initial = '" . $mname . "' WHERE ID = '" . $ID. "'";}

        $conn->query($sql);
        $conn->query($sql2);
    }
    if($lname != "")
    {
        $sql = "UPDATE user SET last_Name = '" . $lname . "' WHERE ID = '" . $ID. "'";

        if($usertype == "Admin"){$sql2 = "UPDATE admin SET last_Name = '" . $lname . "' WHERE ID = '" . $ID. "'";}
        else if($usertype == "Student"){$sql2 = "UPDATE student SET last_Name = '" . $lname . "' WHERE ID = '" . $ID. "'";}
        else if($usertype == "Faculty"){$sql2 = "UPDATE faculty SET last_Name = '" . $lname . "' WHERE ID = '" . $ID. "'";}
        else if($usertype == "Research"){$sql2 = "UPDATE researchstaff SET last_Name = '" . $lname . "' WHERE ID = '" . $ID. "'";}

        $conn->query($sql);
        $conn->query($sql2);
    }
    if($phone != "")
    {
        $sql = "UPDATE user SET phone_Number = '" . $phone . "' WHERE ID = '" . $ID. "'";

        if($usertype == "Admin"){$sql2 = "UPDATE admin SET phone_Number = '" . $phone . "' WHERE ID = '" . $ID. "'";}
        else if($usertype == "Student"){$sql2 = "UPDATE student SET phone_Number = '" . $phone . "' WHERE ID = '" . $ID. "'";}
        else if($usertype == "Faculty"){$sql2 = "UPDATE faculty SET phone_Number = '" . $phone . "' WHERE ID = '" . $ID. "'";}
        else if($usertype == "Research"){$sql2 = "UPDATE researchstaff SET phone_Number = '" . $phone . "' WHERE ID = '" . $ID. "'";}

        $conn->query($sql);
        $conn->query($sql2);
    }
    if($dob != "")
    {
        $sql = "UPDATE user SET DOB = '" . $dob . "' WHERE ID = '" . $ID. "'";

        if($usertype == "Admin"){$sql2 = "UPDATE admin SET DOB = '" . $dob . "' WHERE ID = '" . $ID. "'";}
        else if($usertype == "Student"){$sql2 = "UPDATE student SET DOB = '" . $dob . "' WHERE ID = '" . $ID. "'";}
        else if($usertype == "Faculty"){$sql2 = "UPDATE faculty SET DOB = '" . $dob . "' WHERE ID = '" . $ID. "'";}
        else if($usertype == "Research"){$sql2 = "UPDATE researchstaff SET DOB = '" . $dob . "' WHERE ID = '" . $ID. "'";}

        if (($conn->query($sql) === TRUE) && ($conn->query($sql2) === TRUE)) {
            $_SESSION["Error"] =  "User info updated successfully";
        } else {
            $_SESSION["Error"] =  "Error updating record: " . $conn->error;
        }
    }
    
    if($usertype == "Student")
    {
        if($studentYear != "")
        {
            $sql = "UPDATE student SET year = '" . $studentYear . "' WHERE ID = '" . $ID. "'";
            $conn->query($sql);
        }
        if($major != "")
        {
            $sql = "UPDATE student SET major_ID = '" . $major . "' WHERE ID = '" . $ID. "'";
            $conn->query($sql);
        }
        if($minor != "")
        {
            $sql = "UPDATE student SET minor_ID = '" . $minor . "' WHERE ID = '" . $ID. "'";
            $conn->query($sql);
        }
        if($enrollType != "")
        {
            $sql = "UPDATE student SET enrollment_Type = '" . $enrollType . "' WHERE ID = '" . $ID. "'";
            $conn->query($sql);
        }
        if($enrollStatus != "")
        {
            $sql = "UPDATE student SET enrollment_Status = '" . $enrollStatus . "' WHERE ID = '" . $ID. "'";
            $conn->query($sql);
        }
    }
    else if($usertype == "Faculty")
    {
        if($employStatus != "")
        {
            $sql = "UPDATE faculty SET employment_Status = '" . $employStatus . "' WHERE ID = '" . $ID. "'";
            $conn->query($sql);
        }
        if($department != "")
        {
            $sql = "UPDATE faculty SET department_Name = '" . $department . "' WHERE ID = '" . $ID. "'";
            $conn->query($sql);
        }
        if($office != "")
        {
            $sql = "UPDATE faculty SET office_Hours = '" . $office . "' WHERE ID = '" . $ID. "'";
            $conn->query($sql);
        }
    }

   	header('Location: ViewUserInfo.php');

?>