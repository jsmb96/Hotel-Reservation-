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

    if($_POST["ID2"])
    {
        $sql = "INSERT INTO studenthold (student_ID,hold_Type)
            VALUES ('" . $_SESSION["StudentID"] . "','"
                    . $_POST["ID2"] . "')";
        $conn->query($sql);
    }
    else
    {
        $sql = "DELETE FROM studenthold WHERE studenthold_ID =  '" . $_POST["ID"] . "'";
        $conn->query($sql);
    }


    // $_POST["ID"] = $_SESSION["StudentID"];

    //echo $_SESSION["StudentID"];

    header('Location: UpdateStudentHold.php');
?>