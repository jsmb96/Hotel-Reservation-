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
    <title>My Homepage</title>
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

        if(is_null($_SESSION["ID"]))
            header('Location: Login.php');

        if($_SESSION["type"] == "Admin")
            echo "<h1> Administrator Homepage </h1>";
        else if($_SESSION["type"] == "Student")
            echo "<h1> Student Homepage </h1>";
        else if($_SESSION["type"] == "Faculty")
            echo "<h1> Faculty Homepage </h1>";
        else if($_SESSION["type"] == "Research")
            echo "<h1> Research Staff Homepage </h1>"; 

        $sql = "SELECT first_Name, last_Name FROM user WHERE ID = '" . $_SESSION["ID"] . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
    
        $fname = $row["first_Name"];
        $lname = $row["last_Name"];
        echo "<h2> Welcome, " . $fname . " " . $lname . "!</h2>";

        if($_SESSION["type"] == "Admin")
        {
            echo "<a href='ViewPersonalInfo.php'>View Personal Info</a><br>";
            echo "<br>";
            echo "<a href='ViewAllUsers.php'>View All Users</a><br>";
            echo "<a href='AddUser.php'>Add User</a><br>";
            // echo "<a href='ViewAllStudents.php'>View All Students</a><br>";
            echo "<br>";
            echo "<a href='ViewCourseCatalog.php'>View Course Catalog</a><br>";
            echo "<a href='AddCourseToCatalog.php'>Add Course to Catalog</a><br>";
            echo "<a href='ViewDepartmentInfo.php'>View Department Info</a><br>";
            echo "<a href='AddDepartment.php'>Add Department</a><br>";
            echo "<br>";
            echo "<a href='ViewMasterSchedule.php'>View Master Schedule</a><br>";
            echo "<a href='AddSection.php'>Add Section</a><br>";
            echo "<a href='ViewMasterScheduleArchive.php'>View Master Schedule Archive</a><br>";
            echo "<a href='AddSemester.php'>Add Semester</a><br>";
            echo "<br>";
            echo "<a href='Logout.php'>Logout</a><br></p>";
            echo "<br><br><br>";
        }
        else if($_SESSION["type"] == "Student")
        {
            echo "<a href='ViewPersonalInfo.php'>View Personal Info</a><br>";
            echo "<a href='ViewStudentHold.php'>View Account Holds</a><br>";
            echo "<a href='ViewAdvisor.php'>View Advisor Info</a><br>";
            echo "<br>";
            echo "<a href='ViewCourseCatalog.php'>View Course Catalog</a><br>";
            echo "<a href='ViewDepartmentInfo.php'>View Department Info</a><br>";
            echo "<br>";
            echo "<a href='ViewMasterSchedule.php'>View Master Schedule</a><br>";
            echo "<a href='ViewPersonalSchedule.php'>View Personal Schedule</a><br>";
            echo "<a href='ViewCourseHistory.php'>View Course History</a><br>";
            echo "<a href='ViewTranscript.php'>View Unofficial Transcript</a><br>";
            echo "<a href='ViewDegreeAudit.php'>View Degree Audit</a><br>";
            echo "<br>";
            echo "<a href='Logout.php'>Logout</a><br>";
            echo "<br><br><br>";
        }
        else if($_SESSION["type"] == "Faculty")
        {
            echo "<a href='ViewPersonalInfo.php'>View Personal Info</a><br>";
            echo "<a href='ViewAdvisedStudents.php'>View Advised Students</a><br>";
            echo "<br>";
            echo "<a href='ViewCourseCatalog.php'>View Course Catalog</a><br>";
            echo "<a href='ViewDepartmentInfo.php'>View Department Info</a><br>";
            echo "<br>";
            echo "<a href='ViewMasterSchedule.php'>View Master Schedule</a><br>";
            echo "<a href='ViewFacultySchedule.php'>View Faculty Schedule</a><br>";
            echo "<a href='ViewGradebook.php'>View Gradebook</a><br>";
            echo "<br>";
            echo "<a href='Logout.php'>Logout</a><br>";
            echo "<br><br><br>";
        }
        else if($_SESSION["type"] == "Research")
        {
            echo "<a href='ViewPersonalInfo.php'>View Personal Info</a><br>";
            echo "<br>";
            echo "<a href='ViewSystemData.php'>View System Data</a><br>";
            echo "<br>";
            echo "<a href='Logout.php'>Logout</a><br>";
            echo "<br><br><br>";
        }

    ?>
</body>
</html>