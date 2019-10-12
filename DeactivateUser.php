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

    $season = NULL;
    $date = getdate();
    $year = $date["year"];
    $today = new DateTime();

    $winterStart = new DateTime('January 01');
    $winterEnd = new DateTime('January 18');
    $springStart = new DateTime('January 19');
    $springEnd = new DateTime('May 19');
    $summerStart = new DateTime('May 20');
    $summerEnd = new DateTime('August 31'); 
    $fallStart = new DateTime('September 01');
    $fallEnd = new DateTime('December 31'); 

    if(($today >= $winterStart) && ($today < $winterEnd))
        $season = "Winter";
    else if(($today >= $springStart) && ($today < $springEnd))
        $season = "Spring";
    else if(($today >= $summerStart) && ($today < $summerEnd))
        $season = "Summer";
    else if(($today >= $fallStart) && ($today < $fallEnd))
        $season = "Fall";

    $sql = "SELECT semester_ID FROM semesteryear WHERE Year = '" . $year . "'" . " AND Season = '" . $season . "'";
    $result = $conn->query($sql);
    $yearTable = $result->fetch_assoc();

    $semesterID = $yearTable["semester_ID"];

    $ID = $_POST["ID"];

    $sql = "SELECT type FROM user WHERE ID = '" . $ID . "'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $type = $row["type"];

    if($type == "Student")
    {
        $sql = "SELECT CRN FROM enrollment WHERE student_ID = '" . $ID . "' AND semester_ID >= '" . $semesterID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if(is_null($row))
        {
            $sql = "UPDATE user SET hidden = 1 WHERE ID = '" . $ID . "'";
            $conn->query($sql);

            $sql = "UPDATE student SET hidden = 1 WHERE ID = '" . $ID . "'";
            $conn->query($sql);

            header('Location: ViewAllUsers.php'); 
        }
        else
        {
            $_SESSION["Error"] = "Error: Cannot deactivate a student that is enrolled in current or future sections.";
            header('Location: ViewUserInfo.php'); 
        }
    }
    else if($type == "Faculty")
    {
        $sql = "SELECT CRN FROM section WHERE faculty_ID = '" . $ID . "' AND semester_ID >= '" . $semesterID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if(is_null($row))
        {
            $sql = "UPDATE user SET hidden = 1 WHERE ID = '" . $ID . "'";
            $conn->query($sql);

            $sql = "UPDATE faculty SET hidden = 1 WHERE ID = '" . $ID . "'";
            $conn->query($sql);

            header('Location: ViewAllUsers.php'); 
        }
        else
        {
            $_SESSION["Error"] = "Error: Cannot deactivate a faculty member that is instructing current or future sections.";
            header('Location: ViewUserInfo.php'); 
        }
    }
    else if($type == "Admin")
    {
        $sql = "UPDATE user SET hidden = 1 WHERE ID = '" . $ID . "'";
        $conn->query($sql);

        $sql = "UPDATE admin SET hidden = 1 WHERE ID = '" . $ID . "'";
        $conn->query($sql);

        header('Location: ViewAllUsers.php');
    }

    else if($type == "Research")
    {
        $sql = "UPDATE user SET hidden = 1 WHERE ID = '" . $ID . "'";
        $conn->query($sql);

        $sql = "UPDATE researchstaff SET hidden = 1 WHERE ID = '" . $ID . "'";
        $conn->query($sql);

        header('Location: ViewAllUsers.php');
    }
 
?>