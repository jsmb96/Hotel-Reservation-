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
    <title>Update Section Info</title>
</head>

<body>
    <h1>Update Section Info</h1>
    <a href="BackToHomepage.php">Back to Homepage</a><br>
    <a href="ViewSectionInfo.php">View Section Info</a><br>

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

        $CRN = $_POST["CRN"];

        $sql = "SELECT * FROM section WHERE CRN = '" . $CRN . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $facultyID = $row["faculty_ID"];
        $capacity = $row["capacity"];
        $timeslotID = $row["timeslot_ID"];
        $roomID = $row["room_ID"];

        $courseID = $row["course_ID"];
        $courseName = $row["course_Name"];
        $sectionNum = $row["section_Number"];
        $department = $row["department_Name"];
        $semesterID = $row["semester_ID"];

        $sql = "SELECT * FROM timeslot WHERE timeslot_ID = '" . $timeslotID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $dayID = $row["day_ID"];
        $periodID = $row["period_ID"];

        $sql = "SELECT department_Name FROM department";
        $departmentList = $conn->query($sql);

        $sql = "SELECT course_ID, course_Name FROM course";
        $courseList = $conn->query($sql);

        $sql = "SELECT * FROM semesteryear";
        $semesterList = $conn->query($sql);

        $sql = "SELECT ID, first_Name, last_Name, department_Name FROM faculty";
        $facultyList = $conn->query($sql);

        $sql = "SELECT * FROM days";
        $daysList = $conn->query($sql);

        $sql = "SELECT * FROM period";
        $periodList = $conn->query($sql);

        $sql = "SELECT * FROM room";
        $roomList = $conn->query($sql);
    ?>

    <form method="post" action="UpdateSectionInfoPost.php">
        <input type ="hidden" name ="CRN" value= '<?php echo $CRN ?>' >
        <p>
            <label>
                Department:
                <select name="department" disabled>
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
                Course:
                <select name="courseID" disabled>
                    <?php while($row = mysqli_fetch_array($courseList))
                    {
                        if($courseName == $row["course_Name"])
                            echo "<option selected value = '" . $row["course_ID"] . "'>" . $row["course_Name"] . "</option>";
                        else
                            echo "<option value = '" . $row["course_ID"] . "'>" . $row["course_Name"] . "</option>";
                    } ?>
                </select>
            </label>
        </p>
        <p>
            <label>
                Section #:
                <input name="sectionNum" type="number" size="1"  value ="<?php echo $sectionNum ?>" min="0" max="9" readonly required>
            </label>
        </p>
        
        <p>
            <label>
                Instructor:
                <select name="facultyID">
                    <?php while($row = mysqli_fetch_array($facultyList))
                    {
                        if($facultyID == $row["ID"])
                            echo "<option selected value = '" . $row["ID"] . "'>" . $row["first_Name"] . " " . $row["last_Name"] . " (" . $row["department_Name"] . ")</option>";
                        else
                            echo "<option value = '" . $row["ID"] . "'>" . $row["first_Name"] . " " . $row["last_Name"] . " (" . $row["department_Name"] . ")</option>";
                    } ?>
                </select>
            </label>
        </p>
        <p>
            <label>
                Capacity:
                <input name="capacity" type="number" size="2" value="<?php echo $capacity ?>" min="0" max="40" required>
            </label>
        </p>
        <p>
            <label>
                Days:
                <select name="dayID">
                    <?php while($row = mysqli_fetch_array($daysList))
                    {
                        if($dayID == $row["day_ID"])
                            echo "<option selected value = '" . $row["day_ID"] . "'>" . $row["Days"] . "</option>";
                        else
                            echo "<option value = '" . $row["day_ID"] . "'>" . $row["Days"] . "</option>";
                    } ?>
                </select>
            </label>
        </p>
        <p>
            <label>
                Period:
                <select name="periodID">
                    <?php while($row = mysqli_fetch_array($periodList))
                    {
                        if($periodID == $row["period_ID"])
                            echo "<option selected value = '" . $row["period_ID"] . "'>" . $row["period_Time"] . "</option>";
                        else
                            echo "<option value = '" . $row["period_ID"] . "'>" . $row["period_Time"] . "</option>";
                    } ?>
                </select>
            </label>
        </p>
        <p>
            <label>
                Semester:
                <select name="semesterID" disabled>
                    <?php while($row = mysqli_fetch_array($semesterList))
                    {
                        if($semesterID == $row["semester_ID"])
                            echo "<option selected value = '" . $row["semester_ID"] . "'>" . $row["Season"] . " " . $row["Year"] . "</option>";
                        else
                            echo "<option value = '" . $row["semester_ID"] . "'>" . $row["Season"] . " " . $row["Year"] . "</option>";
                    } ?>
                </select>
            </label>
        </p>
        <p>
            <label>
                Building/Room:
                <select name="roomID">
                    <?php while($row = mysqli_fetch_array($roomList))
                    {
                        $sql = "SELECT building_Name FROM building WHERE building_Code = '" . $row["building_Code"] . "'";
                        $result = $conn->query($sql);
                        $row2 = $result->fetch_assoc();

                        if($roomID == $row["room_ID"])
                            echo "<option selected value = '" . $row["room_ID"] . "'>" . $row2["building_Name"] . ": " . $row["room_Number"] . "</option>";
                        else
                            echo "<option value = '" . $row["room_ID"] . "'>" . $row2["building_Name"] . ": " . $row["room_Number"] . "</option>";
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