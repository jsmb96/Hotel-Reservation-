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
    <title>Course Info</title>
</head>

<body>
    <h1>Detailed Course Information</h1>
    <a href="BackToHomepage.php">Back to Homepage</a><br>
    <a href="ViewCourseCatalog.php">View Course Catalog</a><br> 

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

        $ID = isset($_POST['ID']) ? $_POST['ID'] : 0;
        if($ID != 0)
            $_SESSION["courseID"] = $_POST["ID"];
        else
            $ID = $_SESSION["courseID"];

        $sql = "SELECT * FROM course WHERE course_ID = '" . $ID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
    
        $ID = $row["course_ID"];
        $cname = $row["course_Name"];
        $department = $row["department_Name"];
        $credits = $row["credits"];
        $description = $row["description"];

        $sql = "SELECT * FROM prerequisite WHERE course_ID = '" . $ID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $prereq1 = $row["prereq_1"];
        $prereq2 = $row["prereq_2"];
        $prereq3 = $row["prereq_3"];
        $prereq4 = $row["prereq_4"];
        $prereq5 = $row["prereq_5"];
    ?>

    <p><table>
        <tr>
            <th>Course ID</th>
            <th>Course Name</th>
            <th>Department</th>
            <th>Credits</th>
            <th>Description</th>
        </tr>
        <tr>
            <td><?php echo $ID ?></td>
            <td><?php echo $cname ?></td>
            <td><?php echo $department ?></td>
            <td><?php echo $credits ?></td>
            <td><?php echo $description ?></td>
        </tr>
    </table></p>

    <?php
        if($_SESSION["type"] == "Admin")
        {
            echo "<form method='post' action='UpdateCourseInfo.php'>
                    <button name='ID' type='submit' value='". $ID . "'> Edit Info </button>
                </form>";
            echo "<form method='post' action='RemoveCourseFromCatalog.php'>
                    <button name='ID' type='submit' value='". $ID . "'> Remove Course from Catalog</button>
                </form>";
        }
    ?>

    <p>
        <?php 
        if($_SESSION["Error"] != "") echo $_SESSION["Error"]; 
        $_SESSION["Error"] = "";
        ?>
    </p>

    <form method="post" action="ViewCourseInfo.php">
            <?php
                if(!is_null($prereq1) && $prereq1 != 0)
                {
                    $sql = "SELECT * FROM course WHERE course_ID = '" . $prereq1 . "'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();

                    echo "<h2>Prerequisite Courses</h2>";
                    echo "<p><table>
                            <tr>
                                <th>Course Name</th>
                                <th>More Info</th>
                            </tr>
                            <tr>
                                <td> " . $row["course_Name"] . "</td>
                                <td><button name='ID' type='submit' value='"
                                . $row["course_ID"] . "'> View </button>
                                 </td>
                            </tr>";
                    if(!is_null($prereq2) && $prereq2 != 0)
                    {
                        $sql = "SELECT * FROM course WHERE course_ID = '" . $prereq2 . "'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();

                        echo "<tr>
                                <td> " . $row["course_Name"] . "</td>
                                <td><button name='ID' type='submit' value='"
                                . $row["course_ID"] . "'> View </button>
                                 </td>
                            </tr>";
                    }
                    if(!is_null($prereq3) && $prereq3 != 0)
                    {
                        $sql = "SELECT * FROM course WHERE course_ID = '" . $prereq3 . "'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();

                        echo "<tr>
                                <td> " . $row["course_Name"] . "</td>
                                <td><button name='ID' type='submit' value='"
                                . $row["course_ID"] . "'> View </button>
                                 </td>
                            </tr>";
                    }
                    if(!is_null($prereq4) && $prereq4 != 0)
                    {
                        $sql = "SELECT * FROM course WHERE course_ID = '" . $prereq4 . "'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();

                        echo "<tr>
                                <td> " . $row["course_Name"] . "</td>
                                <td><button name='ID' type='submit' value='"
                                . $row["course_ID"] . "'> View </button>
                                 </td>
                            </tr>";
                    }
                    if(!is_null($prereq5) && $prereq5 != 0)
                    {
                        $sql = "SELECT * FROM course WHERE course_ID = '" . $prereq5 . "'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();

                        echo "<tr>
                                <td> " . $row["course_Name"] . "</td>
                                <td><button name='ID' type='submit' value='"
                                . $row["course_ID"] . "'> View </button>
                                 </td>
                            </tr>";
                    }
                    echo "</table></p>";
                }

            ?>
    </form>

</body>
</html>