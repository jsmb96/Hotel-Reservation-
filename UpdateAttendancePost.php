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
    $daysAbsent = $_POST["daysAbsent"];
    $attendCheck = $_POST["attendCheck"];
    
    $date = getdate();
    $year = $date["year"];
    $month = $date["mon"];
    $day = $date["mday"];
    $today = $year . "-" . $month . "-" . $day;

    if($attendCheck == False)
    {
        $sql = "INSERT INTO attendance(CRN,student_ID,days_absent,date)
            VALUES ('" . $CRN . "','" . $studentID . "','" . $daysAbsent . "','" . $today . "')";
    }
    else
    {
        $sql = "UPDATE attendance SET days_absent = '" . $daysAbsent . "', date = '" . $today . 
                "' WHERE CRN = '" . $CRN . "' AND student_ID = '" . $studentID . "'";
    }

    if ($conn->query($sql) === TRUE) {
        $_SESSION["Error"] = "Attendance updated successfully.";
    } else {
        $_SESSION["Error"] = "Error: " . $sql . "<br>" . $conn->error;
    }

    if($_SESSION["type"] == "Admin")
    {
        header('Location: ViewUserInfo.php');
    }
    else
        header('Location: ViewGradebook.php');

    
?>