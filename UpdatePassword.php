<?php
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
    <title>Update Password</title>
</head>

<body>
    <h1>Update Password</h1>
    <a href="BackToHomepage.php">Back to Homepage</a><br>
    <a href="ViewPersonalInfo.php">View Personal Info</a><br> 

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

        if(is_null($_SESSION["ID"]))
            header('Location: Login.php');
    ?>

    <form method="post" action="UpdatePasswordPost.php">
        <p>
            <label>
                Current Password:
                <input name="oldPassword" type="password" size="25" required>
            </label>
        </p>
        <p>
            <label>
                New Password:
                <input name="newPassword" type="password" size="25" required>
            </label>
        </p>
        <p>
            <label>
                Verify New Password:
                <input name="newPassword2" type="password" size="25" required>
            </label>
        </p>

        <p>
            <input type="submit" value="Submit">
            <input type="reset" value="Clear">
        </p> 
    </form>

</body>
</html>