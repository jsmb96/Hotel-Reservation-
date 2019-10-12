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
    <title>Update Attendance</title>
</head>

<body>
    <h1>Update Attendance</h1>
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

        if(($_SESSION["type"] != "Admin") && ($_SESSION["type"] != "Faculty"))
            {header('Location: UserHomepage.php');}

        if($_SESSION["type"] == "Faculty")
        {
            echo "<a href='ViewGradebook.php'>View Gradebook</a><br>";
        }

        $CRN = $_POST["CRN"];
        $studentID = $_POST["ID"];

        $sql = "SELECT * FROM enrollment WHERE CRN = '" . $CRN . "' AND student_ID = '" . $studentID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $semesterID = $row["semester_ID"];
        $midtermGrade = $row["midterm_Grade"];
        $finalGrade = $row["final_Grade"];

        $sql = "SELECT * FROM semesteryear WHERE semester_ID = '" . $semesterID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $season = $row["Season"];
        $year = $row["Year"];

        $sql = "SELECT course_ID, section_Number FROM section WHERE CRN = '" . $CRN . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $courseID = $row["course_ID"];
        $sectionNum = $row["section_Number"];

        $sql = "SELECT course_Name FROM course WHERE course_ID = '" . $courseID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $courseName = $row["course_Name"];

        $sql = "SELECT first_Name, last_Name FROM student WHERE ID = '" . $studentID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $firstName = $row["first_Name"];
        $lastName = $row["last_Name"];

        $sql = "SELECT days_absent FROM attendance WHERE CRN = '" . $CRN . "' AND student_ID = '" . $studentID . "'";
        $result = $conn->query($sql);

        $attendCheck = True;

        if(mysqli_num_rows($result)==0)
        {
            $daysAbsent = 0;
            $attendCheck = False;
        }
        else
        {
            $row = $result->fetch_assoc();
            $daysAbsent = $row["days_absent"];
        } 

        echo "<h2>" . $season . " " . $year . " - " . $courseName . " Section " . $sectionNum . "</h2>";
    ?>

    <form method='post' action='UpdateAttendancePost.php'>
        <input type = 'hidden' name = 'CRN' value = <?php echo $CRN ?> >
        <input type = 'hidden' name = 'studentID' value = <?php echo $studentID ?> >
        <input type = 'hidden' name = 'attendCheck' value = <?php echo $attendCheck ?> >
        <p>
            Student Name: <?php echo $firstName . " " . $lastName ?>
        </p>
        <p>
            <label>Number of Days Absent: 
            <input type = 'number' name='daysAbsent' value = '<?php echo $daysAbsent ?>'' min='0' max='99' required>
            </label>
        </p>
        <input type="submit" value="Update">
    </form>