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
    <title>Update Department Info</title>
</head>

<body>
    <h1>Update Department Info</h1>
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

        $departmentID = $_POST["ID"];

        $sql = "SELECT * FROM department WHERE department_ID = '" . $departmentID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $buildingCode = $row["building_Code"];
        $facultyID = $row["faculty_ID"];
        $departmentName = $row["department_Name"];

        $sql = "SELECT * FROM building";
        $building = $conn->query($sql);

        $sql = "SELECT ID, first_Name, last_Name FROM faculty";
        $faculty = $conn->query($sql);
    ?>

    <form method="post" action="UpdateDepartmentInfoPost.php">
        <input type="hidden" name="ID" value="<?php echo $departmentID ?>">
        <input type="hidden" name="departmentNameOld" value="<?php echo $departmentName ?>">
        <p>
            <label>
                Department name:
                <input name="departmentName" type="text" size="20" maxlength="20" value = '<?php echo $departmentName ?>'required>
            </label>
        </p>
        <p>
            <label>
                Main building:
                <select name="buildingCode">
                    <?php while($row = mysqli_fetch_array($building))
                    {
                        if($buildingCode == $row["building_Code"])
                            echo "<option selected value = '" . $row["building_Code"] . "'>" . $row["building_Name"] . "</option>";
                        else
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
                        if($facultyID == $row["ID"])
                            echo "<option selected value = '" . $row["ID"] . "'>" . $row["first_Name"] . " " . $row["last_Name"] . "</option>";
                        else
                            echo "<option value = '" . $row["ID"] . "'>" . $row["first_Name"] . " " . $row["last_Name"] . "</option>";
                    } ?>
                </select>
            </label>
        </p>
        <p>
            <input type="submit" value="Submit">
            <input type="reset" value="Reset">
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