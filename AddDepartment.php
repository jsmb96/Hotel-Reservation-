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
    <title>Add a department</title>
</head>

<body>
    <h1>Add a department</h1>
    <a href="BackToHomepage.php">Back to Homepage</a><br>
    <a href="ViewDepartmentInfo.php">View Department Info</a><br>

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

        $sql = "SELECT department_Name FROM department";
        $department = $conn->query($sql);

        $sql = "SELECT * FROM building";
        $building = $conn->query($sql);

        $sql = "SELECT ID, first_Name, last_Name FROM faculty";
        $faculty = $conn->query($sql);
    ?>
        <p>
            <label>
                Current department list:
                <select>
                    <?php while($row = mysqli_fetch_array($department))
                    {echo "<option>" . $row["department_Name"] . "</option>";} ?>
                </select>
            </label>
        </p>
    <form method="post" action="AddDepartmentPost.php">
        <p>
            <label>
                New department name:
                <input name="departmentName" type="text" size="20" maxlength="20" required>
            </label>
        </p>
        <p>
            <label>
                Main building:
                <select name="buildingCode">
                    <?php while($row = mysqli_fetch_array($building))
                    {
                        echo "<option value = '" . $row["building_Code"] . "'>" . $row["building_Name"] . "</option>";
                    } ?>
                </select>
            </label>
        </p>
        <p>
            <label>
                Head of department:
                <select name="facultyID">
                    <?php while($row = mysqli_fetch_array($faculty))
                    {
                        echo "<option value = '" . $row["ID"] . "'>" . $row["first_Name"] . " " . $row["last_Name"] . "</option>";
                    } ?>
                </select>
            </label>
        </p>
        <p>
            <input type="submit" value="Submit">
            <input type="reset" value="Clear">
        </p> 
    </form>

    <p>
        <?php 
        if($_SESSION["Error"] != "") echo $_SESSION["Error"]; 
        $_SESSION["Error"] = "";
        ?>
    </p>

</body>
</html>