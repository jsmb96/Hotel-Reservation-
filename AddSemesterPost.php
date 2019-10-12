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

    $season = $_POST["season"];
    $year = $_POST["year"];

    $sql = "SELECT semester_ID FROM semesteryear WHERE Season = '" . $season . "' AND Year = '" . $year . "'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if(is_null($row["semester_ID"]))
    {
        $sql = "INSERT INTO semesteryear (Season, Year)
            VALUES ('" . $season . "','" . $year. "')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION["Error"] = "New record created successfully";
        } else {
            $_SESSION["Error"] = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    else
    {
        $_SESSION["Error"] = "Error: semester already exists.";
    }

    header('Location: AddSemester.php');

?>