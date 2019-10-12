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

    $ID = $_POST["ID"];
    $department = $_POST["department"];
    $cname = $_POST["cname"];
    $cnameOld = $_POST["cnameOld"];
    $credits = $_POST["credits"];
    $description = $_POST["description"];

    $majorReq = isset($_POST['majorReq']) ? $_POST['majorReq'] : "";
    $minorReq = isset($_POST['minorReq']) ? $_POST['minorReq'] : "";
    $majorCourse = $_POST["majorCourse"];
    $minorCourse = $_POST["minorCourse"];
    $majorCheck = False;
    $minorCheck = False;

    $nameCheck = False;

    $prereq1 = $_POST["prereq1"];
    $prereq2 = $_POST["prereq2"];
    $prereq3 = $_POST["prereq3"];
    $prereq4 = $_POST["prereq4"];
    $prereq5 = $_POST["prereq5"];

    echo $cnameOld . "<br>";
    echo $cname . "<br>";

    $sql = "SELECT course_Name FROM course";
    $nameList = $conn->query($sql);

    while($row = mysqli_fetch_array($nameList))
    {
        if(($cname == $row["course_Name"]) && ($cnameOld != $row["course_Name"]))
        {
            $nameCheck = True;
            echo "namecheck true <br>";
        }
    }

    $sql = "SELECT department_ID from department WHERE department_Name = '" . $department . "'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $departmentID = $row["department_ID"];

    if(($majorReq == "on") && ($majorReq != "") && ($majorCourse == ""))
    {
        echo "majorcheck <br>";
        $sql = "SELECT * FROM majorrequirements WHERE major_ID = '" . $departmentID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $majorCourse1 = $row["course_1"];
        $majorCourse2 = $row["course_2"];
        $majorCourse3 = $row["course_3"];
        $majorCourse4 = $row["course_4"];
        $majorCourse5 = $row["course_5"];

        if(($majorCourse1 != 0 && $majorCourse1 != NULL) &&
            ($majorCourse2 != 0 && $majorCourse2 != NULL) &&
            ($majorCourse3 != 0 && $majorCourse3 != NULL) &&
            ($majorCourse4 != 0 && $majorCourse4 != NULL) &&
            ($majorCourse5 != 0 && $majorCourse5 != NULL))
        {
            $majorCheck = True;
            echo "majorcheck True <br>";
        }
    }

    if(($minorReq == "on") && ($minorReq != "") && ($minorCourse == ""))
    {
        echo "minorcheck <br>";
        $sql = "SELECT * FROM minorrequirements WHERE minor_ID = '" . $departmentID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $minorCourse1 = $row["course_1"];
        $minorCourse2 = $row["course_2"];
        $minorCourse3 = $row["course_3"];

        if(($minorCourse1 != 0 && $minorCourse1 != NULL) &&
            ($minorCourse2 != 0 && $minorCourse2 != NULL) &&
            ($minorCourse3 != 0 && $minorCourse3 != NULL))
        {
            $minorCheck = True;
            echo "minorcheck True <br>";
        }
    }

    if($nameCheck == True)
    {
        $_SESSION["Error"] = "Error: A course with that name already exists.";
    }
    else if($majorCheck == True)
    {
        $_SESSION["Error"] = "Error: Selected major cannot have more than 5 required courses.";
    }
    else if($minorCheck == True)
    {
        $_SESSION["Error"] = "Error: Selected minor cannot have more than 3 required courses.";
    }
    else
    {
        if(($majorReq == "on") && ($majorReq != ""))
        {
           if($majorCourse1 == 0 || $majorCourse1 == NULL)
                $sql = "UPDATE majorrequirements SET course_1 = '" . $ID . "' WHERE major_ID = '" . $departmentID . "'"; 
            else if($majorCourse2 == 0 || $majorCourse2 == NULL)
                $sql = "UPDATE majorrequirements SET course_2 = '" . $ID . "' WHERE major_ID = '" . $departmentID . "'";  
            else if($majorCourse3 == 0 || $majorCourse3 == NULL)
                $sql = "UPDATE majorrequirements SET course_3 = '" . $ID . "' WHERE major_ID = '" . $departmentID . "'";  
            else if($majorCourse4 == 0 || $majorCourse4 == NULL)
                $sql = "UPDATE majorrequirements SET course_4 = '" . $ID . "' WHERE major_ID = '" . $departmentID . "'";   
            else if($majorCourse5 == 0 || $majorCourse5 == NULL)
                $sql = "UPDATE majorrequirements SET course_5 = '" . $ID . "' WHERE major_ID = '" . $departmentID . "'"; 

            $conn->query($sql); 
        }
        else if(($majorReq == "") && ($majorCourse != ""))
        {
            $sql = "UPDATE majorrequirements SET " . $majorCourse . " = 0 WHERE major_ID = '" . $departmentID . "'";
            $conn->query($sql);
        }

        if(($minorReq == "on") && ($minorReq != ""))
        {
            if($minorCourse1 == 0 || $minorCourse1 == NULL)
                $sql = "UPDATE minorrequirements SET course_1 = '" . $ID . "' WHERE minor_ID = '" . $departmentID . "'";  
            else if($minorCourse2 == 0 || $minorCourse2 == NULL)
                $sql = "UPDATE minorrequirements SET course_2 = '" . $ID . "' WHERE minor_ID = '" . $departmentID . "'";  
            else if($minorCourse3 == 0 || $minorCourse3 == NULL)
                $sql = "UPDATE minorrequirements SET course_3 = '" . $ID . "' WHERE minor_ID = '" . $departmentID . "'";  

            $conn->query($sql);  
        }
        else if(($minorReq == "") && ($minorCourse != ""))
        {
            $sql = "UPDATE minorrequirements SET " . $minorCourse . " = 0 WHERE minor_ID = '" . $departmentID . "'";
            $conn->query($sql);
        }

        $sql = "UPDATE course set course_Name = '" . $cname . "' WHERE course_ID = '" . $ID . "'";
        $conn->query($sql);

        $sql = "UPDATE course set department_Name = '" . $department . "' WHERE course_ID = '" . $ID . "'";
        $conn->query($sql);
        
        $sql = "UPDATE course set credits = '" . $credits . "' WHERE course_ID = '" . $ID . "'";
        $conn->query($sql);

        $sql = "UPDATE course set description = '" . $description . "' WHERE course_ID = '" . $ID . "'";
        $conn->query($sql);

        $sql = "UPDATE prerequisite set course_Name = '" . $cname . "' WHERE course_ID = '" . $ID . "'";
        $conn->query($sql);

        $sql = "UPDATE prerequisite set prereq_1 = '" . $prereq1 . "' WHERE course_ID = '" . $ID . "'";
        $conn->query($sql);

        $sql = "UPDATE prerequisite set prereq_2 = '" . $prereq2 . "' WHERE course_ID = '" . $ID . "'";
        $conn->query($sql);

        $sql = "UPDATE prerequisite set prereq_3 = '" . $prereq3 . "' WHERE course_ID = '" . $ID . "'";
        $conn->query($sql);

        $sql = "UPDATE prerequisite set prereq_4 = '" . $prereq4 . "' WHERE course_ID = '" . $ID . "'";
        $conn->query($sql);

        $sql = "UPDATE prerequisite set prereq_5 = '" . $prereq5 . "' WHERE course_ID = '" . $ID . "'";

        if ($conn->query($sql) === TRUE) {
            $_SESSION["Error"] = "Course info updated successfully.";
        } else {
            $_SESSION["Error"] = "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    header('Location: ViewCourseInfo.php');
?>