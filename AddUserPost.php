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

	$usertype = $_POST["userType"];
	$email = $_POST["email"];
	$password = $_POST["password"];
	$fname = $_POST["fname"];
	$mname = $_POST["mname"];
	$lname = $_POST["lname"];
	$phone = $_POST["phone"];
	$dob = $_POST["dob"];

	$studentYear = $_POST["studentYear"];
	$enrollType = $_POST["enrollType"];
	$enrollStatus = $_POST["enrollStatus"];
    $major = $_POST["major"];
    $minor = $_POST["minor"];

	$employStatus = $_POST["employStatus"];
	$department = $_POST["department"];
	$office = $_POST["office"];
	
	$sql = "INSERT INTO user (type,email,password,
			first_Name,middle_Initial,last_Name,phone_Number,DOB)
			VALUES ('" . $usertype . "','"
					. $email . "','"
					. $password . "','"
					. $fname . "','"
					. $mname . "','"
					. $lname . "','"
					. $phone . "','"
					. $dob . "')";

	if ($conn->query($sql) === TRUE) {
    	$_SESSION["Error"] = "User added successfully.";
	} else {
    	$_SESSION["Error"] = "Error: " . $sql . "<br>" . $conn->error;
	}

	if($usertype == "Admin")
	{
		$sql2 = "INSERT INTO admin (type,email,password,
			first_Name,middle_Initial,last_Name,phone_Number,DOB)
			VALUES ('" . $usertype . "','"
					. $email . "','"
					. $password . "','"
					. $fname . "','"
					. $mname . "','"
					. $lname . "','"
					. $phone . "','"
					. $dob . "')";

		if ($conn->query($sql2) === TRUE) {
    		$_SESSION["Error"] = "Admin added successfully.";
		} else {
    		$_SESSION["Error"] = "Error: " . $sql2 . "<br>" . $conn->error;
		}
	}
	else if($usertype == "Student")
	{
		$sql2 = "INSERT INTO student (type,email,password,
			first_Name,middle_Initial,last_Name,phone_Number,DOB,
			year,enrollment_Type,enrollment_Status,major_ID,minor_ID)
			VALUES ('" . $usertype . "','"
					. $email . "','"
					. $password . "','"
					. $fname . "','"
					. $mname . "','"
					. $lname . "','"
					. $phone . "','"
					. $dob . "','" 
					. $studentYear . "','" 
					. $enrollType . "','"
					. $enrollStatus . "','"
                    . $major . "','"
                    . $minor . "')";

		if ($conn->query($sql2) === TRUE) {
    		$_SESSION["Error"] = "Student added successfully.";
		} else {
    		$_SESSION["Error"] = "Error: " . $sql2 . "<br>" . $conn->error;
		}
	}
	else if($usertype == "Faculty")
	{
		$sql2 = "INSERT INTO faculty (type,email,password,
			first_Name,middle_Initial,last_Name,phone_Number,DOB,
			employment_Status,department_Name,office_Hours)
			VALUES ('" . $usertype . "','"
					. $email . "','"
					. $password . "','"
					. $fname . "','"
					. $mname . "','"
					. $lname . "','"
					. $phone . "','"
					. $dob . "','"
					. $employStatus . "','" 
					. $department . "','"
					. $office ."')";

		if ($conn->query($sql2) === TRUE) {
    		$_SESSION["Error"] = "Faculty added successfully.";
		} else {
    		$_SESSION["Error"] = "Error: " . $sql2 . "<br>" . $conn->error;
		}
	}
	else if($usertype == "Research")
	{
		$sql2 = "INSERT INTO researchstaff (type,email,password,
			first_Name,middle_Initial,last_Name,phone_Number,DOB)
			VALUES ('" . $usertype . "','"
					. $email . "','"
					. $password . "','"
					. $fname . "','"
					. $mname . "','"
					. $lname . "','"
					. $phone . "','"
					. $dob . "')";

		if ($conn->query($sql2) === TRUE) {
    		$_SESSION["Error"] = "Research Staff added successfully.";
		} else {
    		$_SESSION["Error"] = "Error: " . $sql2 . "<br>" . $conn->error;
		}
	}

	$sql3 = "INSERT INTO login (email,type,password)
			VALUES ('". $email . "','"
					. $usertype . "','"
					. $password . "')";

	if ($conn->query($sql3) === TRUE) {
    	//$_SESSION["Error"] = "User added successfully.";
	} else {
    	$_SESSION["Error"] = "Error: " . $sql3 . "<br>" . $conn->error;
	}

	header('Location: AddUser.php');

?>