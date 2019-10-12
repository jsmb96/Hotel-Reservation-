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

    $CRN = $_POST["CRN"];
    $studentID = $_POST["studentID"];

    $sql = "UPDATE section SET enrolled = enrolled - 1 WHERE CRN = '" . $CRN . "'";
    $conn->query($sql);

    $sql = "DELETE FROM enrollment WHERE CRN = '" . $CRN . "' AND student_ID = '" . $studentID . "'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION["Error"] = "Course dropped successfully.";
    } else {
        $_SESSION["Error"] = "Error: " . $sql . "<br>" . $conn->error;
    }

    if($_SESSION["type"] == "Admin")
    {
        header('Location: ViewUserInfo.php');
    }
    else
        header('Location: ViewPersonalSchedule.php');

?>