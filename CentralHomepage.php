<?php
session_start();
session_unset(); 
session_destroy();

session_start();
?>

<link rel="stylesheet" href="stylesheet.css">
<!doctype html>
<html>

<head>
    <ul>
    <li><img src = "UpdatedLogo.png" style="width:136;height:131;padding:10px;"></li>
    <!-- <li><a class="active" href="#home">Home</a></li> -->
    <li><div class="heading">The University of Western Long Island</div></li>
    </ul>
    <title>Central Homepage</title>
</head>

<body>
    <?php
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

        $_SESSION["type"] = "Visitor";
        $_SESSION["Error"] = "";

        echo "<h1> Central Homepage </h1>"; 

        echo "<a href='Login.php'>Login</a><br>";
        echo "<br>";
        echo "<a href='ViewCourseCatalog.php'>View Course Catalog</a><br>";
        echo "<a href='ViewDepartmentInfo.php'>View Department Info</a><br>";
        echo "<br>";
        echo "<a href='ViewMasterSchedule.php'>View Master Schedule</a><br>";
        echo "<br><br><br>";

    ?>
</body>
</html>