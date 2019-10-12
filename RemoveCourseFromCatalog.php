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

    $ID = $_POST["ID"];

    $sql = "UPDATE course SET hidden = 1 WHERE course_ID = '" . $ID . "'";
    $conn->query($sql);

    header('Location: ViewCourseCatalog.php');
 
?>