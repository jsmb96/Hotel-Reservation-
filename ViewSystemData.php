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
    <title>View System Data</title>
</head>

<body>
    <h1>View System Data</h1>
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

        $value = isset($_POST['search']) ? $_POST['search'] : "";
        $column = isset($_POST['in']) ? $_POST['in'] : "course_ID";
        $columnName = "";

        if($column == "course_ID") $columnName = "Course ID";
        else if($column == "course_Name") $columnName = "Course Name";
        else if($column == "department_Name") $columnName = "Department Name";
        else $columnName = $column;

        $sql = "SELECT * FROM department WHERE hidden = 0";
        $departmentList = $conn->query($sql);
        $departmentList2 = $conn->query($sql);
        $departmentList3 = $conn->query($sql);
        $departmentList4 = $conn->query($sql);
        $departmentList5 = $conn->query($sql);
        $departmentList6 = $conn->query($sql);
        $majorList = $conn->query($sql);
        $minorList = $conn->query($sql);

        $sql = "SELECT * FROM semesteryear";
        $semesterList1 = $conn->query($sql);
        $semesterList2 = $conn->query($sql);
        $semesterList3 = $conn->query($sql);
        $semesterList4 = $conn->query($sql);
        $semesterList5 = $conn->query($sql);

        $sql = "SELECT * FROM days";
        $daysList = $conn->query($sql);

        $sql = "SELECT * FROM period";
        $periodList = $conn->query($sql);

        $sql = "SELECT * FROM building";
        $buildingList = $conn->query($sql);

        $sql = "SELECT DOB FROM student WHERE hidden = 0";
        $studentDOB = $conn->query($sql);

        $sql = "SELECT DOB FROM faculty WHERE hidden = 0";
        $facultyDOB = $conn->query($sql);

        $select1 = isset($_POST['select1']) ? $_POST['select1'] : "";
            $users = isset($_POST['users']) ? $_POST['users'] : "";
                $faculty = isset($_POST['faculty']) ? $_POST['faculty'] : "";
                    $employStatus = isset($_POST['employStatus']) ? $_POST['employStatus'] : "";
                    $department = isset($_POST['department']) ? $_POST['department'] : "";
                $student = isset($_POST['student']) ? $_POST['student'] : "";
                    $enrollStatus = isset($_POST['enrollStatus']) ? $_POST['enrollStatus'] : "";
                    $enrollType = isset($_POST['enrollType']) ? $_POST['enrollType'] : "";
                    $year = isset($_POST['year']) ? $_POST['year'] : "";
                    $holds = isset($_POST['holds']) ? $_POST['holds'] : "";
                    $major = isset($_POST['major']) ? $_POST['major'] : "";
                    $minor = isset($_POST['minor']) ? $_POST['minor'] : "";
                    $GPAcompare = isset($_POST['GPAcompare']) ? $_POST['GPAcompare'] : "";
                    $GPAvalue = isset($_POST['GPAvalue']) ? $_POST['GPAvalue'] : "";
            $course = isset($_POST['course']) ? $_POST['course'] : "";
                $department2 = isset($_POST['department2']) ? $_POST['department2'] : "";
                $semester = isset($_POST['semester']) ? $_POST['semester'] : "";
            $section = isset($_POST['section']) ? $_POST['section'] : "";
                $department3 = isset($_POST['department3']) ? $_POST['department3'] : "";
                $semester2 = isset($_POST['semester2']) ? $_POST['semester2'] : "";
                $days = isset($_POST['days']) ? $_POST['days'] : "";
                $period = isset($_POST['period']) ? $_POST['period'] : "";
            $rooms = isset($_POST['rooms']) ? $_POST['rooms'] : "";
                $building = isset($_POST['building']) ? $_POST['building'] : "";

        $sqlDefault = "SELECT * FROM user WHERE ID = 000000";
        $result = $conn->query($sqlDefault);

        $searchResults = " ";

        if($select1 == "Users")
        {
            $searchResults .= "Users: ";
            if($users == "All")
            {
                $searchResults .= "All";
                $sql = "SELECT * FROM user WHERE hidden = 0";
                $result = $conn->query($sql);
            }
            else if($users == "Faculty")
            {
                $searchResults .= "Faculty: ";
                if($faculty == "All")
                {
                    $searchResults .= "All";
                    $sql = "SELECT * FROM faculty WHERE hidden = 0";
                    $result = $conn->query($sql);
                }
                else if($faculty == "Employment Status")
                {
                    $searchResults .= "Employment Status: " . $employStatus;
                    $sql = "SELECT * FROM faculty WHERE employment_Status = '" . $employStatus . "' AND hidden = 0";
                    $result = $conn->query($sql);
                }
                else if($faculty == "Department")
                {
                    $searchResults .= "Department: " . $department;
                    $sql = "SELECT * FROM faculty WHERE department_Name = '" . $department . "' AND hidden = 0";
                    $result = $conn->query($sql);
                }
                else if($faculty == "Advisors")
                {
                    $searchResults .= "Advisors";
                    $sql = "SELECT * FROM faculty JOIN advisor ON advisor.faculty_ID = faculty.ID WHERE faculty.hidden = 0";
                    $result = $conn->query($sql);
                }
            }
            else if($users == "Students")
            {
                $searchResults .= "Students: ";
                if($student == "All")
                {
                    $searchResults .= "All";
                    $sql = "SELECT * FROM student WHERE hidden = 0";
                    $result = $conn->query($sql);
                }
                else if($student == "Enrollment Status")
                {
                    $searchResults .= "Enrollment Status: " . $enrollStatus;
                    $sql = "SELECT * FROM student WHERE enrollment_Status = '" . $enrollStatus . "' AND hidden = 0";
                    $result = $conn->query($sql);
                }
                else if($student == "Enrollment Type")
                {
                    $searchResults .= "Enrollment Type: " . $enrollType;
                    $sql = "SELECT * FROM student WHERE enrollment_Type = '" . $enrollType . "' AND hidden = 0";
                    $result = $conn->query($sql);
                }
                else if($student == "Year")
                {
                    $searchResults .= "Year: " . $year;
                    $sql = "SELECT * FROM student WHERE year = '" . $year . "' AND hidden = 0";
                    $result = $conn->query($sql);
                }
                else if($student == "Major")
                {
                    $sql = "SELECT major_Title FROM major WHERE major_ID = '" . $major . "'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $majorName = $row["major_Title"];

                    $searchResults .= "Major: " . $majorName;
                    $sql = "SELECT * FROM student WHERE major_ID = '" . $major . "' AND hidden = 0";
                    $result = $conn->query($sql);
                }
                else if($student == "Minor")
                {
                    $sql = "SELECT minor_Title FROM minor WHERE minor_ID = '" . $minor . "'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $minorName = $row["minor_Title"];

                    $searchResults .= "Minor: " . $minorName;
                    $sql = "SELECT * FROM student WHERE minor_ID = '" . $minor . "' AND hidden = 0";
                    $result = $conn->query($sql);
                }
                else if($student == "Holds")
                {
                    $searchResults .= "Holds: ";
                    if($holds == "Financial") // 1
                    {
                        $searchResults .= "Financial";
                        $sql = "SELECT * FROM student JOIN studenthold ON studenthold.student_ID = student.ID WHERE studenthold.hold_Type = 1 AND student.hidden = 0";
                        $result = $conn->query($sql);
                    }
                    else if($holds == "Academic") // 2
                    {
                        $searchResults .= "Academic";
                        $sql = "SELECT * FROM student JOIN studenthold ON studenthold.student_ID = student.ID WHERE studenthold.hold_Type = 2 AND student.hidden = 0";
                        $result = $conn->query($sql);
                    }
                    else if($holds == "Administrative") // 3
                    {
                        $searchResults .= "Administrative";
                        $sql = "SELECT * FROM student JOIN studenthold ON studenthold.student_ID = student.ID WHERE studenthold.hold_Type = 3 AND student.hidden = 0";
                        $result = $conn->query($sql);
                    }
                }
                else if($student == "GPA")
                {
                    $searchResults .= "GPA ";
                    if($GPAcompare == "Less Than")
                    {
                        $searchResults .= "< " . $GPAvalue;
                        $sql = "SELECT * FROM student WHERE gpa < '" . $GPAvalue . "' AND hidden = 0";
                        $result = $conn->query($sql);
                    }
                    else if($GPAcompare == "Less Than or Equal To")
                    {
                        $searchResults .= "<= " . $GPAvalue;
                        $sql = "SELECT * FROM student WHERE gpa <= '" . $GPAvalue . "' AND hidden = 0";
                        $result = $conn->query($sql);
                    }
                    else if($GPAcompare == "Equal To")
                    {
                        $GPAvalue = number_format($GPAvalue, 1, '.', '');
                        $searchResults .= "= " . $GPAvalue;
                        $sql = "SELECT * FROM student WHERE gpa = '" . $GPAvalue . "' AND hidden = 0";
                        $result = $conn->query($sql);
                    }
                    else if($GPAcompare == "Greater Than")
                    {
                        $searchResults .= "> " . $GPAvalue;
                        $sql = "SELECT * FROM student WHERE gpa >'" . $GPAvalue . "' AND hidden = 0";
                        $result = $conn->query($sql);
                    }
                    else if($GPAcompare == "Greater Than or Equal To")
                    {
                        $searchResults .= ">= " . $GPAvalue;
                        $sql = "SELECT * FROM student WHERE gpa >= '" . $GPAvalue . "' AND hidden = 0";
                        $result = $conn->query($sql);
                    }

                }
                else if($student == "Advised")
                {
                    $searchResults .= " Advised";
                    $sql = "SELECT * FROM student JOIN advisor ON advisor.student_ID = student.ID WHERE student.hidden = 0";
                    $result = $conn->query($sql);
                }
            }
        }
        else if($select1 == "Courses")
        {
            $searchResults .= "Courses: ";
            if($course == "All")
            {
                $searchResults .= "All";
                $sql = "SELECT * FROM course WHERE hidden = 0";
                $result = $conn->query($sql);
            }
            else if($course == "Department")
            {
                $searchResults .= "Department: " . $department2;
                $sql = "SELECT * FROM course WHERE department_Name = '" . $department2 . "' AND hidden = 0";
                $result = $conn->query($sql);
            }
            else if($course == "Semester")
            {
                $sql = "SELECT Season, Year FROM semesteryear WHERE semester_ID = '" . $semester . "'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $semesterSeason = $row["Season"];
                $semesterYear = $row["Year"];

                $searchResults .= "Semester: " . $semesterSeason . " " . $semesterYear;
                $sql = "SELECT DISTINCT course_ID FROM section WHERE semester_ID = '" . $semester . "'";
                $result = $conn->query($sql);
            }
        }
        else if($select1 == "Sections")
        {
            $searchResults .= "Sections: ";
            if($section == "All")
            {
                $searchResults .= "All";
                $sql = "SELECT * FROM section";
                $result = $conn->query($sql);
            }
            else if($section == "Department")
            {
                $searchResults .= "Department: " . $department3;
                $sql = "SELECT * FROM section WHERE department_Name = '" . $department3 . "'";
                $result = $conn->query($sql);
            }
            else if($section == "Semester")
            {
                $sql = "SELECT Season, Year FROM semesteryear WHERE semester_ID = '" . $semester2 . "'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $semesterSeason = $row["Season"];
                $semesterYear = $row["Year"];

                $searchResults .= "Semester: " . $semesterSeason . " " . $semesterYear;
                $sql = "SELECT * FROM section WHERE semester_ID = '" . $semester2 . "'";
                $result = $conn->query($sql);
            }
            else if($section == "Days")
            {
                $sql = "SELECT Days FROM days WHERE day_ID = '" . $days . "'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $daysName = $row["Days"];

                $searchResults .= "Days: " . $daysName;
                $sql = "SELECT * FROM section JOIN timeslot ON timeslot.timeslot_ID = section.timeslot_ID WHERE timeslot.day_ID = '" . $days . "'";
                $result = $conn->query($sql);
            }
            else if($section == "Period")
            {
                $sql = "SELECT period_Time FROM period WHERE period_ID = '" . $period . "'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $periodName = $row["period_Time"];

                $searchResults .= "Period: " . $periodName;
                $sql = "SELECT * FROM section JOIN timeslot ON timeslot.timeslot_ID = section.timeslot_ID WHERE timeslot.period_ID = '" . $period . "'";
                $result = $conn->query($sql);
            }
        }
        else if($select1 == "Rooms")
        {
            $searchResults .= "Rooms: ";
            if($rooms == "All")
            {
                $searchResults .= "All";
                $sql = "SELECT * FROM room";
                $result = $conn->query($sql);
            }
            else if($rooms == "Buildings")
            {
                $sql = "SELECT building_Name FROM building WHERE building_Code = '" . $building . "'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $buildingName = $row["building_Name"];

                $searchResults .= "Building: " . $buildingName;
                $sql = "SELECT * FROM room WHERE building_Code = '" . $building . "'";
                $result = $conn->query($sql);
            }
        }      

        // <option>Grade</option>  

    ?>

    <form method="post" action="ViewSystemData.php">
        <p>
            <label>Find:
                <select onchange="select1Check(this);" name="select1">
                    <option selected></option>
                    <option>Users</option>
                    <option>Courses</option>
                    <option>Sections</option>
                    <option>Rooms</option>
                </select>
            </label>
        </p>
        
        <div id="ifUsers" style="display: none;">
        <p>
            <label>
                <select onchange="usersCheck(this);" name="users">
                    <option>All</option>
                    <option>Faculty</option>
                    <option>Students</option>
                </select>
            </label>
        </p>

            <div id="ifFaculty" style="display: none;">
            <p>
                <label>
                    <select onchange="facultyCheck(this);" name="faculty">
                        <option>All</option>
                        <option>Employment Status</option>
                        <option>Department</option>
                        <option>Advisors</option>
                    </select>
                </label>
            </p>

                <div id="ifEmployStatus" style="display: none;">
                <p>
                    <label>
                        <select name="employStatus">
                            <option>Full-Time</option>
                            <option>Part-Time</option>
                        </select>
                    </label>
                </p>
                </div>

                <div id="ifDepartment" style="display: none;">
                <p>
                    <label>
                        <select name="department">
                            <?php while($row = mysqli_fetch_array($departmentList))
                            {echo "<option>" . $row["department_Name"] . "</option>";} ?>
                        </select>
                    </label>
                </p>
                </div>

                <div id="ifBirthyear" style="display: none;">
                <p>
                    <label>
                        <select name="birthyear">
                            <?php while($row = mysqli_fetch_array($facultyDOB))
                            {
                                $dob = explode("-",$row["DOB"]);
                                $year = $dob[0];
                                echo "<option>" . $year . "</option>";
                            } ?>
                        </select>
                    </label>
                </p>
                </div>

            </div>

            <div id="ifStudents" style="display: none;">
            <p>
                <label>
                    <select onchange="studentCheck(this);" name="student">
                        <option>All</option>
                        <option>Enrollment Status</option>
                        <option>Enrollment Type</option>
                        <option>Year</option>
                        <option>Major</option>
                        <option>Minor</option>
                        <option>Holds</option>
                        <option>GPA</option>
                        <option>Advised</option>
                    </select>
                </label>
            </p>

                <div id="ifEnrollStatus" style="display: none;">
                <p>
                    <label>
                        <select name="enrollStatus">
                            <option>Full-Time</option>
                            <option>Part-Time</option>
                        </select>
                    </label>
                </p>
                </div>

                <div id="ifEnrollType" style="display: none;">
                <p>
                    <label>
                        <select name="enrollType">
                            <option>Undergraduate</option>
                            <option>Graduate</option>
                        </select>
                    </label>
                </p>
                </div>

                <div id="ifYear" style="display: none;">
                <p>
                    <label>
                        <select name="year">
                            <option>Freshman</option>
                            <option>Sophomore</option>
                            <option>Junior</option>
                            <option>Senior</option>
                            <option>Post-Grad</option>
                        </select>
                    </label>
                </p>
                </div>

                <div id="ifMajor" style="display: none;">
                <p>
                    <label>
                        <select name="major">
                            <?php while($row = mysqli_fetch_array($majorList))
                            {echo "<option value = '" . $row["department_ID"] . "'>" . $row["department_Name"] . "</option>";} ?>
                        </select>
                    </label>
                </p>
                </div>

                <div id="ifMinor" style="display: none;">
                <p>
                    <label>
                        <select name="minor">
                            <?php while($row = mysqli_fetch_array($minorList))
                            {echo "<option value = '" . $row["department_ID"] . "'>" . $row["department_Name"] . "</option>";} ?>
                        </select>
                    </label>
                </p>
                </div>

                <div id="ifHolds" style="display: none;">
                <p>
                    <label>
                        <select name="holds">
                            <option>Financial</option>
                            <option>Academic</option>
                            <option>Administrative</option>
                        </select>
                    </label>
                </p>
                </div>

                <div id="ifGPA" style="display: none;">
                <p>
                    <label>
                        <select name="GPAcompare">
                            <option>Less Than</option>
                            <option>Less Than or Equal To</option>
                            <option>Equal To</option>
                            <option>Greater Than</option>
                            <option>Greater Than or Equal To</option>
                        </select>
                    </label>
                    <label>
                        <input type="number" name="GPAvalue" max="4.0" min="0.0" step="0.1">
                    </label>
                </p>
                </div>

            </div>

        </div>

        <div id="ifCourses" style="display: none;">
        <p>
            <label>
                <select onchange="courseCheck(this);" name="course">
                    <option>All</option>
                    <option>Department</option>
                    <option>Semester</option>
                </select>
            </label>
        </p>

            <div id="ifDepartment2" style="display: none;">
            <p>
                <label>
                    <select name="department2">
                        <?php while($row = mysqli_fetch_array($departmentList2))
                        {echo "<option>" . $row["department_Name"] . "</option>";} ?>
                    </select>
                </label>
            </p>
            </div>
            <div id="ifSemester" style="display: none;">
            <p>
                <label>
                    <select name="semester">
                        <?php while($row = mysqli_fetch_array($semesterList1))
                        {echo "<option value ='" . $row["semester_ID"] . "'>" . $row["Season"] . " " . $row["Year"] . "</option>";} ?>
                    </select>
                </label>
            </p>
            </div>

        </div>

        <div id="ifSections" style="display: none;">
        <p>
            <label>
                <select onchange="sectionCheck(this);" name="section">
                    <option>All</option>
                    <option>Department</option>
                    <option>Semester</option>
                    <option>Days</option>
                    <option>Period</option>
                </select>
            </label>
        </p> 

            <div id="ifDepartment3" style="display: none;">
            <p>
                <label>
                    <select name="department3">
                        <?php while($row = mysqli_fetch_array($departmentList3))
                        {echo "<option>" . $row["department_Name"] . "</option>";} ?>
                    </select>
                </label>
            </p>
            </div>
            <div id="ifSemester2" style="display: none;">
            <p>
                <label>
                    <select name="semester2">
                        <?php while($row = mysqli_fetch_array($semesterList2))
                        {echo "<option value ='" . $row["semester_ID"] . "'>" . $row["Season"] . " " . $row["Year"] . "</option>";} ?>
                    </select>
                </label>
            </p>
            </div>
            <div id="ifDays" style="display: none;">
            <p>
                <label>
                    <select name="days">
                        <?php while($row = mysqli_fetch_array($daysList))
                        {echo "<option value ='" . $row["day_ID"] . "'>" . $row["Days"] . "</option>";} ?>
                    </select>
                </label>
            </p>
            </div>
            <div id="ifPeriod" style="display: none;">
            <p>
                <label>
                    <select name="period">
                        <?php while($row = mysqli_fetch_array($periodList))
                        {echo "<option value ='" . $row["period_ID"] . "'>" . $row["period_Time"] . "</option>";} ?>
                    </select>
                </label>
            </p>
            </div>

        </div> 

        <div id="ifRooms" style="display: none;">
        <p>
            <label>
                <select onchange="RoomsCheck(this);" name="rooms">
                    <option>All</option>
                    <option>Buildings</option>
                </select>
            </label>
        </p>

            <div id="ifBuilding" style="display: none;">
            <p>
                <label>
                    <select name="building">
                        <?php while($row = mysqli_fetch_array($buildingList))
                        {echo "<option value = '" . $row["building_Code"] . "'>" . $row["building_Name"] . "</option>";} ?>
                    </select>
                </label>
            </p>
            </div>

        </div>                

        <script>
            function select1Check(that) 
            {
                if (that.value == "Users") {
                    document.getElementById("ifUsers").style.display = "block";
                } else {
                    document.getElementById("ifUsers").style.display = "none";
                }
                if (that.value == "Courses") {
                    document.getElementById("ifCourses").style.display = "block";
                } else {
                    document.getElementById("ifCourses").style.display = "none";
                }
                if (that.value == "Sections") {
                    document.getElementById("ifSections").style.display = "block";
                } else {
                    document.getElementById("ifSections").style.display = "none";
                }
                if (that.value == "Rooms") {
                    document.getElementById("ifRooms").style.display = "block";
                } else {
                    document.getElementById("ifRooms").style.display = "none";
                }

            }
            function usersCheck(that) 
            {
                if (that.value == "Faculty") {
                    document.getElementById("ifFaculty").style.display = "block";
                } else {
                    document.getElementById("ifFaculty").style.display = "none";
                }
                if (that.value == "Students") {
                    document.getElementById("ifStudents").style.display = "block";
                } else {
                    document.getElementById("ifStudents").style.display = "none";
                }
            }

            function facultyCheck(that) 
            {
                if (that.value == "Employment Status") {
                    document.getElementById("ifEmployStatus").style.display = "block";
                } else {
                    document.getElementById("ifEmployStatus").style.display = "none";
                }
                if (that.value == "Department") {
                    document.getElementById("ifDepartment").style.display = "block";
                } else {
                    document.getElementById("ifDepartment").style.display = "none";
                }
                // birth year
            }

            function studentCheck(that) 
            {
                if (that.value == "Enrollment Status") {
                    document.getElementById("ifEnrollStatus").style.display = "block";
                } else {
                    document.getElementById("ifEnrollStatus").style.display = "none";
                }
                if (that.value == "Enrollment Type") {
                    document.getElementById("ifEnrollType").style.display = "block";
                } else {
                    document.getElementById("ifEnrollType").style.display = "none";
                }
                if (that.value == "Year") {
                    document.getElementById("ifYear").style.display = "block";
                } else {
                    document.getElementById("ifYear").style.display = "none";
                }
                if (that.value == "Major") {
                    document.getElementById("ifMajor").style.display = "block";
                } else {
                    document.getElementById("ifMajor").style.display = "none";
                }
                if (that.value == "Minor") {
                    document.getElementById("ifMinor").style.display = "block";
                } else {
                    document.getElementById("ifMinor").style.display = "none";
                }
                if (that.value == "Holds") {
                    document.getElementById("ifHolds").style.display = "block";
                } else {
                    document.getElementById("ifHolds").style.display = "none";
                }
                if (that.value == "GPA") {
                    document.getElementById("ifGPA").style.display = "block";
                } else {
                    document.getElementById("ifGPA").style.display = "none";
                }
                // birth year
            }

            function courseCheck(that) 
            {
                if (that.value == "Department") {
                    document.getElementById("ifDepartment2").style.display = "block";
                } else {
                    document.getElementById("ifDepartment2").style.display = "none";
                }
                if (that.value == "Semester") {
                    document.getElementById("ifSemester").style.display = "block";
                } else {
                    document.getElementById("ifSemester").style.display = "none";
                }
            }

            function sectionCheck(that) 
            {
                if (that.value == "Department") {
                    document.getElementById("ifDepartment3").style.display = "block";
                } else {
                    document.getElementById("ifDepartment3").style.display = "none";
                }
                if (that.value == "Semester") {
                    document.getElementById("ifSemester2").style.display = "block";
                } else {
                    document.getElementById("ifSemester2").style.display = "none";
                }
                if (that.value == "Days") {
                    document.getElementById("ifDays").style.display = "block";
                } else {
                    document.getElementById("ifDays").style.display = "none";
                }
                if (that.value == "Period") {
                    document.getElementById("ifPeriod").style.display = "block";
                } else {
                    document.getElementById("ifPeriod").style.display = "none";
                }
            }
            
            function RoomsCheck(that) 
            {
                if (that.value == "Buildings") {
                    document.getElementById("ifBuilding").style.display = "block";
                } else {
                    document.getElementById("ifBuilding").style.display = "none";
                }
            }

        </script>
            
            <input type="submit" value="Search">
        </form>
    </p>

    <?php
        
        if($select1 != "")
            echo "<h2>Search results for " . $searchResults . "</h2>";
        
        if(mysqli_num_rows($result)==0)
            echo "No results found.";
        else
        {
            echo mysqli_num_rows($result);
        }
     
    ?>

</body>
</html>