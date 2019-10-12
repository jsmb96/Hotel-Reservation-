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

    $date = getdate();
    $year = $date["year"];
    $month = $date["mon"];
    $day = $date["mday"];
    $today = $year . "-" . $month . "-" . $day;

    if($studentID == 0)
    {
        $_SESSION["Error"] = "Error: Invalid student.";
    }
    else if($facultyID == 0)
    {
        $_SESSION["Error"] = "Error: Invalid faculty.";
    }
    else
    {
        $sql = "INSERT INTO advisor(faculty_ID, student_ID, date_Assigned)
            VALUES ('" . $facultyID . "','" . $studentID . "','" . $today . "')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION["Error"] = "Advisor updated successfully.";
        } else {
            $_SESSION["Error"] = "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    header('Location: ViewUserInfo.php');
?>