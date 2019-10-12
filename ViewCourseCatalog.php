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
    <title>Course Catalog</title>
</head>

<body>
    <h1>Course Catalog</h1>

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

        $sql = "SELECT * FROM course WHERE ". $column . " LIKE '%" . $value . "%' AND hidden = 0";
		$courseList = $conn->query($sql);

        echo "<a href='BackToHomepage.php'>Back to Homepage</a><br>";
        if($_SESSION["type"] == "Admin")
        {
            echo "<a href='AddCourseToCatalog.php'>Add Course</a><br>";
        }
   	?>
    
    <p>
        <form method="post" action="ViewCourseCatalog.php">
            <label>Search:
                <input name="search" type="text" value="" size="15"  maxlength="255">
            </label>
            <label>in
                <select name="in">
                    <option value="course_ID">Course ID</option>
                    <option value="course_Name">Course Name</option>
                    <option value="department_Name">Department Name</option>
                </select>
            </label>
            <input type="submit" value="Search">
        </form>
    </p>

    <?php
        if(($column != "") && ($value != ""))
        {
            echo "<h2> Search results for " . $columnName . ": " . $value . "</h2>";
        }

        if(mysqli_num_rows($courseList)==0)
            echo "No results found.";
        else
        {
            echo "<form method='post' action='ViewCourseInfo.php'>";
            echo "<p><table><tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Department Name</th>
                <th>More Info</th>
                </tr>";
            while($row = mysqli_fetch_array($courseList))
            {
                echo "<tr><td>" . $row["course_ID"] . "</td>
                        <td>" . $row["course_Name"] . "</td>
                        <td>" . $row["department_Name"] . "</td>
                        <td><button name='ID' type='submit' value='"
                        . $row["course_ID"] . "'> View </button>
                        </td></tr>";
            }
            echo "</table></p></form>";
        }
    ?>

</body>
</html>