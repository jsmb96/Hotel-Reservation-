<?php
session_start();
$ID = $_POST["ID"];
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
    <title>Reset Password</title>
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

        if($_SESSION["type"] != "Admin")
            {header('Location: UserHomepage.php');}

        $sql = "SELECT first_Name, last_Name FROM user WHERE ID = '" . $_POST["ID"] . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
    
        $fname = $row["first_Name"];
        $lname = $row["last_Name"];
        echo "<h1> Reset " . $fname . " " . $lname . "'s Password</h1>";

    ?>

    <a href="BackToHomepage.php">Back to Homepage</a><br>
    <a href="ViewUserInfo.php">View User Info</a><br>

    <form method="post" action="ResetUserPasswordPost.php">
        <p>
            <input type ="hidden" name ="ID" value="<?php echo $ID ?>">
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