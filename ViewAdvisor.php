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
    <title>View Advisor</title>
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

        if(($_SESSION["type"] != "Admin") && ($_SESSION["type"] != "Student"))
            {header('Location: UserHomepage.php');}

        $studentID = isset($_POST['studentID']) ? $_POST['studentID'] : $_SESSION["ID"];

        echo "<h1>";
        if($_SESSION["type"] == "Admin")
        {
            $sql = "SELECT first_Name, last_Name FROM student WHERE ID = '" . $studentID . "'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            $firstName = $row["first_Name"];
            $lastName = $row["last_Name"];

            echo $firstName . " " . $lastName . "'s ";
        }
        echo "Advisor Information</h1>";
        echo "<a href='BackToHomepage.php'>Back to Homepage</a><br>";
        if($_SESSION["type"] == "Admin")
            { echo "<a href='ViewUserInfo.php'>View User Info</a><br>";}

        $sql = "SELECT faculty_ID FROM advisor WHERE student_ID = '" . $studentID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $ID = $row["faculty_ID"];
        $sql =  "SELECT  * FROM faculty WHERE ID = '" . $ID . "'";
        $result2 =  $conn->query($sql);

        if(mysqli_num_rows($result2)==0)
        {
            echo "No advisor found.";

            $sql = "SELECT ID, first_Name, last_Name, department_Name FROM faculty";
            $facultyList = $conn->query($sql);

            echo "<p><form method='post' action='UpdateAdvisor.php'>";
            echo "<input type = 'hidden' name = 'studentID' value = '" . $studentID . "'>";
            echo "<label>Set Advisor: <select name='facultyID'>";
            echo "<option value = 0>None</option>";
            while($row = mysqli_fetch_array($facultyList))
            { 
                echo "<option value = '" . $row["ID"] . "'>"
                    . $row["first_Name"] . " " . $row["last_Name"] . " (" . $row["department_Name"] . ")</option>";
            }
            echo "</select></label>
                <input type='submit' value='Update'>
                </form></p>";
        }
        else
        {
            $row = $result2->fetch_assoc();

            $fname = $row["first_Name"];
            $lname = $row["last_Name"];
            $email = $row["email"];
            $phoneNumber = $row["phone_Number"];
            $officeHours = $row["office_Hours"];

            echo "<p><table><tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Office Hours</th>
                </tr><tr>
                <td>" . $fname . "</td>
                <td>" . $lname . "</td>
                <td>" . $email . "</td>
                <td>" . $phoneNumber . "</td>
                <td>" . $officeHours . "</td>
                </tr></table></p>";

            if($_SESSION["type"] == "Admin")
            {
                echo "<form method='post' action='ViewUserInfo.php'>
                    <button name='ID' type='submit' value='" . $ID . "'>View Faculty Info</button>
                    </form>";
            }    
        }
         
    ?>

</body>
</html>