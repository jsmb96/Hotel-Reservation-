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

    $departmentID = $_POST["ID"];
    $departmentName = $_POST["departmentName"];
    $departmentNameOld = $_POST["departmentNameOld"];
    $facultyID = $_POST["facultyID"];
    $buildingCode = $_POST["buildingCode"];

    $sql = "SELECT email, phone_Number FROM faculty WHERE ID = '" . $facultyID . "'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $facultyEmail = $row["email"];
    $facultyPhone = $row["phone_Number"];

    $sql = "SELECT department_ID, faculty_ID, department_Name FROM department";
    $department = $conn->query($sql);

    while($row = mysqli_fetch_array($department))
    {
        if(($departmentName == $row["department_Name"]) && ($departmentID != $row["department_ID"]))
            $nameCheck = True;

        if(($facultyID == $row["faculty_ID"]) && ($departmentID != $row["department_ID"]))
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
        $sql = "UPDATE department SET building_Code = '" . $buildingCode . "', faculty_ID = '" . $facultyID . "', department_Name = '" . $departmentName .
                "', email_Address = '" . $facultyEmail . "', phone_Number = '" . $facultyPhone . "' WHERE department_ID = '" . $departmentID . "'";

        if ($conn->query($sql) === TRUE) {
            $_SESSION["Error"] = "Department info updated successfully.";
        } else {
            $_SESSION["Error"] = "Error: " . $sql . "<br>" . $conn->error;
        }

        $sql = "UPDATE course SET department_Name = '" . $departmentName . "' WHERE department_Name = '" . $departmentNameOld . "'";
        $conn->query($sql);

        $sql = "UPDATE section SET department_Name = '" . $departmentName . "' WHERE department_Name = '" . $departmentNameOld . "'";
        $conn->query($sql);

        $sql = "UPDATE faculty SET department_Name = '" . $departmentName . "' WHERE department_Name = '" . $departmentNameOld . "'";
        $conn->query($sql);

        $sql = "UPDATE major SET major_Title = '" . $departmentName . "' WHERE department_ID = '" . $departmentID . "'";
        $conn->query($sql);

        $sql = "UPDATE minor SET minor_Title = '" . $departmentName . "' WHERE department_ID = '" . $departmentID . "'";
        $conn->query($sql);
    }
    
    header('Location: ViewDepartmentInfo.php');

?>