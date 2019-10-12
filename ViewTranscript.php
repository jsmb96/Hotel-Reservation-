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
    <title>Unofficial Transcript</title>
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
        
        $overallGPA = 0.0;
        $totalCredits = 0.0;
        $totalGrade = 0.0;
        $sql = "SELECT CRN, final_Grade FROM enrollment WHERE student_ID = '" . $studentID . "'";
        $allClasses = $conn->query($sql);
        while($row = mysqli_fetch_array($allClasses))
        {

            $finalGrade = $row["final_Grade"]; 

            $sql = "SELECT * FROM section WHERE CRN = '" . $row["CRN"] . "'";
            $result = $conn->query($sql);
            $sectionTable = $result->fetch_assoc();
        
            $CRN = $sectionTable["CRN"];

            $courseID = $sectionTable["course_ID"];

            $sql = "SELECT credits FROM course WHERE course_ID = '" . $courseID . "'";
            $result = $conn->query($sql);
            $creditTable = $result->fetch_assoc();
            $credits = $creditTable["credits"];

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
        }
        if($totalCredits != 0)
        $overallGPA = round(($totalGrade / $totalCredits),2);

        // echo $totalGrade;
        // echo "<br>";
        // echo $totalCredits;
        // echo "<br>";
        // echo $overallGPA;
        // echo "<br>";
        // echo $studentID;
        // echo "<br>";

        $sql = "UPDATE Student SET GPA = '" . $overallGPA . "' WHERE ID = '" . $studentID . "'";
        $conn->query($sql);

        echo "<h1>Unofficial Transcript";
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

        $semesterID = $yearTable["semester_ID"]; // current semester

        $sql = "SELECT * FROM student WHERE ID = '" . $studentID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();



        $firstName = $row["first_Name"];
        $middleInitial = $row["middle_Initial"];
        $lastName = $row["last_Name"];
        $dob = $row["DOB"];
        $year = $row["year"];
        $enrollType = $row["enrollment_Type"];
        $enrollStatus = $row["enrollment_Status"];
        $gpa = $row["gpa"];
        $majorID = $row["major_ID"];
        $minorID = $row["minor_ID"];

        $sql = "SELECT major_Title FROM major WHERE major_ID = '" . $majorID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $majorTitle = $row["major_Title"];

        $sql = "SELECT minor_Title FROM minor WHERE minor_ID = '" . $minorID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $minorTitle = $row["minor_Title"];

        $sql = "SELECT CRN, final_Grade FROM enrollment WHERE student_ID = '" . $studentID . "' AND semester_ID < '" . $semesterID . "'";
        $schedule = $conn->query($sql);

        $sql = "SELECT DISTINCT semester_ID FROM enrollment WHERE student_ID = '" . $studentID . "' AND semester_ID < '" . $semesterID . "'";
        $semester = $conn->query($sql);
    ?>

    <a href="BackToHomepage.php">Back to Homepage</a><br>
    <?php
        if(($_SESSION["type"] == "Admin") || ($_SESSION["type"] == "Faculty"))
        { echo "<a href='ViewUserInfo.php'>View User Info</a><br>";}
    ?>

    <h2>Student Information</h2>
    
    <?php
    echo "<table>
        <tr>
            <th>Name</th>
            <th>Date of Birth</th>
            <th>Year</th>
            <th>GPA</th>
            <th>Major</th>
            <th>Minor</th>
            <th>Enrollment Type</th>
            <th>Enrollment Status</th>
        </tr>
        <tr>
            <td>". $firstName . " " . $middleInitial . ". " . $lastName . "</td>
            <td>". $dob ."</td>
            <td>". $year ."</td>
            <td>". $gpa ."</td>
            <td>". $majorTitle ."</td>
            <td>". $minorTitle ."</td>
            <td>". $enrollType ."</td>
            <td>". $enrollStatus ."</td>
        </tr>
    </table>";

        while($row = mysqli_fetch_array($semester))
        {
            $semetserID = $row["semester_ID"];

            $sql = "SELECT Season, Year FROM semesteryear WHERE semester_ID = '" . $semetserID . "'";
            $result = $conn->query($sql);
            $semesterTable = $result->fetch_assoc();

            $season = $semesterTable["Season"];
            $year = $semesterTable["Year"];

            echo "<h2>" . $season . " " . $year . "</h2>";
            echo "<table>
                        <tr>
                            <th>Department</th>
                            <th>Course ID</th>
                            <th>Course Name</th>
                            <th>Grade</th>
                            <th>Credits</th>
                        </tr>";

            $sql = "SELECT CRN, final_Grade FROM enrollment WHERE student_ID = '" . $studentID . "' AND semester_ID = '" . $semetserID . "'";
            $schedule = $conn->query($sql);

            $totalCredits = 0.0;
            $totalGrade = 0.0;

            while($row = mysqli_fetch_array($schedule))
            {
                $finalGrade = $row["final_Grade"]; 

                $sql = "SELECT * FROM section WHERE CRN = '" . $row["CRN"] . "'";
                $result = $conn->query($sql);
                $sectionTable = $result->fetch_assoc();
            
                $CRN = $sectionTable["CRN"];
                $courseID = $sectionTable["course_ID"];
                $courseName = $sectionTable["course_Name"];
                $departmentName = $sectionTable["department_Name"];

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


                echo "<tr><td>" . $departmentName . "</td>
                        <td>" . $courseID . "</td>
                        <td>" . $courseName . "</td>
                        <td>" . $finalGrade . "</td>
                        <td>" . $credits . "</td>
                        </tr>";
            }
            echo "</table>";
            $GPA = round(($totalGrade / $totalCredits),2);
            echo "<h3> Semester GPA: " . $GPA . "</h3><br>";
                    
        }

        
         ?>
    </table>

</body>
</html>