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
    <title>Add course to catalog</title>
</head>
    <title>Add course to catalog</title>
</head>

<body>
    <h1>Add course to catalog</h1>
    <a href="BackToHomepage.php">Back to Homepage</a><br>
    <a href="ViewCourseCatalog.php">View Course Catalog</a><br>

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

        $sql = "SELECT department_Name FROM department WHERE hidden = 0";
        $departmentList = $conn->query($sql);

        $sql = "SELECT course_ID, course_Name, department_Name FROM course";
        $courseList = $conn->query($sql);
        $courseList2 = $conn->query($sql);
        $courseList3 = $conn->query($sql);
        $courseList4 = $conn->query($sql);
        $courseList5 = $conn->query($sql);
    ?>

    <form method="post" action="AddCourseToCatalogPost.php">
        <p>
            <label>
                Department:
                <select name="department">
                    <?php while($row = mysqli_fetch_array($departmentList))
                    {echo "<option>" . $row["department_Name"] . "</option>";} ?>
                </select>
            </label>
        </p>

        <p>
            <label>
                Course Name:
                <input name="cname" type="text" size="20" maxlength="20" required>
            </label>
        </p>
        <p>
            <label>
                Credits
                <input name="credits" type="number" size="1"  value ="4" min="0" max="9" required>
            </label>
        </p>
        <p>
            <label>
                Description
                <input name="description" type="text" size="25" maxlength="255" required>
            </label>
        </p>
        <p>
            <label>
                Required for Major
                <input type="checkbox" name="majorReq">
            </label>
        </p>
        <p>
            <label>
                Required for Minor
                <input type="checkbox" name="minorReq">
            </label>
        </p>
        <p>
            <label>
                Prerequisite 1:
                <select name="prereq1">
                    <option selected value = '0'>None</option>
                    <?php while($row = mysqli_fetch_array($courseList))
                    {echo "<option value = '" . $row["course_ID"] . "'>" . $row["department_Name"] . " - " . $row["course_Name"] . "</option>";} ?>
                </select>
            </label>
        </p>
        <p>
            <label>
                Prerequisite 2:
                <select name="prereq2">
                    <option selected value = '0'>None</option>
                    <?php while($row = mysqli_fetch_array($courseList2))
                    {echo "<option value = '" . $row["course_ID"] . "'>" . $row["department_Name"] . " - " . $row["course_Name"] . "</option>";} ?>
                </select>
            </label>
        </p>
        <p>
            <label>
                Prerequisite 3:
                <select name="prereq3">
                    <option selected value = '0'>None</option>
                    <?php while($row = mysqli_fetch_array($courseList3))
                    {echo "<option value = '" . $row["course_ID"] . "'>" . $row["department_Name"] . " - " . $row["course_Name"] . "</option>";} ?>
                </select>
            </label>
        </p>
        <p>
            <label>
                Prerequisite 4:
                <select name="prereq4">
                    <option selected value = '0'>None</option>
                    <?php while($row = mysqli_fetch_array($courseList4))
                    {echo "<option value = '" . $row["course_ID"] . "'>" . $row["department_Name"] . " - " . $row["course_Name"] . "</option>";} ?>
                </select>
            </label>
        </p>
        <p>
            <label>
                Prerequisite 5:
                <select name="prereq5">
                    <option selected value = '0'>None</option>
                    <?php while($row = mysqli_fetch_array($courseList5))
                    {echo "<option value = '" . $row["course_ID"] . "'>" . $row["department_Name"] . " - " . $row["course_Name"] . "</option>";} ?>
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