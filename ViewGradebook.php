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
    <title>Gradebook</title>
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

        if(($_SESSION["type"] != "Admin") && ($_SESSION["type"] != "Faculty"))
            {header('Location: UserHomepage.php');}

        $facultyID = isset($_POST['facultyID']) ? $_POST['facultyID'] : $_SESSION["ID"];

        echo "<h1>";
        if($_SESSION["type"] == "Admin")
        {
            $sql = "SELECT first_Name, last_Name FROM faculty WHERE ID = '" . $facultyID . "'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            $firstName = $row["first_Name"];
            $lastName = $row["last_Name"];

            echo $firstName . " " . $lastName . "'s ";
        }
        echo "Gradebook</h1>";
        echo "<a href='BackToHomepage.php'>Back to Homepage</a><br>";

        if($_SESSION["type"] == "Admin")
            { echo "<a href='ViewUserInfo.php'>View User Info</a><br>";}

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

        $sql = "SELECT DISTINCT semester_ID FROM enrollment"; // fix later
        $resultOption = $conn->query($sql);

        $selectedSemester = isset($_POST['semester']) ? $_POST['semester'] : $semesterID;
        $selectedSection = isset($_POST['section']) ? $_POST['section'] : 0;

        $sql = "SELECT * FROM semesteryear WHERE semester_ID = '" . $selectedSemester . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $selectedSeason = $row["Season"];
        $selectedYear = $row["Year"];

        $sql = "SELECT course_ID, section_Number FROM section WHERE CRN = '" . $selectedSection . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $selectedCourseID = $row["course_ID"];
        $selectedSectionNum = $row["section_Number"];

        $sql = "SELECT course_Name FROM course WHERE course_ID = '" . $selectedCourseID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $selectedCourseName = $row["course_Name"];

        $sql = "SELECT * FROM section WHERE faculty_ID = '" . $facultyID . "' AND semester_ID = '" . $selectedSemester . "'";
        $sections = $conn->query($sql);

        $sql = "SELECT * FROM enrollment WHERE CRN = '" . $selectedSection . "'";
        $students = $conn->query($sql);
    ?>

    <form method="post" action="ViewGradebook.php">
        <input type="hidden" name="facultyID" value="<?php echo $facultyID ?>">
        <p>
            <label>Semester:
                <select name="semester">
                    <?php while($row = mysqli_fetch_array($resultOption))
                    { 
                        $sql = "SELECT Season, Year FROM semesteryear WHERE semester_ID = '" . $row["semester_ID"] . "'";
                        $result = $conn->query($sql);
                        $row2 = $result->fetch_assoc();

                        if($row["semester_ID"] == $selectedSemester)
                            echo "<option selected ";
                        else
                            echo "<option ";
                        echo "value = '" . $row["semester_ID"] . "'>"
                     . $row2["Season"] . " " . $row2["Year"] . "</option>";
                    } ?>
                </select>
            </label>
            <input type="submit" value="Update">
        </p>
    </form>
    <form method="post" action="ViewGradebook.php">
        <input type="hidden" name="facultyID" value="<?php echo $facultyID ?>">
        <p>
            <input type = 'hidden' name = 'semester' value = <?php echo $selectedSemester ?> >
            <label>Section:
                <select name="section">
                    <?php while($row = mysqli_fetch_array($sections))
                    { 
                        $CRN = $row["CRN"];
                        $courseID = $row["course_ID"];
                        $sectionNum = $row["section_Number"];

                        $sql = "SELECT course_Name FROM course WHERE course_ID = '" . $courseID . "'";
                        $result = $conn->query($sql);
                        $row3 = $result->fetch_assoc();

                        $courseName = $row3["course_Name"];

                        if($row["CRN"] == $selectedSection)
                            echo "<option selected ";
                        else
                            echo "<option ";
                        echo "value = '" . $CRN . "'>" . $courseName . " Section " . $sectionNum .  "</option>";
                    } ?>
                </select>
            </label>
            <input type="submit" value="Select">
        </p>
    </form>

    <?php
        if($selectedSection == 0)
        {
            echo "No section currently selected.";
        }
        else
        {
            echo "<h2>" . $selectedSeason . " " . $selectedYear . " - " . $selectedCourseName . " Section " . $selectedSectionNum . "</h2>";

            if(mysqli_num_rows($students)==0)
                echo "No results found.";
            else
            {
                echo "<table>
                <tr>
                    <th>Name</th>
                    <th>Midterm</th>
                    <th>Final</th>
                    <th>Absent</th>
                    <th>Student Info</th>";
                if(($selectedSemester == $semesterID) || ($_SESSION["type"] == "Admin"))
                    {echo "<th>Grades</th><th>Attendace</th>";}
                echo "</tr>";
                while($row = mysqli_fetch_array($students))
                {
                    $studentID = $row["student_ID"];
                    $midtermGrade = $row["midterm_Grade"];
                    $finalGrade = $row["final_Grade"];

                    $sql = "SELECT days_absent FROM attendance WHERE CRN = '" . $selectedSection . "' AND student_ID = '" . $studentID . "'";
                    $attendance = $conn->query($sql);

                    if(mysqli_num_rows($attendance)==0)
                        $daysAbsent = 0;
                    else
                    {
                        $row2 = $attendance->fetch_assoc();
                        $daysAbsent = $row2["days_absent"];
                    }

                    $sql = "SELECT first_Name, last_Name FROM student WHERE ID = '" . $studentID . "'";
                    $result2 = $conn->query($sql);
                    $row2 = $result2->fetch_assoc();

                    $firstName = $row2["first_Name"];
                    $lastName = $row2["last_Name"];

                    echo "<tr><td>" . $firstName . " " . $lastName . "</td>
                            <td>" . $midtermGrade . "</td>
                            <td>" . $finalGrade . "</td>
                            <td>" . $daysAbsent . "</td>
                            <td><form method='post' action='ViewUserInfo.php'>
                            <button name='ID' type='submit' value='" . $studentID . "'>View</button>
                            </form></td>";         
                    if(($selectedSemester == $semesterID) || ($_SESSION["type"] == "Admin"))
                    {
                        echo "<td><form method='post' action='UpdateStudentGrades.php'>
                            <input type = 'hidden' name = 'CRN' value = '" . $selectedSection . "'>
                            <button name='ID' type='submit' value='" . $studentID . "'>Update</button>
                            </form></td>";
                        echo "<td><form method='post' action='UpdateAttendance.php'>
                            <input type = 'hidden' name = 'CRN' value = '" . $selectedSection . "'>
                            <button name='ID' type='submit' value='" . $studentID . "'>Update</button>
                            </form></td>";
                    }       
                    echo "</tr>";
                }
                echo "</table>";
            } 
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