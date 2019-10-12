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

    $departmentID = $_POST["ID"];

    $sql = "SELECT department_Name FROM department WHERE department_ID = '" . $departmentID . "'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $departmentName = $row["department_Name"];

    $sql = "UPDATE department SET hidden = 1 WHERE department_ID = '" . $departmentID . "'";
    $conn->query($sql);

    $sql = "UPDATE course SET hidden = 1 WHERE department_Name = '" . $departmentName . "'";
    $conn->query($sql);

    header('Location: ViewDepartmentInfo.php');
 
?>