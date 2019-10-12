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

    $nameCheck = False;
    $headCheck = False;

    $departmentName = $_POST["departmentName"];
    $facultyID = $_POST["facultyID"];
    $buildingCode = $_POST["buildingCode"];

    $sql = "SELECT email, phone_Number FROM faculty WHERE ID = '" . $facultyID . "'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $facultyEmail = $row["email"];
    $facultyPhone = $row["phone_Number"];

    $sql = "SELECT faculty_ID, department_Name FROM department";
    $department = $conn->query($sql);

    while($row = mysqli_fetch_array($department))
    {
        if($departmentName == $row["department_Name"])
            $nameCheck = True;

        if($facultyID == $row["faculty_ID"])
            $headCheck = True;
    }

    if($nameCheck == True)
    {
        $_SESSION["Error"] = "Error: A department with that name already exists.";
    }
    else if($headCheck == True)
    {
        $_SESSION["Error"] = "Error: Selected faculty is already the head of another department.";
    }
    else
    {
        $sql = "INSERT INTO department (building_Code, faculty_ID, department_Name, email_Address, phone_Number)
            VALUES ('" . $buildingCode . "','"
                    . $facultyID . "','"
                    . $departmentName . "','"
                    . $facultyEmail . "','"
                    . $facultyPhone . "')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION["Error"] = "New department added successfully.";
        } else {
            $_SESSION["Error"] = "Error: " . $sql . "<br>" . $conn->error;
        }

        $sql = "SELECT department_ID FROM department WHERE department_Name = '" . $departmentName . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $departmentID = $row["department_ID"];

        $sql = "INSERT INTO major (department_ID, major_Title) VALUES ('" . $departmentID . "','" . $departmentName . "')";
        $conn->query($sql);

        $sql = "INSERT INTO minor (department_ID, minor_Title) VALUES ('" . $departmentID . "','" . $departmentName . "')";
        $conn->query($sql);

        $sql = "INSERT INTO majorrequirements (major_ID) VALUES ('" . $departmentID . "')";
        $conn->query($sql);

        $sql = "INSERT INTO minorrequirements (minor_ID) VALUES ('" . $departmentID . "')";
        $conn->query($sql);
    }
    
    header('Location: AddDepartment.php');

?>