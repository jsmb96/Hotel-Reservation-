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
    <title>My Personal Info</title>
</head>

<body>
    <h1>Personal Information</h1>
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

        if(is_null($_SESSION["ID"]))
            header('Location: Login.php');

    $sql = "SELECT * FROM user WHERE ID = '" . $_SESSION["ID"] . "'";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
   	
      $ID = $row["ID"];
   		$type = $row["type"];
      $email = $row["email"];
      $fname = $row["first_Name"];
      $mname = $row["middle_Initial"];
      $lname = $row["last_Name"];
      $phone = $row["phone_Number"];
      $dob = $row["DOB"];

      if($type == "Admin" || $type == "Research")
      {
        echo "<p><table>
              <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Email Address</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Date of Birth</th>
              </tr>
              <tr>
                <td>". $ID ."</td>
                <td>". $type ."</td>
                <td>". $email ."</td>
                <td>". $fname . " " . $mname . ". " . $lname . "</td>
                <td>". $phone ."</td>
                <td>". $dob ."</td>
              </tr>
            </table></p>";
        }
      else if($type == "Student")
      {
        $sql2 = "SELECT year, enrollment_Type, enrollment_Status, major_ID, minor_ID FROM student WHERE ID = '" . $_SESSION["ID"] . "'";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();

        $year = $row2["year"];
        $enrollType = $row2["enrollment_Type"];
        $enrollStatus = $row2["enrollment_Status"];
        $majorID = $row2["major_ID"];
        $minorID = $row2["minor_ID"];

        $sql = "SELECT major_Title FROM major WHERE major_ID = '" . $majorID . "'";
        $result = $conn->query($sql);
        $row3 = $result->fetch_assoc();

        $majorTitle = $row3["major_Title"];

        $sql = "SELECT minor_Title FROM minor WHERE minor_ID = '" . $minorID . "'";
        $result = $conn->query($sql);
        $row4 = $result->fetch_assoc();

        $minorTitle = $row4["minor_Title"];

        echo "<p><table>
              <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Email Address</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Date of Birth</th>
                <th>Year</th>
                <th>Major</th>
                <th>Minor</th>
                <th>Enrollment Type</th>
                <th>Enrollment Status</th>
              </tr>
              <tr>
                <td>". $ID ."</td>
                <td>". $type ."</td>
                <td>". $email ."</td>
                <td>". $fname . " " . $mname . ". " . $lname . "</td>
                <td>". $phone ."</td>
                <td>". $dob ."</td>
                <td>". $year ."</td>
                <td>". $majorTitle ."</td>
                <td>". $minorTitle ."</td>
                <td>". $enrollType ."</td>
                <td>". $enrollStatus ."</td>
              </tr>
            </table></p>";
      }
      else if($type == "Faculty")
      {
        $sql2 = "SELECT employment_Status, department_Name, office_Hours FROM faculty WHERE ID = '" . $_SESSION["ID"] . "'";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();

        $employStatus = $row2["employment_Status"];
        $department = $row2["department_Name"];
        $office = $row2["office_Hours"];

        echo "<p><table>
              <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Email Address</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Date of Birth</th>
                <th>Employment Status</th>
                <th>Department</th>
                <th>Office Hours</th>
              </tr>
              <tr>
                <td>". $ID ."</td>
                <td>". $type ."</td>
                <td>". $email ."</td>
                <td>". $fname . " " . $mname . ". " . $lname . "</td>
                <td>". $phone ."</td>
                <td>". $dob ."</td>
                <td>". $employStatus ."</td>
                <td>". $department ."</td>
                <td>". $office ."</td>
              </tr>
            </table></p>";
      }
   	?>

    <a href="UpdatePersonalInfo.php">Update Personal Info</a><br>
    <a href="UpdatePassword.php">Update Password</a><br>

    <p>
        <?php 
        if($_SESSION["Error"] != "") echo $_SESSION["Error"]; 
        $_SESSION["Error"] = "";
        ?>
    </p>

</body>
</html>