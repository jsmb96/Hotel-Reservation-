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
    <title>Personal Schedule</title>
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

        if(($_SESSION["type"] != "Admin") && ($_SESSION["type"] != "Faculty") && ($_SESSION["type"] != "Student"))
            {header('Location: UserHomepage.php');}

        $studentID = isset($_POST['studentID']) ? $_POST['studentID'] : $_SESSION["ID"];

        echo "<h1>Personal Schedule";
        if(($_SESSION["type"] == "Admin") || ($_SESSION["type"] == "Faculty"))
        {
            $sql = "SELECT first_Name, last_Name FROM student WHERE ID = '" . $studentID . "'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            $firstName = $row["first_Name"];
            $lastName = $row["last_Name"];

            echo " - " . $firstName . " " . $lastName;
        }
        echo "</h1>";

        $season = NULL;
        $date = getdate();
        $year = $date["year"];
        $today = new DateTime();

        $winterStart = new DateTime('January 01');
        $winterEnd = new DateTime('January 18');
        $springStart = new DateTime('January 19');
        $springEnd = new DateTime('May 19');
        $summerStart = new DateTime('May 20');
        $summerEnd = new DateTime('August 31'); //15
        $fallStart = new DateTime('September 01');
        $fallEnd = new DateTime('December 31'); //22

        if(($today >= $winterStart) && ($today < $winterEnd))
            $season = "Winter";
        else if(($today >= $springStart) && ($today < $springEnd))
            $season = "Spring";
        else if(($today >= $summerStart) && ($today < $summerEnd))
            $season = "Summer";
        else if(($today >= $fallStart) && ($today < $fallEnd))
            $season = "Fall";

        $sql = "SELECT semester_ID FROM semesteryear WHERE Year = '" . $year . "'" . " AND Season = '" . $season . "'";
        $result = $conn->query($sql);
        $yearTable = $result->fetch_assoc();

        $semesterID = $yearTable["semester_ID"];
        $semesterID1 = $semesterID + 1;
        $semesterID2 = $semesterID + 2;

        if($season == "Fall" || $season == "Spring")
            $sql = "SELECT * FROM semesteryear WHERE semester_ID = '" . $semesterID . "' OR semester_ID = '" . $semesterID1 . "' OR semester_ID = '" . $semesterID2 . "'";
        else if($season == "Summer" || $season == "Winter")
            $sql = "SELECT * FROM semesteryear WHERE semester_ID = '" . $semesterID . "' OR semester_ID = '" . $semesterID1 . "'";

        $resultOption = $conn->query($sql);

        $selectedSemester = isset($_POST['semester']) ? $_POST['semester'] : $semesterID;

        $sql = "SELECT * FROM semesteryear WHERE semester_ID = '" . $selectedSemester . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $selectedSeason = $row["Season"];
        $selectedYear = $row["Year"];

        $sql = "SELECT CRN FROM enrollment WHERE student_ID = '" . $studentID . "' AND semester_ID = '" . $selectedSemester . "'";
        $schedule = $conn->query($sql);

    ?>

    <a href="BackToHomepage.php">Back to Homepage</a><br>
    <a href="ViewMasterSchedule.php">View Master Schedule</a><br>

    <?php
        if(($_SESSION["type"] == "Admin") || ($_SESSION["type"] == "Faculty"))
        { echo "<a href='ViewUserInfo.php'>View User Info</a><br>";}
    ?>

    <p>
        <form method="post" action="ViewPersonalSchedule.php">
            <input type = 'hidden' name = 'studentID' value = <?php echo $studentID ?> >
            <label>Semester:
                <select name="semester">
                    <?php while($row = mysqli_fetch_array($resultOption))
                    { 
                        if($row["semester_ID"] == $selectedSemester)
                            echo "<option selected ";
                        else
                            echo "<option ";
                        echo "value = '" . $row["semester_ID"] . "'>"
                     . $row["Season"] . " " . $row["Year"] . "</option>";
                    } ?>
                </select>
            </label>
            <input type="submit" value="Update">
        </form>
    </p>

    <h2><?php echo $selectedSeason . " " . $selectedYear ?></h2>

    <?php
        if(mysqli_num_rows($schedule)==0)
            echo "No results found.";
        else
        {
            echo "<p><table><tr>
                <th>Course Name</th>
                <th>Instructor</th>
                <th>Day & Time</th>
                <th>Room #</th>
                <th>Building</th>
                <th>More Info</th>";
            if($_SESSION["type"] == "Admin")
                {echo "<th>Action</th>";}
            echo "</tr>";
            while($row = mysqli_fetch_array($schedule))
            {
                $sql = "SELECT * FROM section WHERE CRN = '" . $row["CRN"] . "'";
                $result = $conn->query($sql);
                $sectionTable = $result->fetch_assoc();
            
                $CRN = $sectionTable["CRN"];
                $cname = $sectionTable["course_Name"];
                $facultyID = $sectionTable["faculty_ID"];
                $timeslotID = $sectionTable["timeslot_ID"];
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

                echo "<tr><td>" . $cname . "</td>
                        <td>" . $fname . " " . $lname . "</td>
                        <td>" . $days . " " . $period . "</td>
                        <td>" . $roomNum . "</td>
                        <td>" . $buildingName . "</td>
                        <td><form method='post' action='ViewSectionInfo.php'>
                        <button name='CRN' type='submit' value='"
                        . $CRN . "'> View</button></form></td>";
                if($_SESSION["type"] == "Admin")
                {
                    echo "<td><form method='post' action='DropCourse.php'>
                    <input type = 'hidden' name = 'studentID' value = '" . $studentID . "'>
                    <button name='CRN' type='submit' value=' " . $CRN . "'> Drop</button>
                    </form></td>";
                }

                echo "</tr>";
            }
            echo "</table></p>";
        }
        if($_SESSION["type"] == "Admin")
        {
            $sql = "SELECT * FROM section WHERE semester_ID = '" . $selectedSemester . "'";
            $sectionList = $conn->query($sql);

            echo "<p><form method='post' action='AddCourse.php'>";
            echo "<input type = 'hidden' name = 'currentSemesterID' value = '" . $semesterID . "'>";
            echo "<input type = 'hidden' name = 'studentID' value = '" . $studentID . "'>";
            echo "<label>Add Section to Schedule: <select name='CRN'>";
            echo "<option value = 0>None</option>";
            while($row = mysqli_fetch_array($sectionList))
            { 
                echo "<option value = '" . $row["CRN"] . "'>" . $row["CRN"] . " " 
                    . $row["course_Name"] . " Section " . $row["section_Number"] . "</option>";
            }
            echo "</select></label>
                <input type='submit' value='Add'>
                </form></p>";
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