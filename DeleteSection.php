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

    $sql = "SELECT enrolled FROM section WHERE CRN = '" .  $CRN . "'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $enrolled = $row["enrolled"];

    if($enrolled == 0)
    {
        $sql = "DELETE FROM section WHERE CRN = '" . $CRN . "'";
        $conn->query($sql);

        $sql = "DELETE FROM masterschedule WHERE CRN = '" . $CRN . "'";
        $conn->query($sql);

        header('Location: ViewMasterSchedule.php');
    }
    else
    {
        $_SESSION["Error"] = "Error: Cannot delete a section that has students enrolled in it.";
        header('Location: ViewSectionInfo.php');
    }

    
?>