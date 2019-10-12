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
    <title>Section Info</title>
</head>

<body>
    <h1>Detailed Section Information</h1>
    <a href="BackToHomepage.php">Back to Homepage</a><br>
    <a href="ViewMasterSchedule.php">View Master Schedule</a><br> 

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

        if($_SESSION["type"] == "Student")
        {
            echo "<a href='ViewPersonalSchedule.php'>View Personal Schedule</a><br>";
        }

        $currentSeason = NULL;
        $date = getdate();
        $currentYear = $date["year"];
        $today = new DateTime();

        $winterStart = new DateTime('January 01');
        $winterEnd = new DateTime('January 18');
        $springStart = new DateTime('January 19');
        $springEnd = new DateTime('May 19');
        $summerStart = new DateTime('May 20');
        $summerEnd = new DateTime('August 31');
        $fallStart = new DateTime('September 01');
        $fallEnd = new DateTime('December 31');

        if(($today >= $winterStart) && ($today < $winterEnd))
            $currentSeason = "Winter";
        else if(($today >= $springStart) && ($today < $springEnd))
            $currentSeason = "Spring";
        else if(($today >= $summerStart) && ($today < $summerEnd))
            $currentSeason = "Summer";
        else if(($today >= $fallStart) && ($today < $fallEnd))
            $currentSeason = "Fall";

        $sql = "SELECT semester_ID, deadline FROM semesteryear WHERE Year = '" . $currentYear . "'" . " AND Season = '" . $currentSeason . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $currentSemesterID = $row["semester_ID"];

        $CRN = isset($_POST['CRN']) ? $_POST['CRN'] : 0;
        if($CRN != 0)
            $_SESSION["CRN"] = $_POST["CRN"];
        else
            $CRN = $_SESSION["CRN"];

        $sql = "SELECT * FROM section WHERE CRN = '" . $CRN . "'";
        $result = $conn->query($sql);
        $sectionTable = $result->fetch_assoc();
    
        $courseID = $sectionTable["course_ID"];
        $sectionNum = $sectionTable["section_Number"];
        $cname = $sectionTable["course_Name"];
        $facultyID = $sectionTable["faculty_ID"];
        $enrolled = $sectionTable["enrolled"];
        $capacity = $sectionTable["capacity"];
        $timeslotID = $sectionTable["timeslot_ID"];
        $semesterID = $sectionTable["semester_ID"];
        $roomID = $sectionTable["room_ID"];

        $sql = "SELECT first_Name, last_Name FROM faculty WHERE ID = '" . $facultyID . "'";
        $result = $conn->query($sql);
        $facultyTable = $result->fetch_assoc();

        $fname = $facultyTable["first_Name"];
        $lname = $facultyTable["last_Name"];

        $sql = "SELECT * FROM timeslot WHERE timeslot_ID = '" . $timeslotID . "'";
        $result = $conn->query($sql);
        $timeslotTable = $result->fetch_assoc();

        $dayID = $timeslotTable["day_ID"];
        $periodID = $timeslotTable["period_ID"];

        $sql = "SELECT Days FROM days WHERE day_ID = '" . $dayID . "'";
        $result = $conn->query($sql);
        $daysTable = $result->fetch_assoc();

        $days = $daysTable["Days"];

        $sql = "SELECT period_Time FROM period WHERE period_ID = '" . $periodID . "'";
        $result = $conn->query($sql);
        $periodTable = $result->fetch_assoc();

        $period = $periodTable["period_Time"];

        $sql = "SELECT * FROM room WHERE room_ID = '" . $roomID . "'";
        $result = $conn->query($sql);
        $roomTable = $result->fetch_assoc();

        $roomNum = $roomTable["room_Number"];
        $buildingCode = $roomTable["building_Code"];

        $sql = "SELECT building_Name FROM building WHERE building_Code = '" . $buildingCode . "'";
        $result = $conn->query($sql);
        $buildingTable = $result->fetch_assoc();

        $buildingName = $buildingTable["building_Name"];

        $sql = "SELECT credits FROM course WHERE course_ID = '" . $courseID . "'";
        $result = $conn->query($sql);
        $courseTable = $result->fetch_assoc();

        $credits = $courseTable["credits"];

        $sql = "SELECT * FROM semesteryear WHERE semester_ID = '" . $semesterID . "'";
        $result = $conn->query($sql);
        $semesterTable = $result->fetch_assoc();

        $deadline = new DateTime($semesterTable["deadline"]);
        $season = $semesterTable["Season"];
        $year = $semesterTable["Year"];
    ?>

    <p><table>
        <tr>
            <th>CRN</th>
            <th>Course ID</th>
            <th>Section #</th>
            <th>Course Name</th>
            <th>Instructor</th>
            <th>Enrolled</th>
            <th>Capacity</th>
            <th>Day & Time</th>
            <th>Room #</th>
            <th>Building</th>
            <th>Credits</th>
            <th>Semester</th>
        </tr>
        <tr>
            <td><?php echo $CRN ?></td>
            <td><?php echo $courseID ?></td>
            <td><?php echo $sectionNum ?></td>
            <td><?php echo $cname ?></td>
            <td><?php echo $fname . " " . $lname ?></td>
            <td><?php echo $enrolled ?></td>
            <td><?php echo $capacity ?></td>
            <td><?php echo $days . " " . $period ?></td>
            <td><?php echo $roomNum ?></td>
            <td><?php echo $buildingName ?></td>
            <td><?php echo $credits ?></td>
            <td><?php echo $season . " " . $year ?></td>
        </tr>
    </table></p>

    <form method="post" action="ViewCourseInfo.php">
        <button name='ID' type='submit' value=' <?php echo $courseID; ?>'> View Course Info in Catalog </button>
    </form>

    <?php
        
        if($_SESSION["type"] == "Admin")
        {
            echo "<form method='post' action='UpdateSectionInfo.php'>
                <button name='CRN' type='submit' value='" . $CRN . "'>Update Section Info</button>
                </form>";
            echo "<form method='post' action='DeleteSection.php'>
                <button name='CRN' type='submit' value='" . $CRN . "'>Delete Section</button>
                </form>";
        }

        if($_SESSION["type"] == "Student")
        {
            $sql = "SELECT * FROM enrollment WHERE CRN = '" . $CRN . "' AND student_ID = '" . $_SESSION["ID"] . "'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $enrollCheck = $row["CRN"];
        }

        if(($_SESSION["type"] == "Student") && ($enrollCheck == NULL) && ($currentSemesterID <= $semesterID) && ($today <= $deadline))
        {
            echo "<form method='post' action='AddCourse.php'>
                    <input type = 'hidden' name = 'currentSemesterID' value = '" . $currentSemesterID . "'>
                    <input type = 'hidden' name = 'studentID' value = '" . $_SESSION["ID"] . "'>
                    <button name='CRN' type='submit' value=' " . $CRN . "'> Add Course to Personal Schedule </button>
                </form>";
        }
        else if(($_SESSION["type"] == "Student") && ($enrollCheck != NULL) && ($currentSemesterID <= $semesterID) && ($today <= $deadline))
        {
            echo "<form method='post' action='DropCourse.php'>
                    <input type = 'hidden' name = 'studentID' value = '" . $_SESSION["ID"] . "'>
                    <button name='CRN' type='submit' value=' " . $CRN . "'> Drop Course from Personal Schedule </button>
                </form>";
        }
    ?>

    <p>
        <?php 
        if($_SESSION["Error"] != "") echo $_SESSION["Error"];
        $_SESSION["Error"] = "";
        ?>
    </p>

</body>
</html>