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
    <title>Update Grades</title>
</head>

<body>
    <h1>Update Grades</h1>
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

        $sql = "SELECT * FROM enrollment WHERE CRN = '" . $CRN . "'";
        $students = $conn->query($sql);

        echo "<h2>" . $season . " " . $year . " - " . $courseName . " Section " . $sectionNum . "</h2>";
    ?>

    <form method='post' action='UpdateStudentGradesPost.php'>
        <input type = 'hidden' name = 'CRN' value = <?php echo $CRN ?> >
        <input type = 'hidden' name = 'studentID' value = <?php echo $studentID ?> >
        <p>
            Student Name: <?php echo $firstName . " " . $lastName ?>
        </p>
        <p>
            <label>Midterm Grade:
            <select name = 'midterm'>
                <?php
                    if(($midtermGrade == NULL) || ($midtermGrade == ''))
                    {
                        echo "<option selected value = ''></option>
                        <option>A+</option><option>A</option><option>A-</option>
                        <option>B+</option><option>B</option><option>B-</option>
                        <option>C+</option><option>C</option><option>C-</option>
                        <option>D+</option><option>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($midtermGrade == "A")
                    {
                        echo "<option value = ''></option>
                        <option selected>A</option><option>A-</option>
                        <option>B+</option><option>B</option><option>B-</option>
                        <option>C+</option><option>C</option><option>C-</option>
                        <option>D+</option><option>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($midtermGrade == "A-")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option selected>A-</option>
                        <option>B+</option><option>B</option><option>B-</option>
                        <option>C+</option><option>C</option><option>C-</option>
                        <option>D+</option><option>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($midtermGrade == "B+")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option>A-</option>
                        <option selected>B+</option><option>B</option><option>B-</option>
                        <option>C+</option><option>C</option><option>C-</option>
                        <option>D+</option><option>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($midtermGrade == "B")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option>A-</option>
                        <option>B+</option><option selected>B</option><option>B-</option>
                        <option>C+</option><option>C</option><option>C-</option>
                        <option>D+</option><option>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($midtermGrade == "B-")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option>A-</option>
                        <option>B+</option><option>B</option><option selected>B-</option>
                        <option>C+</option><option>C</option><option>C-</option>
                        <option>D+</option><option>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($midtermGrade == "C+")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option>A-</option>
                        <option>B+</option><option>B</option><option>B-</option>
                        <option selected>C+</option><option>C</option><option>C-</option>
                        <option>D+</option><option>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($midtermGrade == "C")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option>A-</option>
                        <option>B+</option><option>B</option><option>B-</option>
                        <option>C+</option><option selected>C</option><option>C-</option>
                        <option>D+</option><option>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($midtermGrade == "C-")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option>A-</option>
                        <option>B+</option><option>B</option><option>B-</option>
                        <option>C+</option><option>C</option><option selected>C-</option>
                        <option>D+</option><option>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($midtermGrade == "D+")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option>A-</option>
                        <option>B+</option><option>B</option><option>B-</option>
                        <option>C+</option><option>C</option><option>C-</option>
                        <option selected>D+</option><option>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($midtermGrade == "D")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option>A-</option>
                        <option>B+</option><option>B</option><option>B-</option>
                        <option>C+</option><option>C</option><option>C-</option>
                        <option>D+</option><option selected>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($midtermGrade == "D-")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option>A-</option>
                        <option>B+</option><option>B</option><option>B-</option>
                        <option>C+</option><option>C</option><option>C-</option>
                        <option>D+</option><option>D</option><option selected>D-</option>
                        <option>F</option>";
                    }
                    else if($midtermGrade == "F")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option>A-</option>
                        <option>B+</option><option>B</option><option>B-</option>
                        <option>C+</option><option>C</option><option>C-</option>
                        <option>D+</option><option>D</option><option>D-</option>
                        <option selected>F</option>";
                    }
                ?>
            </select></label>
        </p>
        <p>
            <label>Final Grade:
            <select name = 'final'>
                <?php
                    if(($finalGrade == NULL) || ($finalGrade == ''))
                    {
                        echo "<option selected value = ''></option>
                        <option>A</option><option>A-</option>
                        <option>B+</option><option>B</option><option>B-</option>
                        <option>C+</option><option>C</option><option>C-</option>
                        <option>D+</option><option>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($finalGrade == "A")
                    {
                        echo "<option value = ''></option>
                        <option selected>A</option><option>A-</option>
                        <option>B+</option><option>B</option><option>B-</option>
                        <option>C+</option><option>C</option><option>C-</option>
                        <option>D+</option><option>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($finalGrade == "A-")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option selected>A-</option>
                        <option>B+</option><option>B</option><option>B-</option>
                        <option>C+</option><option>C</option><option>C-</option>
                        <option>D+</option><option>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($finalGrade == "B+")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option>A-</option>
                        <option selected>B+</option><option>B</option><option>B-</option>
                        <option>C+</option><option>C</option><option>C-</option>
                        <option>D+</option><option>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($finalGrade == "B")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option>A-</option>
                        <option>B+</option><option selected>B</option><option>B-</option>
                        <option>C+</option><option>C</option><option>C-</option>
                        <option>D+</option><option>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($finalGrade == "B-")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option>A-</option>
                        <option>B+</option><option>B</option><option selected>B-</option>
                        <option>C+</option><option>C</option><option>C-</option>
                        <option>D+</option><option>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($finalGrade == "C+")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option>A-</option>
                        <option>B+</option><option>B</option><option>B-</option>
                        <option selected>C+</option><option>C</option><option>C-</option>
                        <option>D+</option><option>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($finalGrade == "C")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option>A-</option>
                        <option>B+</option><option>B</option><option>B-</option>
                        <option>C+</option><option selected>C</option><option>C-</option>
                        <option>D+</option><option>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($finalGrade == "C-")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option>A-</option>
                        <option>B+</option><option>B</option><option>B-</option>
                        <option>C+</option><option>C</option><option selected>C-</option>
                        <option>D+</option><option>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($finalGrade == "D+")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option>A-</option>
                        <option>B+</option><option>B</option><option>B-</option>
                        <option>C+</option><option>C</option><option>C-</option>
                        <option selected>D+</option><option>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($finalGrade == "D")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option>A-</option>
                        <option>B+</option><option>B</option><option>B-</option>
                        <option>C+</option><option>C</option><option>C-</option>
                        <option>D+</option><option selected>D</option><option>D-</option>
                        <option>F</option>";
                    }
                    else if($finalGrade == "D-")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option>A-</option>
                        <option>B+</option><option>B</option><option>B-</option>
                        <option>C+</option><option>C</option><option>C-</option>
                        <option>D+</option><option>D</option><option selected>D-</option>
                        <option>F</option>";
                    }
                    else if($finalGrade == "F")
                    {
                        echo "<option value = ''></option>
                        <option>A</option><option>A-</option>
                        <option>B+</option><option>B</option><option>B-</option>
                        <option>C+</option><option>C</option><option>C-</option>
                        <option>D+</option><option>D</option><option>D-</option>
                        <option selected>F</option>";
                    }
                ?>
            </select></label>
        </p>


        <input type="submit" value="Update">
    </form>

</body>
</html>