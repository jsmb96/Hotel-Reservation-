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
    <title>Course History</title>
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

        $semesterID = $yearTable["semester_ID"]; // current semester 
        $semesterIDold = $semesterID - 2;

        $studentID = isset($_POST['studentID']) ? $_POST['studentID'] : $_SESSION["ID"];

        echo "<h1>Course History";
        if($_SESSION["type"] == "Admin" || ($_SESSION["type"] == "Faculty"))
        {
            $sql = "SELECT first_Name, last_Name FROM student WHERE ID = '" . $studentID . "'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            $firstName = $row["first_Name"];
            $lastName = $row["last_Name"];

            echo " - " . $firstName . " " . $lastName;
        }
        echo "</h1>";

        $sql = "SELECT DISTINCT semester_ID FROM enrollment WHERE student_ID = '" . $studentID . "'";
        $resultOption = $conn->query($sql);

        $selectedSemester = isset($_POST['semester']) ? $_POST['semester'] : $semesterIDold;

        $sql = "SELECT * FROM semesteryear WHERE semester_ID = '" . $selectedSemester . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $selectedSeason = $row["Season"];
        $selectedYear = $row["Year"];

        $sql = "SELECT CRN, midterm_Grade, final_Grade FROM enrollment WHERE student_ID = '" . $studentID . "' AND semester_ID = '" . $selectedSemester . "'";
        $schedule = $conn->query($sql);

    ?>

    <a href="BackToHomepage.php">Back to Homepage</a><br>
    <a href="ViewMasterSchedule.php">View Master Schedule</a><br>
    <?php
        if(($_SESSION["type"] == "Admin") || ($_SESSION["type"] == "Faculty"))
        { echo "<a href='ViewUserInfo.php'>View User Info</a><br>";}
    ?>
    <p>
        <form method="post" action="ViewCourseHistory.php">
            <input type = 'hidden' name = 'studentID' value = <?php echo $studentID ?> >
            <label>Semester:
                <select name="semester">
                    <option value = 0>None</option>
                    <?php while($row = mysqli_fetch_array($resultOption))
                    { 
                        if($row["semester_ID"] < $semesterID)
                        {
                            $sql = "SELECT Season, Year FROM semesteryear WHERE semester_ID = '" . $row["semester_ID"] . "'";
                            $result = $conn->query($sql);
                            $row2 = $result->fetch_assoc();

                            if($row["semester_ID"] == $selectedSemester)
                                echo "<option selected ";
                            else
                                echo "<option ";
                            echo "value = '" . $row["semester_ID"] . "'>" . $row2["Season"] . " " . $row2["Year"] . "</option>"; 
                        }                              
                    } ?>
                </select>
            </label>
            <input type="submit" value="Update">
        </form>
    </p>

    <?php

        if(mysqli_num_rows($schedule)==0)
            echo "No results found.";
        else
        {
            echo "<h2>" . $selectedSeason . " " . $selectedYear . "</h2>";
            
            $totalCredits = 0.0;
            $totalGrade = 0.0;

            echo "<p><table><tr>
                <th>Course Name</th>
                <th>Midterm</th>
                <th>Final</th>
                <th>Instructor</th>
                <th>Day & Time</th>
                <th>Room #</th>
                <th>Building</th>
                <th>More Info</th>";
            if($_SESSION["type"] == "Admin")
                {echo "<th>Update Grades</th>";}
            echo "</tr>";
            while($row = mysqli_fetch_array($schedule))
            {
                $midtermGrade = $row["midterm_Grade"];
                $finalGrade = $row["final_Grade"];

                $sql = "SELECT * FROM section WHERE CRN = '" . $row["CRN"] . "'";
                $result = $conn->query($sql);
                $sectionTable = $result->fetch_assoc();
            
                $CRN = $sectionTable["CRN"];
                $courseID = $sectionTable["course_ID"];
                $cname = $sectionTable["course_Name"];
                $facultyID = $sectionTable["faculty_ID"];
                $timeslotID = $sectionTable["timeslot_ID"];
                $roomID = $sectionTable["room_ID"];

                $sql = "SELECT credits FROM course WHERE course_ID = '" . $courseID . "'";
                $result = $conn->query($sql);
                $creditTable = $result->fetch_assoc();
                $credits = $creditTable["credits"];
                $totalCredits += $credits;

                $gradeValue = array
                (   array("A","A-","B+","B","B-","C+","C","C-","D+","D","D-","F"),
                    array(4.0, 3.7, 3.3, 3.0, 2.7, 2.3, 2.0, 1.7, 1.3, 1.0, 0.7, 0.0)
                );

                for($i = 0; $i < 12; $i++)
                {
                    if($finalGrade == $gradeValue[0][$i])
                        $totalGrade += ($gradeValue[1][$i] * $credits);
                }

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
                        <td>" . $midtermGrade . "</td>
                        <td>" . $finalGrade . "</td>
                        <td>" . $fname . " " . $lname . "</td>
                        <td>" . $days . " " . $period . "</td>
                        <td>" . $roomNum . "</td>
                        <td>" . $buildingName . "</td>
                        <td><form method='post' action='ViewSectionInfo.php'>
                        <button name='CRN' type='submit' value='" . $CRN . "'> View</button>
                        </form></td>";
                if($_SESSION["type"] == "Admin")
                {
                    echo "<td><form method='post' action='UpdateStudentGrades.php'>
                        <input type = 'hidden' name = 'CRN' value = '" . $CRN . "'>
                        <button name='ID' type='submit' value='" . $studentID . "'>Update</button>
                        </form></td>";
                }
                echo "</tr>";
            }
            echo "</table><p></form>";
            
            $GPA = round(($totalGrade / $totalCredits),2);
            echo "<h3> Semester GPA: " . $GPA . "</h3>";
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