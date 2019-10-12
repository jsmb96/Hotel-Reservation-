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
    <title>Master Schedule</title>
</head>

<body>
    <h1>Master Schedule</h1>
    <a href="BackToHomepage.php">Back to Homepage</a><br>

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

        $value = isset($_POST['search']) ? $_POST['search'] : "";
        $column = isset($_POST['in']) ? $_POST['in'] : "CRN";
        $selectedSemester = isset($_POST['semester']) ? $_POST['semester'] : $semesterID;
        $columnName = "";

        if($column == "course_ID") $columnName = "Course ID";
        else if($column == "course_Name") $columnName = "Course Name";
        else $columnName = $column;

        $sql = "SELECT * FROM semesteryear WHERE semester_ID = '" . $selectedSemester . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $selectedSeason = $row["Season"];
        $selectedYear = $row["Year"];

        if(($column == "Instructor") && ($column != ""))
        {
            $firstName = "";
            $lastName = "";

            if($value != "")
            {
                $name = explode(" ", $value);
                $firstName = $name[0];
                $lastName = isset($name[1]) ? $name[1] : "";      
            }

            $sql = "SELECT ID FROM faculty WHERE first_Name = '" . $firstName . "' OR last_Name = '" . $lastName . "'";
            $result = $conn->query($sql);

            if(mysqli_num_rows($result)==0)
            {
                $sql = "SELECT ID FROM faculty WHERE last_Name = '" . $firstName . "'";
                $result = $conn->query($sql);
            }
            $row = $result->fetch_assoc();

            $facultyID = $row["ID"];

            $sql = "SELECT CRN FROM section WHERE faculty_ID = '" . $facultyID . "' AND semester_ID = '" . $selectedSemester . "'";
            $schedule = $conn->query($sql);
        }
        else if(($column == "Day") && ($column != ""))
        {
            $sql = "SELECT day_ID FROM days WHERE Days LIKE '%" . $value . "%'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $dayID = $row["day_ID"];

            $sql = "SELECT * FROM section JOIN timeslot ON timeslot.timeslot_ID = section.timeslot_ID WHERE timeslot.day_ID = '" . $dayID . 
                    "' AND section.semester_ID = '" . $selectedSemester . "'";
            $schedule = $conn->query($sql); 
        }
        else if(($column == "Time") && ($column != ""))
        {
            $sql = "SELECT period_ID FROM period WHERE period_Time LIKE '%" . $value . "%'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $periodID = $row["period_ID"];

            $sql = "SELECT * FROM section JOIN timeslot ON timeslot.timeslot_ID = section.timeslot_ID WHERE timeslot.period_ID = '" . $periodID . 
                    "' AND section.semester_ID = '" . $selectedSemester . "'";
            $schedule = $conn->query($sql); 
        }
        else
        {
            $sql = "SELECT * FROM masterschedule WHERE ". $column . " LIKE '%" . $value . "%'" . " AND semester_ID = '" . $selectedSemester . "'";
            $schedule = $conn->query($sql); 
        }

        if($_SESSION["type"] == "Admin")
        {
            echo "<a href='AddSection.php'>Add Section</a><br>";
        }       
    ?>
    
    <p>
        <form method="post" action="ViewMasterSchedule.php">
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
    </p>
    <p>
            <label>Search:
                <input name="search" type="text" value="" size="15"  maxlength="255">
            </label>
            <label>in
                <select name="in">
                    <option>CRN</option>
                    <option value="course_ID">Course ID</option>
                    <option value="course_Name">Course Name</option>
                    <option>Instructor</option>
                    <option>Day</option>
                    <option>Time</option>
                </select>
            </label>
            <input type="submit" value="Search">
        </form>
    </p>

    <?php
        echo "<h2>" . $selectedSeason . " " . $selectedYear . "</h2>";
        if(($column != "") && ($value != ""))
        {
            echo "<h2> Search results for " . $columnName . ": " . $value . "</h2>";
        }

        if(mysqli_num_rows($schedule)==0)
            echo "No results found.";
        else
        {
            echo "<form method='post' action='ViewSectionInfo.php'>";
            echo "<p><table><tr>
                <th>CRN</th>
                <th>Course ID</th>
                <th>Section #</th>
                <th>Course Name</th>
                <th>Instructor</th>
                <th>Day & Time</th>
                <th>More Info</th>
                </tr>";
            while($row = mysqli_fetch_array($schedule))
            {
                $CRN = $row["CRN"];

                if((($column == "Instructor") || ($column == "Day") || ($column == "Day")) && ($column != ""))
                {
                    $sql = "SELECT * FROM masterschedule WHERE CRN = '" . $CRN . "' AND semester_ID = '" . $selectedSemester . "'";
                    $result = $conn->query($sql);
                    $row2 = $result->fetch_assoc();

                    $courseID = $row2["course_ID"];
                    $courseName = $row2["course_Name"];
                    $sectionNum = $row2["section_Number"];
                }
                else
                {
                    $courseID = $row["course_ID"];
                    $courseName = $row["course_Name"];
                    $sectionNum = $row["section_Number"];
                }

                $sql = "SELECT faculty_ID, timeslot_ID FROM section WHERE CRN = '" . $CRN . "'";
                $result = $conn->query($sql);
                $sectionTable = $result->fetch_assoc();

                $facultyID = $sectionTable["faculty_ID"];
                $timeslotID = $sectionTable["timeslot_ID"];

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

                echo "<tr>
                        <td>" . $CRN . "</td>
                        <td>" . $courseID . "</td>
                        <td>" . $sectionNum . "</td>
                        <td>" . $courseName . "</td>
                        <td>" . $fname . " " . $lname . "</td>
                        <td>" . $days . " " . $period . "</td>
                        <td><button name='CRN' type='submit' value='"
                        . $row["CRN"] . "'> View </button>
                        </td></tr>";
            }
            echo "</table></p></form>";
        }

    ?>   

</body>
</html>