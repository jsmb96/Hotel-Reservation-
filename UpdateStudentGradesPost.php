<?php

    session_start();
    
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

    $CRN = $_POST["CRN"];
    $studentID = $_POST["studentID"];
    $midterm = $_POST["midterm"];
    $final = $_POST["final"];

    $sql = "UPDATE enrollment set midterm_Grade = '" . $midterm . "' WHERE CRN = '" . $CRN . "' AND student_ID = '" . $studentID . "'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION["Error"] = "Grades updated successfully.";
    } else {
        $_SESSION["Error"] = "Error: " . $sql . "<br>" . $conn->error;
    }

    $sql = "UPDATE enrollment set final_Grade = '" . $final . "' WHERE CRN = '" . $CRN . "' AND student_ID = '" . $studentID . "'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION["Error"] = "Grades updated successfully.";
    } else {
        $_SESSION["Error"] = "Error: " . $sql . "<br>" . $conn->error;
    }

    $sql = "SELECT CRN, final_Grade FROM enrollment WHERE student_ID = '" . $studentID . "'";
    $enrollList = $conn->query($sql);

    $totalGrade = 0.0;
    $totalCredits = 0.0;
    $GPA = 0.0;

    while($row = mysqli_fetch_array($enrollList))
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
    }
    $GPA = round(($totalGrade / $totalCredits),2);

    $sql = "UPDATE student SET gpa = '" . $GPA . "' WHERE ID = '" . $studentID . "'";
    $conn->query($sql);

    if($_SESSION["type"] == "Admin")
    {
        header('Location: ViewUserInfo.php');
    }
    else
        header('Location: ViewGradebook.php');

    
?>