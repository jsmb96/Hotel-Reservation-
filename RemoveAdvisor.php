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

    $studentID = $_POST["studentID"];
    $facultyID = $_POST["facultyID"];

    $sql = "DELETE FROM advisor WHERE faculty_ID = '" . $facultyID . "' AND student_ID = '" . $studentID . "'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION["Error"] = "Student removed successfully.";
    } else {
        $_SESSION["Error"] = "Error: " . $sql . "<br>" . $conn->error;
    }

    header('Location: ViewUserInfo.php');
?>