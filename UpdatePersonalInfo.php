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
    <title>Update Personal Info</title>
</head>

<body>
    <h1>Update Personal Info</h1>
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

        $sql = "SELECT * FROM user WHERE ID = '" . $_SESSION["ID"] . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
    
        $ID = $row["ID"];
        $type = $row["type"];
        $email = $row["email"];
        $fname = $row["first_Name"];
        $mname = $row["middle_Initial"];
        $lname = $row["last_Name"];
        $phone = $row["phone_Number"];
        $dob = $row["DOB"];
    ?>

    <form method="post" action="UpdatePersonalInfoPost.php">
        <p>
            <label>
                First Name:
                <input name="fname" type="text" size="25" maxlength="255" required value="<?php echo $fname ?>">
            </label>
        </p>
        <p>
            <label>
                Middle Initial:
                <input name="mname" type="text" size="1" maxlength="1" required value="<?php echo $mname ?>">
            </label>
        </p>
        <p>
            <label>
                Last Name:
                <input name="lname" type="text" maxlength="255" required size="25" value="<?php echo $lname ?>">
            </label>
        </p>
         <p>
            <label>
                Phone Number:
                <input name="phone" type="text" size="10" maxlength="10" value="<?php echo $phone ?>">
            </label>
        </p>
        <p>
            <label>
                Date of Birth:
                <input name="dob" type="date" size="10" value="<?php echo $dob ?>">
            </label>
        </p>

        <p>
            <input type="submit" value="Submit">
            <input type="reset" value="Reset">
        </p> 
    </form>

</body>
</html>