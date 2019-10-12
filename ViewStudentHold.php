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
    <title>View Student Hold</title>
</head>

<body>
    <h1>View Student Hold</h1>

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

    $sql = "SELECT * FROM studenthold WHERE student_ID = '" . $_SESSION["ID"] . "'";
		$holds = $conn->query($sql);


    // $holdType = $holds["hold_Type"];

    // $sql = "SELECT hold_Name FROM hold WHERE hold_Type = '" . $holdType . "'";
    // $holds = $conn->query($sql);

   	?>

    <a href="BackToHomepage.php">Back to Homepage</a><br><br>

   	<table>
   		<tr>
   			<th>Student ID</th>
   			<th>Hold Name</th>
   		</tr>
        <?php while($row = mysqli_fetch_assoc($holds))
        {
            // $holdType = $row["hold_Type"];
            $sql = "SELECT hold_Name FROM hold WHERE hold_Type = '" . $row["hold_Type"] . "'";
            $result = $conn->query($sql);
            $nameTable = $result->fetch_assoc();

            echo "<tr><td>" . $_SESSION["ID"] . "</td>
                    <td>" . $nameTable["hold_Name"] . "</td>
                  </tr>";
        } ?>
    </table>
    </form>

</body>
</html>