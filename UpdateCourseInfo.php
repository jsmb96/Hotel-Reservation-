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
    <title>Edit Course Info</title>
</head>

<body>
    <h1>Update Course Info</h1>
    <a href="BackToHomepage.php">Back to Homepage</a><br>
    <a href="ViewCourseInfo.php">View Course Info</a><br>

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

        $sql = "SELECT * FROM course WHERE course_ID = '" . $_POST["ID"] . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
    
        $ID = $row["course_ID"];
        $cname = $row["course_Name"];
        $department = $row["department_Name"];
        $credits = $row["credits"];
        $description = $row["description"];

        $majorReq = False;
        $minorReq = False;
        $majorCourse = "";
        $minorCourse = "";

        $sql = "SELECT department_ID from department WHERE department_Name = '" . $department . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $departmentID = $row["department_ID"];

        $sql = "SELECT * FROM majorrequirements WHERE major_ID = '" . $departmentID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if($ID == $row["course_1"]) $majorCourse = "course_1";
        else if($ID == $row["course_2"]) $majorCourse = "course_2";
        else if($ID == $row["course_3"]) $majorCourse = "course_3";
        else if($ID == $row["course_4"]) $majorCourse = "course_4";
        else if($ID == $row["course_5"]) $majorCourse = "course_5";
        else $majorCourse = "";

        if($majorCourse != "")
            $majorReq = True;

        $sql = "SELECT * FROM minorrequirements WHERE minor_ID = '" . $departmentID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if($ID == $row["course_1"]) $minorCourse = "course_1";
        else if($ID == $row["course_2"]) $minorCourse = "course_2";
        else if($ID == $row["course_3"]) $minorCourse = "course_3";
        else $minorCourse = "";

        if($minorCourse != "")
            $minorReq = True;

        $sql = "SELECT * FROM prerequisite WHERE course_ID = '" . $ID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $prereq1 = $row["prereq_1"];
        $prereq2 = $row["prereq_2"];
        $prereq3 = $row["prereq_3"];
        $prereq4 = $row["prereq_4"];
        $prereq5 = $row["prereq_5"];

        $sql = "SELECT department_Name FROM department";
        $departmentList = $conn->query($sql);

        $sql = "SELECT course_ID, course_Name, department_Name FROM course";
        $courseList = $conn->query($sql);
        $courseList2 = $conn->query($sql);
        $courseList3 = $conn->query($sql);
        $courseList4 = $conn->query($sql);
        $courseList5 = $conn->query($sql);
    ?>

    <form method="post" action="UpdateCourseInfoPost.php">
        <input type ="hidden" name ="ID" value= '<?php echo $ID ?>' >
        <input type ="hidden" name ="cnameOld" value= '<?php echo $cname ?>' >
        <input type ="hidden" name ="majorCourse" value= '<?php echo $majorCourse ?>' >
        <input type ="hidden" name ="minorCourse" value= '<?php echo $minorCourse ?>' >
        <p>
            <label>
                Department:
                <select name="department">
                    <?php while($row = mysqli_fetch_array($departmentList))
                    {
                        if($department == $row["department_Name"])
                            echo "<option selected>" . $row["department_Name"] . "</option>"; 
                        else
                            echo "<option>" . $row["department_Name"] . "</option>";
                    } ?>
                </select>
            </label>
        </p>
        <p>
            <label>
                Course Name:
                <input name="cname" type="text" size="20" maxlength="20" value= '<?php echo $cname ?>' required>
            </label>
        </p>
        <p>
            <label>
                Credits
                <input name="credits" type="number" size="1"  value ="4" min="0" max="9" value= '<?php echo $credits ?>' required>
            </label>
        </p>
        <p>
            <label>
                Description
                <input name="description" type="text" size="25" maxlength="255" value= '<?php echo $description ?>' required>
            </label>
        </p>
        <p>
            <label>
                Required for Major
                <input type="checkbox" name="majorReq" <?php if($majorReq == True) echo "checked" ?> >
            </label>
        </p>
        <p>
            <label>
                Required for Minor
                <input type="checkbox" name="minorReq" <?php if($minorReq == True) echo "checked" ?> >
            </label>
        </p>
        <p>
            <label>
                Prerequisite 1:
                <select name="prereq1">
                    <option selected value = '0'>None</option>
                    <?php while($row = mysqli_fetch_array($courseList))
                    { 
                        if($prereq1 == $row["course_ID"])
                            echo "<option selected value = '" . $row["course_ID"] . "'>" . $row["department_Name"] . " - " . $row["course_Name"] . "</option>";
                        else
                            echo "<option value = '" . $row["course_ID"] . "'>" . $row["department_Name"] . " - " . $row["course_Name"] . "</option>";
                    } ?>
                </select>
            </label>
        </p>
        <p>
            <label>
                Prerequisite 2:
                <select name="prereq2">
                    <option selected value = '0'>None</option>
                    <?php while($row = mysqli_fetch_array($courseList2))
                    {
                        if($prereq2 == $row["course_ID"])
                            echo "<option selected value = '" . $row["course_ID"] . "'>" . $row["department_Name"] . " - " . $row["course_Name"] . "</option>";
                        else
                            echo "<option value = '" . $row["course_ID"] . "'>" . $row["department_Name"] . " - " . $row["course_Name"] . "</option>";
                    } ?>
                </select>
            </label>
        </p>
        <p>
            <label>
                Prerequisite 3:
                <select name="prereq3">
                    <option selected value = '0'>None</option>
                    <?php while($row = mysqli_fetch_array($courseList3))
                    {
                        if($prereq3 == $row["course_ID"])
                            echo "<option selected value = '" . $row["course_ID"] . "'>" . $row["department_Name"] . " - " . $row["course_Name"] . "</option>";
                        else
                            echo "<option value = '" . $row["course_ID"] . "'>" . $row["department_Name"] . " - " . $row["course_Name"] . "</option>";
                    } ?>
                </select>
            </label>
        </p>
        <p>
            <label>
                Prerequisite 4:
                <select name="prereq4">
                    <option selected value = '0'>None</option>
                    <?php while($row = mysqli_fetch_array($courseList4))
                    {
                        if($prereq4 == $row["course_ID"])
                            echo "<option selected value = '" . $row["course_ID"] . "'>" . $row["department_Name"] . " - " . $row["course_Name"] . "</option>";
                        else
                            echo "<option value = '" . $row["course_ID"] . "'>" . $row["department_Name"] . " - " . $row["course_Name"] . "</option>";
                    } ?>
                </select>
            </label>
        </p>
        <p>
            <label>
                Prerequisite 5:
                <select name="prereq5">
                    <option selected value = '0'>None</option>
                    <?php while($row = mysqli_fetch_array($courseList5))
                    {
                        if($prereq5 == $row["course_ID"])
                            echo "<option selected value = '" . $row["course_ID"] . "'>" . $row["department_Name"] . " - " . $row["course_Name"] . "</option>";
                        else
                            echo "<option value = '" . $row["course_ID"] . "'>" . $row["department_Name"] . " - " . $row["course_Name"] . "</option>";
                    } ?>
                </select>
            </label>
        </p>

        <p>
            <input type="submit" value="Submit">
            <input type="reset" value="Reset">
        </p> 
    </form>

</body>
</html>