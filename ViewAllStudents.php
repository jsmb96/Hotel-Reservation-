<?php
session_start();
?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <a href = "BackToHomepage.php">
            <img src = "Logo1.png" style="width:250px;height:183px;">
         </a>
      <style type = "text/css">
         h1  { color: black;}
         body  { background-color: lightgray; }
         p     { color: black; }
      </style>
    <style>
    table, th, td {border: 1px solid black;}
	</style>
    <title>Update Student Account Holds</title>
</head>

<body>
    <h1>Student List</h1>

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

        $value = isset($_POST['search']) ? $_POST['search'] : 0;
        $column = isset($_POST['in']) ? $_POST['in'] : 0;

    	$sql = "SELECT * FROM Student WHERE ". $column . " LIKE '%" . $value . "%' AND hidden = 0";
		$result = $conn->query($sql);

   	?>

    <a href="BackToHomepage.php">Back to Homepage</a><br>
    <p>
        <form method="post" action="ViewAllStudents.php">
            <label>Search:
                <input name="search" type="text" value="" size="15"  maxlength="255">
            </label>
            <label>in
                <select name="in">
                    <option>ID</option>
                    <option value="first_Name">First Name</option>
                    <option value="last_Name">Last Name</option>
                </select>
            </label>
            <input type="submit" value="Search">
        </form>
    </p> 

    <form method="post" action="UpdateStudentHold.php">
   	<table>
   		<tr>
   			<th>User ID</th>
   			<th>User Type</th>
        <th>First Name</th>
        <th>Last Name</th>
   			<th>Holds</th>
   		</tr>
        <?php while($row = mysqli_fetch_array($result))
        {
            echo "<tr><td>" . $row["ID"] . "</td>
                    <td>" . $row["type"] . "</td>
                    <td>" . $row["first_Name"] . "</td>
                    <td>" . $row["last_Name"] . "</td>
                    <td><button name='ID' type='submit' value='"
                    . $row["ID"] . "'> View Holds </button>
                    </td></tr>";
        } ?>
    </table>
    </form>

</body>
</html>