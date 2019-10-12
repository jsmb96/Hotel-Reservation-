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
    <title>Faculty Schedule</title>
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

        echo "<h1>Faculty Schedule";
        if($_SESSION["type"] == "Admin")
        {
            $sql = "SELECT first_Name, last_Name FROM faculty WHERE ID = '" . $facultyID . "'";
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

        $semesterID = $yearTable["semester_ID"];
        $semesterID1 = $semesterID + 1;
        $semesterID2 = $semesterID + 2;

        if($season == "Fall" || $season == "Spring")
            $sql = "SELECT * FROM semesteryear WHERE semester_ID = '" . $semesterID . "' OR semester_ID = '" . $semesterID1 . "' OR semester_ID = '" . $semesterID2 . "'";
        else if($season == "Summer" || $season == "Winter")
            $sql = "SELECT * FROM semesteryear WHERE semester_ID = '" . $semesterID . "' OR semester_ID = '" . $semesterID1 . "'";

        $resultOption = $conn->query($sql);

        $selectedSemester = isset($_POST['semester']) ? $_POST['semester'] : $semesterID;

        $sql = "SELECT * FROM semesteryear WHERE semester_ID = '" . $selectedSemester . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $selectedSeason = $row["Season"];
        $selectedYear = $row["Year"];

        $sql = "SELECT CRN FROM section WHERE faculty_ID = '" . $facultyID . "' AND semester_ID = '" . $selectedSemester . "'";
        $schedule = $conn->query($sql);

    ?>

    <a href="BackToHomepage.php">Back to Homepage</a><br>
    <a href="ViewMasterSchedule.php">View Master Schedule</a><br>

    <?php
        if($_SESSION["type"] == "Admin")
        { echo "<a href='ViewUserInfo.php'>View User Info</a><br>";}
    ?>

    <p>
        <form method="post" action="ViewFacultySchedule.php">
            <input type = 'hidden' name = 'facultyID' value = <?php echo $facultyID ?> >
            <label>Semester:
                <select name="semester">
                    <?php while($row = mysqli_fetch_array($resultOption))
                    { 
                        if($row["semester_ID"] == $selectedSemester)
                            echo "<option selected ";
                        else
                            echo "<option ";
                        echo "value = '" . $row["semester_ID"] . "'>"
                     . $row["Season"] . " " . $row["Year"] . "</option>";
                    } ?>
                </select>
            </label>
            <input type="submit" value="Update">
        </form>
    </p>

    <h2><?php echo $selectedSeason . " " . $selectedYear ?></h2>

    <?php
        if(mysqli_num_rows($schedule)==0)
            echo "No results found.";
        else
        {
            echo "<p><table><tr>
                <th>Course Name</th>
                <th>Section</th>
                <th>Day & Time</th>
                <th>Room #</th>
                <th>Building</th>
                <th>More Info</th>";
            echo "</tr>";
            while($row = mysqli_fetch_array($schedule))
            {
                $sql = "SELECT * FROM section WHERE CRN = '" . $row["CRN"] . "'";
                $result = $conn->query($sql);
                $sectionTable = $result->fetch_assoc();
            
                $CRN = $sectionTable["CRN"];
                $cname = $sectionTable["course_Name"];
                $sectionNum = $sectionTable["section_Number"];
                $timeslotID = $sectionTable["timeslot_ID"];
                $roomID = $sectionTable["room_ID"];


                $sql = "SELECT * FROM timeslot WHERE timeslot_ID = '" . $timeslotID . "'";
                $result = $conn->query($sql);
                $timeslotTable = $result->fetch_assoc();

                $dayID = $timeslotTable["day_ID"];
                $periodID = $timeslotTable["period_ID"];

                $sql = "SELECT Days FROM days WHERE day_ID = '" . $dayID . "'";
                $result = $conn->query($sql);
                $daysTable = $result->fetch_assoc();

                $days = $daysTable["Days"];

                $sql = "SELECT period_Time FROM period WHERE period_ID = '" . $periodID . "'";
                $result = $conn->query($sql);
                $periodTable = $result->fetch_assoc();

                $period = $periodTable["period_Time"];

                $sql = "SELECT * FROM room WHERE room_ID = '" . $roomID . "'";
                $result = $conn->query($sql);
                $roomTable = $result->fetch_assoc();

                $roomNum = $roomTable["room_Number"];
                $buildingCode = $roomTable["building_Code"];

                $sql = "SELECT building_Name FROM building WHERE building_Code = '" . $buildingCode . "'";
                $result = $conn->query($sql);
                $buildingTable = $result->fetch_assoc();

                $buildingName = $buildingTable["building_Name"];

                echo "<tr><td>" . $cname . "</td>
                        <td>" . $sectionNum . "</td>
                        <td>" . $days . " " . $period . "</td>
                        <td>" . $roomNum . "</td>
                        <td>" . $buildingName . "</td>
                        <td><form method='post' action='ViewSectionInfo.php'>
                        <button name='CRN' type='submit' value='"
                        . $CRN . "'> View</button></form></td>";
                echo "</tr>";
            }
            echo "</table></p>";
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