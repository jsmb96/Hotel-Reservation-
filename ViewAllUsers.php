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
    <title>User List</title>
</head>

<body>
    <h1>User List</h1>

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

        if($_SESSION["type"] != "Admin")
            {header('Location: UserHomepage.php');}

        $value = isset($_POST['search']) ? $_POST['search'] : "";
        $column = isset($_POST['in']) ? $_POST['in'] : "ID";
        $columnName = "";

        if($column == "type") $columnName = "Type";
        else $columnName = $column;

        if(($column == "Name") && ($column != ""))
        {
            $firstName = "";
            $lastName = "";

            if($value != "")
            {
                $name = explode(" ", $value);
                $firstName = $name[0];
                $lastName = isset($name[1]) ? $name[1] : "";      
            }

            $sql = "SELECT * FROM user WHERE first_Name = '" . $firstName . "' OR last_Name = '" . $lastName . "'";
            $userList = $conn->query($sql);

            if(mysqli_num_rows($userList)==0)
            {
                $sql = "SELECT * FROM user WHERE last_Name = '" . $firstName . "'";
                $userList = $conn->query($sql);
            }
        }
        else if(($column == "Major") && ($column != ""))
        {
            $sql = "SELECT major_ID FROM major WHERE major_Title LIKE '%" . $value . "%'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $majorID = $row["major_ID"];

            $sql = "SELECT * FROM student WHERE major_ID LIKE '" . $majorID . "' AND hidden = 0";
            $userList = $conn->query($sql);
        }
        else if(($column == "Minor") && ($column != ""))
        {
            $sql = "SELECT minor_ID FROM minor WHERE minor_Title = '%" . $value . "%'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $minorID = $row["minor_ID"];

            $sql = "SELECT * FROM student WHERE minor_ID = '" . $minorID . "' AND hidden = 0";
            $userList = $conn->query($sql);
        }
        else
        {
            $sql = "SELECT * FROM user WHERE ". $column . " LIKE '%" . $value . "%' AND hidden = 0";
            $userList = $conn->query($sql);
        }
   	?>

    <a href="BackToHomepage.php">Back to Homepage</a><br>
    <a href="AddUser.php" >Add User</a><br>
    <p>
        <form method="post" action="ViewAllUsers.php">
            <label>Search:
                <input name="search" type="text" value="" size="15"  maxlength="255">
            </label>
            <label>in
                <select name="in">
                    <option>ID</option>
                    <option value ="type">Type</option>
                    <option>Name</option>
                    <option>Major</option>
                    <option>Minor</option>
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

        if(mysqli_num_rows($userList)==0)
            echo "No results found.";
        else
        {
            echo "<form method='post' action='ViewUserInfo.php'>";
            echo "<p><table><tr>
                <th>User ID</th>
                <th>User Type</th>
                <th>Name</th>
                <th>More Info</th>
                </tr>";
            while($row = mysqli_fetch_array($userList))
            {
                echo "<tr><td>" . $row["ID"] . "</td>
                        <td>" . $row["type"] . "</td>
                        <td>" . $row["first_Name"] . " " . $row["last_Name"] . "</td>
                        <td><button name='ID' type='submit' value='"
                        . $row["ID"] . "'> View </button>
                        </td></tr>";
            }
            echo "</table></p></form>";
        }
    ?>

</body>
</html>