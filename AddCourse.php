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
    $currentSemesterID = $_POST["currentSemesterID"];
    $studentID = $_POST["studentID"];
    $courseCheck = False;
    $timeslotCheck = False;
    $holdCheck = False;
    $creditCheck = 0;
    $creditLimit = 0;
    $prereq1Check = True;
    $prereq2Check = True;
    $prereq3Check = True;
    $prereq3Check = True;
    $prereq4Check = True;

    $sql = "SELECT * FROM section WHERE CRN = '" . $_POST["CRN"] . "'";
    $result = $conn->query($sql);
    $sectionTable = $result->fetch_assoc();
    
    $courseID = $sectionTable["course_ID"];
    $enrolled = $sectionTable["enrolled"];
    $capacity = $sectionTable["capacity"];
    $timeslotID = $sectionTable["timeslot_ID"];
    $semesterID = $sectionTable["semester_ID"];

    $sql = "SELECT enrollment_Status FROM student WHERE ID = '" . $studentID . "'";
    $result = $conn->query($sql);
    $studentTable = $result->fetch_assoc();

    $enrollmentStatus = $studentTable["enrollment_Status"];
    if($enrollmentStatus == "Part-Time")
        $creditLimit = 8;
    else if($enrollmentStatus == "Full-Time")
        $creditLimit = 20;

    $sql = "SELECT credits FROM course WHERE course_ID = '" . $courseID . "'";
    $result = $conn->query($sql);
    $creditTable = $result->fetch_assoc();

    $credits = $creditTable["credits"];

    $sql = "SELECT * FROM prerequisite WHERE course_ID = '" . $courseID . "'";
    $result = $conn->query($sql);
    $prereqTable = $result->fetch_assoc();

    $prereq1 = $prereqTable["prereq_1"];
    $prereq2 = $prereqTable["prereq_2"];
    $prereq3 = $prereqTable["prereq_3"];
    $prereq4 = $prereqTable["prereq_4"];
    $prereq5 = $prereqTable["prereq_5"];

    echo $prereq1 . "<br>";
    echo $prereq2 . "<br>";
    echo $prereq3 . "<br>";
    echo $prereq4 . "<br>";
    echo $prereq5 . "<br>";

    $sql = "SELECT CRN FROM enrollment WHERE student_ID = '" . $studentID . "' AND semester_ID = '" . $semesterID . "'";
    $enrollment = $conn->query($sql);

    while($row = mysqli_fetch_array($enrollment))
    {
        $sql = "SELECT course_ID, timeslot_ID FROM section WHERE CRN = '" . $row["CRN"] . "'";
        $result = $conn->query($sql);
        $courseTable = $result->fetch_assoc();

        if($courseID == $courseTable["course_ID"]) 
        {
            $courseCheck = True;
        }
        
        if($timeslotID == $courseTable["timeslot_ID"]) 
        {
            $timeslotCheck = True;
        }
        $sql = "SELECT credits FROM course WHERE course_ID = '" . $courseTable["course_ID"] . "'";
        $result = $conn->query($sql);
        $creditTable = $result->fetch_assoc();
        
        $creditCheck += $creditTable["credits"];
    }

    $sql = "SELECT CRN FROM enrollment WHERE student_ID = '" . $studentID . "' AND semester_ID <= '" . $currentSemesterID . "'";
    $enrollmentHistory = $conn->query($sql);

    while($row = mysqli_fetch_array($enrollmentHistory))
    {
        $sql = "SELECT course_ID FROM section WHERE CRN = '" . $row["CRN"] . "'";
        $result = $conn->query($sql);
        $courseTable = $result->fetch_assoc();

        echo $courseTable["course_ID"] . " ";

        if(!is_null($prereq1) && $prereq1 != 0 && $prereq1Check == True)
        {
            if($prereq1 == $courseTable["course_ID"])
            {
                $prereq1Check = False;
                echo "OK <br>";
            }
            else
                $prereq1Check = True;
        }
        else
            $prereq1Check = False;
        
        if(!is_null($prereq2) && $prereq2 != 0 && $prereq2Check == True)
        {
            if($prereq2 == $courseTable["course_ID"])
            {
                $prereq2Check = False;
                echo "OK <br>";
            }
            else
                $prereq2Check = True;
        }
        else
            $prereq2Check = False;
        
        if(!is_null($prereq3) && $prereq3 != 0 && $prereq3Check == True)
        {
            if($prereq3 == $courseTable["course_ID"])
            {
                $prereq3Check = False;
                echo "OK <br>";
            }
            else
                $prereq3Check = True;
        }
        else
            $prereq3Check = False;
        
        if(!is_null($prereq4) && $prereq4 != 0 && $prereq4Check == True)
        {
            if($prereq4 == $courseTable["course_ID"])
            {
                $prereq4Check = False;
            }
            else
                $prereq4Check = True;
        }
        else
            $prereq4Check = False;
        
        if(!is_null($prereq5) && $prereq5 != 0 && $prereq5Check == True)
        {
            if($prereq5 == $courseTable["course_ID"])
            {
                $prereq5Check = False;
            }
            else
                $prereq5Check = True;
        }
        else
            $prereq5Check = False; 
    }

    $sql = "SELECT * FROM studenthold WHERE student_ID = '" . $studentID . "'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    if(!is_null($row))
    {
        $holdCheck = True;
    }

    if($holdCheck == True)
    {
        echo "hold";
        $_SESSION["Error"] = "Error: Cannot register for any sections while a hold exists on your account.";
    }
    else if((!is_null($prereq1) && $prereq1 != 0 && $prereq1Check == True) || 
        (!is_null($prereq2) && $prereq2 != 0 && $prereq2Check == True) || 
        (!is_null($prereq3) && $prereq3 != 0 && $prereq3Check == True) || 
        (!is_null($prereq4) && $prereq4 != 0 && $prereq4Check == True) || 
        (!is_null($prereq5) && $prereq5 != 0 && $prereq5Check == True))
    {
        echo "prereq 1 check = " . $prereq1Check . "<br>";
        echo "prereq 2 check = " . $prereq2Check . "<br>";
        echo "prereq 3 check = " . $prereq3Check . "<br>";
        echo "prereq 4 check = " . $prereq4Check . "<br>";
        echo "prereq 5 check = " . $prereq5Check . "<br>";
        echo "error";
        $_SESSION["Error"] = "Error: Cannot register for this section without the required prerequisite courses.";
    }
    else if($creditCheck + $credits > $creditLimit)
    {
        echo "creditlimit";
        $_SESSION["Error"] = "Error: Cannot enroll in more than " . $creditLimit . " credits worth of sections.";
    }
    else if($courseCheck == True)
    {
        echo "dup";
        $_SESSION["Error"] = "Error: Cannot register for duplicate courses.";
    }
    else if($timeslotCheck == True)
    {
        echo "filled";
        $_SESSION["Error"] = "Error: Cannot register for a section with a timeslot that is already filled.";
    }
    else if($CRN == 0)
    {
        echo "invalid";
        $_SESSION["Error"] = "Error: Invalid section.";
    }
    else if($enrolled >= $capacity)
    {
        echo "full";
        $_SESSION["Error"] = "Error: Cannot register for a full section.";
    }
    else
    {
        echo "insert";
        $sql = "UPDATE section SET enrolled = enrolled + 1 WHERE CRN = '" . $CRN . "'";
        $conn->query($sql);

        $sql = "INSERT INTO enrollment (CRN, student_ID, semester_ID)
                VALUES ('" . $CRN . "','" . $studentID . "','" . $semesterID . "')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION["Error"] = "Course added successfully.";
        } else {
            $_SESSION["Error"] = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    
    if($_SESSION["type"] == "Admin")
    {
        header('Location: ViewUserInfo.php');
    }
    else
        header('Location: ViewPersonalSchedule.php');
?>