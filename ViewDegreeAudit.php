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
    <title>Degree Audit</title>
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

        echo "<h1>Degree Audit";
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

        $sql = "SELECT * FROM gened";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        
        $gened1 = $row["course_1"];
        $gened2 = $row["course_2"];
        $gened3 = $row["course_3"];
        $gened4 = $row["course_4"];
        $gened5 = $row["course_5"];
        $gened6 = $row["course_6"];
        $gened7 = $row["course_7"];
        $gened8 = $row["course_8"];
        $gened9 = $row["course_9"];
        $gened10 = $row["course_10"];
        $gened11 = $row["course_11"];
        $gened12 = $row["course_12"];
        $gened13 = $row["course_13"];
        $gened14 = $row["course_14"];
        $gened15 = $row["course_15"];
        
        $genedName1 = $genedName2 = $genedName3 = $genedName4 = $genedName5 = "";
        $genedName6 = $genedName7 = $genedName8 = $genedName9 = $genedName10 = "";
        $genedName11 = $genedName12 = $genedName13 = $genedName14 = $genedName15 = "";

        $genedCheck1 = $genedCheck2 = $genedCheck3 = $genedCheck4 = $genedCheck5 = "Unfufilled";
        $genedCheck6 = $genedCheck7 = $genedCheck8 = $genedCheck9 = $genedCheck10 = "Unfufilled";
        $genedCheck11 = $genedCheck12 = $genedCheck13 = $genedCheck14 = $genedCheck15 = "Unfufilled";

        $sql = "SELECT * FROM majorrequirements WHERE major_ID = '" . $majorID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $majorCourse1 = $row["course_1"];
        $majorCourse2 = $row["course_2"];
        $majorCourse3 = $row["course_3"];
        $majorCourse4 = $row["course_4"];
        $majorCourse5 = $row["course_5"];

        $majorName1 = $majorName2 = $majorName3 = $majorName4 = $majorName5 = "";

        $majorCheck1 = $majorCheck2 = $majorCheck3 = $majorCheck4 = $majorCheck5 = "Unfufilled";

        $sql = "SELECT * FROM minorrequirements WHERE minor_ID = '" . $minorID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $minorCourse1 = $row["course_1"];
        $minorCourse2 = $row["course_2"];
        $minorCourse3 = $row["course_3"];

        $minorName1 = $minorName2 = $minorName3 = "";

        $minorCheck1 = $minorCheck2 = $minorCheck3 = "Unfufilled";

        $sql = "SELECT course_ID, course_Name FROM course";
        $courseList = $conn->query($sql);
        while($row = mysqli_fetch_array($courseList))
        {
            if($gened1 == $row["course_ID"]) $genedName1 = $row["course_Name"];
            else if($gened2 == $row["course_ID"]) $genedName2 = $row["course_Name"];
            else if($gened3 == $row["course_ID"]) $genedName3 = $row["course_Name"];
            else if($gened4 == $row["course_ID"]) $genedName4 = $row["course_Name"];
            else if($gened5 == $row["course_ID"]) $genedName5 = $row["course_Name"];
            else if($gened6 == $row["course_ID"]) $genedName6 = $row["course_Name"];
            else if($gened7 == $row["course_ID"]) $genedName7 = $row["course_Name"];
            else if($gened8 == $row["course_ID"]) $genedName8 = $row["course_Name"];
            else if($gened9 == $row["course_ID"]) $genedName9 = $row["course_Name"];
            else if($gened10 == $row["course_ID"]) $genedName10 = $row["course_Name"];
            else if($gened11 == $row["course_ID"]) $genedName11 = $row["course_Name"];
            else if($gened12 == $row["course_ID"]) $genedName12 = $row["course_Name"];
            else if($gened13 == $row["course_ID"]) $genedName13 = $row["course_Name"];
            else if($gened14 == $row["course_ID"]) $genedName14 = $row["course_Name"];
            else if($gened15 == $row["course_ID"]) $genedName15 = $row["course_Name"];

            if($majorCourse1 == $row["course_ID"]) $majorName1 = $row["course_Name"];
            else if($majorCourse2 == $row["course_ID"]) $majorName2 = $row["course_Name"];
            else if($majorCourse3 == $row["course_ID"]) $majorName3 = $row["course_Name"];
            else if($majorCourse4 == $row["course_ID"]) $majorName4 = $row["course_Name"];
            else if($majorCourse5 == $row["course_ID"]) $majorName5 = $row["course_Name"];

            if($minorCourse1 == $row["course_ID"]) $minorName1 = $row["course_Name"];
            else if($minorCourse2 == $row["course_ID"]) $minorName2 = $row["course_Name"];
            else if($minorCourse3 == $row["course_ID"]) $minorName3 = $row["course_Name"];
        }

        $sql = "SELECT CRN, final_Grade FROM enrollment WHERE student_ID = '" . $studentID . "'";
        $enrollList = $conn->query($sql);
        while($row = mysqli_fetch_array($enrollList))
        {
            $finalGrade = $row["final_Grade"];
            $finalCheck = "Unfufilled";
            if($finalGrade == '')
                $finalCheck = "In Progress";
            else if(($finalGrade != 'F') || ($finalGrade != 'D-') || ($finalGrade != 'D') || ($finalGrade != 'D+'))
                $finalCheck = "Fufilled";

            $sql = "SELECT course_ID FROM section WHERE CRN = '" . $row["CRN"] . "'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            if($gened1 == $row["course_ID"]) $genedCheck1 = $finalCheck;
            else if($gened2 == $row["course_ID"]) $genedCheck2 = $finalCheck;
            else if($gened3 == $row["course_ID"]) $genedCheck3 = $finalCheck;
            else if($gened4 == $row["course_ID"]) $genedCheck4 = $finalCheck;
            else if($gened5 == $row["course_ID"]) $genedCheck5 = $finalCheck;
            else if($gened6 == $row["course_ID"]) $genedCheck6 = $finalCheck;
            else if($gened7 == $row["course_ID"]) $genedCheck7 = $finalCheck;
            else if($gened8 == $row["course_ID"]) $genedCheck8 = $finalCheck;
            else if($gened9 == $row["course_ID"]) $genedCheck9 = $finalCheck;
            else if($gened10 == $row["course_ID"]) $genedCheck10 = $finalCheck;
            else if($gened11 == $row["course_ID"]) $genedCheck11 = $finalCheck;
            else if($gened12 == $row["course_ID"]) $genedCheck12 = $finalCheck;
            else if($gened13 == $row["course_ID"]) $genedCheck13 = $finalCheck;
            else if($gened14 == $row["course_ID"]) $genedCheck14 = $finalCheck;
            else if($gened15 == $row["course_ID"]) $genedCheck15 = $finalCheck;

            if($majorCourse1 == $row["course_ID"]) $majorCheck1 = $finalCheck;
            else if($majorCourse2 == $row["course_ID"]) $majorCheck2 = $finalCheck;
            else if($majorCourse3 == $row["course_ID"]) $majorCheck3 = $finalCheck;
            else if($majorCourse4 == $row["course_ID"]) $majorCheck4 = $finalCheck;
            else if($majorCourse5 == $row["course_ID"]) $majorCheck5 = $finalCheck;

            if($minorCourse1 == $row["course_ID"]) $minorCheck1 = $finalCheck;
            else if($minorCourse2 == $row["course_ID"]) $minorCheck2 = $finalCheck;
            else if($minorCourse3 == $row["course_ID"]) $minorCheck3 = $finalCheck;
        }
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
    
    echo "<h2> General Education Requirements </h2>";
    echo "<table><tr><th>Course Name</th><th>Status</th></tr>";
    echo "<tr><td>" . $genedName1 . "</td><td>" . $genedCheck1 . "</td></tr>";
    echo "<tr><td>" . $genedName2 . "</td><td>" . $genedCheck2 . "</td></tr>";
    echo "<tr><td>" . $genedName3 . "</td><td>" . $genedCheck3 . "</td></tr>";
    echo "<tr><td>" . $genedName4 . "</td><td>" . $genedCheck4 . "</td></tr>";
    echo "<tr><td>" . $genedName5 . "</td><td>" . $genedCheck5 . "</td></tr>";
    echo "<tr><td>" . $genedName6 . "</td><td>" . $genedCheck6 . "</td></tr>";
    echo "<tr><td>" . $genedName7 . "</td><td>" . $genedCheck7 . "</td></tr>";
    echo "<tr><td>" . $genedName8 . "</td><td>" . $genedCheck8 . "</td></tr>";
    echo "<tr><td>" . $genedName9 . "</td><td>" . $genedCheck9 . "</td></tr>";
    echo "<tr><td>" . $genedName10 . "</td><td>" . $genedCheck10 . "</td></tr>";
    echo "<tr><td>" . $genedName11 . "</td><td>" . $genedCheck11 . "</td></tr>";
    echo "<tr><td>" . $genedName12 . "</td><td>" . $genedCheck12 . "</td></tr>";
    echo "<tr><td>" . $genedName13 . "</td><td>" . $genedCheck13 . "</td></tr>";
    echo "<tr><td>" . $genedName14 . "</td><td>" . $genedCheck14 . "</td></tr>";
    echo "<tr><td>" . $genedName15 . "</td><td>" . $genedCheck15 . "</td></tr>";
    echo "</table>";

    echo "<h2> Major Requirements </h2>";
    echo "<table><tr><th>Course Name</th><th>Status</th></tr>";
    echo "<tr><td>" . $majorName1 . "</td><td>" . $majorCheck1 . "</td></tr>";
    echo "<tr><td>" . $majorName2 . "</td><td>" . $majorCheck2 . "</td></tr>";
    echo "<tr><td>" . $majorName3 . "</td><td>" . $majorCheck3 . "</td></tr>";
    echo "<tr><td>" . $majorName4 . "</td><td>" . $majorCheck4 . "</td></tr>";
    echo "<tr><td>" . $majorName5 . "</td><td>" . $majorCheck5 . "</td></tr>";
    echo "</table>";

    if($minorID != 0)
    {
        echo "<h2> Minor Requirements </h2>";
        echo "<table><tr><th>Course Name</th><th>Status</th></tr>";
        echo "<tr><td>" . $minorName1 . "</td><td>" . $minorCheck1 . "</td></tr>";
        echo "<tr><td>" . $minorName2 . "</td><td>" . $minorCheck2 . "</td></tr>";
        echo "<tr><td>" . $minorName3 . "</td><td>" . $minorCheck3 . "</td></tr>";
        echo "</table>";
    }

    ?>

</body>
</html>