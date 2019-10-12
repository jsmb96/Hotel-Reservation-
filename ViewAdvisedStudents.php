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
    <title>View Advised Students</title>
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

        echo "<h1>";
        if($_SESSION["type"] == "Admin")
        {
            $sql = "SELECT first_Name, last_Name FROM faculty WHERE ID = '" . $facultyID . "'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            $firstName = $row["first_Name"];
            $lastName = $row["last_Name"];

            echo $firstName . " " . $lastName . "'s ";
        }
        echo "Advised Students</h1>";
        echo "<a href='BackToHomepage.php'>Back to Homepage</a><br>";

        if($_SESSION["type"] == "Admin")
            { echo "<a href='ViewUserInfo.php'>View User Info</a><br>";}

        $sql = "SELECT * FROM advisor WHERE faculty_ID = '" . $facultyID . "'";
        $result = $conn->query($sql);

        if(mysqli_num_rows($result)==0)
        {
            echo "No students found.";

            $sql = "SELECT ID, first_Name, last_Name FROM student";
            $studentList = $conn->query($sql);

            echo "<p><form method='post' action='UpdateAdvisor.php'>";
            echo "<input type = 'hidden' name = 'facultyID' value = '" . $facultyID . "'>";
            echo "<label>Add Student: <select name='studentID'>";
            echo "<option value = 0>None</option>";
            while($row = mysqli_fetch_array($studentList))
            { 
                echo "<option value = '" . $row["ID"] . "'>" 
                    . $row["first_Name"] . " " . $row["last_Name"] . "</option>";
            }
            echo "</select></label>
                <input type='submit' value='Add'>
                </form></p>";
        }
        else
        {
            echo "";
            echo "<p><table><tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>More Info</th>";
            if($_SESSION["type"] == "Admin")
            {echo "<th>Action</th>";}
            echo "</tr>";
            while($row = mysqli_fetch_array($result))
            {
                $sql =  "SELECT  * FROM student WHERE ID = '" . $row["student_ID"] . "'";
                $result2 =  $conn->query($sql);
                $row2 = $result2->fetch_assoc();

                echo "<tr><td>" . $row2["first_Name"] . "</td>
                        <td>" . $row2["last_Name"] . "</td>
                        <td>" . $row2["email"] . "</td>
                        <td>" . $row2["phone_Number"] . "</td>
                        <td><form method='post' action='ViewUserInfo.php'>
                        <button name='ID' type='submit' value='"
                            . $row2["ID"] . "'> View </button></form></td>";
                if($_SESSION["type"] == "Admin")
                {
                    echo "<td><form method='post' action='RemoveAdvisor.php'>
                        <input type='hidden' name='facultyID' value='" . $facultyID . "'>
                        <button name='studentID' type='submit' value='" . $row["student_ID"] . "'>Remove</button>
                        </form></td>";
                }
                echo "</tr>";
            }
            echo "</table></p></form>";
        }

    ?>

</body>
</html>