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
    $facultyID = $_POST["facultyID"];
    $capacity = $_POST["capacity"];
    $dayID = $_POST["dayID"];
    $periodID = $_POST["periodID"];
    $roomID = $_POST["roomID"];

    $sql = "SELECT timeslot_ID FROM timeslot WHERE day_ID = '" . $dayID . "' AND period_ID = '" . $periodID . "'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $timeslotID = $row["timeslot_ID"];

    $sql = "UPDATE section SET faculty_ID = '" . $facultyID . "' WHERE CRN = '" . $CRN . "'";
    $conn->query($sql);

    $sql = "UPDATE section SET capacity = '" . $capacity . "' WHERE CRN = '" . $CRN . "'";
    $conn->query($sql);

    $sql = "UPDATE section SET timeslot_ID = '" . $timeslotID . "' WHERE CRN = '" . $CRN . "'";
    $conn->query($sql);

    $sql = "UPDATE section SET room_ID = '" . $roomID . "' WHERE CRN = '" . $CRN . "'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION["Error"] = "Section updated successfully.";
    } else {
        $_SESSION["Error"] = "Error: " . $sql . "<br>" . $conn->error;
    }

    header('Location: ViewSectionInfo.php');
?>