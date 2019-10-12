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

	$courseID = $_POST["courseID"];
    $sectionNum =$_POST["sectionNum"];
    $facultyID = $_POST["facultyID"];
    $department = $_POST["department"];
    $capacity = $_POST["capacity"];
    $dayID = $_POST["dayID"];
    $periodID = $_POST["periodID"];
    $semesterID = $_POST["semesterID"];
    $roomID = $_POST["roomID"];

    $sectionCheck = False;
    $roomTimeCheck = False;
    $facultyTimeCheck = False;

    $sql = "SELECT timeslot_ID FROM timeslot WHERE day_ID = '" . $dayID . "' AND period_ID = '" . $periodID . "'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $timeslotID = $row["timeslot_ID"];

    $sql = "SELECT section_Number, faculty_ID, timeslot_ID, room_ID FROM section WHERE course_ID = '" . $courseID . "'";
    $sectionTable = $conn->query($sql);

    while($row = mysqli_fetch_array($sectionTable))
    {
        if($sectionNum == $row["section_Number"])
            $sectionCheck = True;
    }

    $sql = "SELECT faculty_ID, timeslot_ID, room_ID FROM section";
    $sectionTable2 = $conn->query($sql);

    while($row = mysqli_fetch_array($sectionTable2))
    {
        if(($roomID == $row["room_ID"]) && ($timeslotID == $row["timeslot_ID"]))
            $roomTimeCheck = True;

        if(($facultyID == $row["faculty_ID"]) && ($timeslotID == $row["timeslot_ID"]))
            $facultyTimeCheck = True;
    }

    $sql = "SELECT department_Name FROM faculty WHERE ID = '" . $facultyID . "'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $facultyDepartment = $row["department_Name"];

    $sql = "SELECT course_Name, department_Name FROM course WHERE course_ID = '" . $courseID . "'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $cname = $row["course_Name"];
    $courseDepartment = $row["department_Name"];

    if($department != $courseDepartment)
    {
        $_SESSION["Error"] = "Error: Selected course does not match the selected department.";
    }
    else if($department != $facultyDepartment)
    {
        $_SESSION["Error"] = "Error: Selected instructor does not match the selected department.";
    }
    else if($sectionCheck == True)
    {
        $_SESSION["Error"] = "Error: Cannot create duplicate section numbers for the same course.";
    }
    else if($roomTimeCheck == True)
    {
        $_SESSION["Error"] = "Error: Selected room is already filled during the selected timeslot.";
    }
    else if($facultyTimeCheck == True)
    {
        $_SESSION["Error"] = "Error: Selected instructor is busy during the selected timeslot.";
    }
    else
    {
        $sql = "INSERT INTO section (course_ID,section_Number,course_Name,faculty_ID,department_Name,capacity,timeslot_ID,semester_ID,room_ID)
            VALUES ('" . $courseID . "','"
                    . $sectionNum . "','"
                    . $cname . "','"
                    . $facultyID . "','"
                    . $department . "','"
                    . $capacity . "','"
                    . $timeslotID . "','"
                    . $semesterID . "','"
                    . $roomID. "')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION["Error"] = "Section added successfully";
        } else {
            $_SESSION["Error"] = "Error: " . $sql . "<br>" . $conn->error;
        }

        $sql = "SELECT CRN FROM section WHERE course_ID = '" . $courseID . "' AND section_Number = '" . $sectionNum . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $CRN = $row["CRN"];

        $sql = "INSERT INTO MasterSchedule (CRN,course_ID,section_Number,course_Name,department_Name,semester_ID)
                VALUES ('" . $CRN . "','"
                        . $courseID . "','"
                        . $sectionNum . "','"
                        . $cname . "','"
                        . $department . "','"
                        . $semesterID . "')";

        $conn->query($sql);
    }

	header('Location: AddSection.php');
?>