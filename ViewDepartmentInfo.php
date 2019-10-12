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
    <title>Department Info</title>
</head>

<body>
    <h1>Department Info</h1>
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

        if($_SESSION["type"] == "Admin")
            {echo "<a href='AddDepartment.php'>Add a Department</a><br>";}

        $sql = "SELECT department_Name FROM department WHERE hidden = 0";
        $departmentList = $conn->query($sql);

        $departmentName = isset($_POST['departmentName']) ? $_POST['departmentName'] : "None";

        $sql = "SELECT * FROM department WHERE department_Name = '" . $departmentName . "' AND hidden = 0";
        $result = $conn->query($sql);
        $departmentInfo = $result->fetch_assoc();

        $departmentID = $departmentInfo["department_ID"];
        $buildingCode = $departmentInfo["building_Code"];
        $facultyID = $departmentInfo["faculty_ID"];
        $email = $departmentInfo["email_Address"];
        $phone = $departmentInfo["phone_Number"];

        $sql = "SELECT building_Name FROM building WHERE building_Code = '" . $buildingCode . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $buildingName = $row["building_Name"];

        $sql = "SELECT first_Name, last_Name FROM faculty WHERE ID = '" . $facultyID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $firstName = $row["first_Name"];
        $lastName = $row["last_Name"];

        $sql = "SELECT * FROM course WHERE department_Name = '" . $departmentName . "' AND hidden = 0";
        $courseList = $conn->query($sql);

        $sql = "SELECT * FROM majorrequirements WHERE major_ID = '" . $departmentID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $majorCourse1 = $row["course_1"];
        $majorCourse2 = $row["course_2"];
        $majorCourse3 = $row["course_3"];
        $majorCourse4 = $row["course_4"];
        $majorCourse5 = $row["course_5"];

        $sql = "SELECT * FROM minorrequirements WHERE minor_ID = '" . $departmentID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $minorCourse1 = $row["course_1"];
        $minorCourse2 = $row["course_2"];
        $minorCourse3 = $row["course_3"];

    ?>

    <p>
        <form method="post" action="ViewDepartmentInfo.php">
            <label>
                Select department:
                <select name = "departmentName">
                    <option>None</option>
                    <?php while($row = mysqli_fetch_array($departmentList))
                    {
                        if($row["department_Name"] == $departmentName)
                            echo "<option selected>";
                        else
                            echo "<option>";
                        echo $row["department_Name"] . "</option>";
                    } ?>
                </select>
            </label>
            <input type="submit" value="Update">
        </form>
    </p>

    <?php
        if($departmentName != "None")
        {
            echo "<p><table><tr>
                <th>Department ID</th>
                <th>Department Name</th>
                <th>Head of Department</th>
                <th>Main Building</th>
                <th>Email Address</th>
                <th>Phone Number</th>
                </tr><tr>";
            echo "<td>" . $departmentID . "</td>
                <td>" . $departmentName . "</td>
                <td>" . $firstName . " " . $lastName . "</td>
                <td>" . $buildingName . "</td>
                <td>" . $email . "</td>
                <td>" . $phone . "</td>
                </tr></table></p>";

                if($_SESSION["type"] == "Admin")
                {
                    echo "<form method='post' action='UpdateDepartmentInfo.php'>
                        <button name='ID' type='submit' value='" . $departmentID . "'>Update Department Info</button>
                        </form>";
                    echo "<form method='post' action='RemoveDepartment.php'>
                        <button name='ID' type='submit' value='" . $departmentID . "'>Remove Department</button>
                        </form>";
                }
        }
        
        if($_SESSION["Error"] != "") echo "<p>" . $_SESSION["Error"] . "</p>"; 
        $_SESSION["Error"] = "";

        echo "<h2>Courses in this department</h2>";

        if(mysqli_num_rows($courseList)==0)
            echo "No results found.";
        else
        {
            echo "<form method='post' action='ViewCourseInfo.php'>";
            echo "<table><tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>More Info</th>
                <th>Major Requirement</th>
                <th>Minor Requirement</th>
                </tr>";
            while($row = mysqli_fetch_array($courseList))
            {
                echo "<tr><td>" . $row["course_ID"] . "</td>
                        <td>" . $row["course_Name"] . "</td>
                        <td><button name='ID' type='submit' value='"
                        . $row["course_ID"] . "'> View </button>
                        </td>";
                if(($row["course_ID"] == $majorCourse1) ||
                    ($row["course_ID"] == $majorCourse2) ||
                    ($row["course_ID"] == $majorCourse3) ||
                    ($row["course_ID"] == $majorCourse4) ||
                    ($row["course_ID"] == $majorCourse5))
                    echo "<td>Required</td>";
                else
                    echo "<td>Not Required</td>";

                if(($row["course_ID"] == $minorCourse1) ||
                    ($row["course_ID"] == $minorCourse2) ||
                    ($row["course_ID"] == $minorCourse3))
                    echo "<td>Required</td>";
                else
                    echo "<td>Not Required</td>";
                echo "</tr>";
            }
            echo "</table></form>";
        }

    ?>
    

</body>
</html>