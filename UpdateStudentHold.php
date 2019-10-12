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
    <title>Update Student Hold</title>
</head>

<body>
    <h1>Update Student Hold</h1>
    <a href="BackToHomepage.php" >Back to Homepage</a><br>
    <a href='ViewUserInfo.php'>View User Info</a><br>

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

        $StudentID = isset($_POST['ID']) ? $_POST['ID'] : 0;
        if($StudentID != 0)
            $_SESSION["StudentID"] = $_POST["ID"];
        else
            $StudentID = $_SESSION["StudentID"];

        $sql = "SELECT first_Name, last_Name FROM student WHERE ID = '" . $StudentID . "'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        echo "<h2>" . $row["first_Name"] . " " . $row["last_Name"] . "'s Holds</h2>";
    ?>

    
    <form method="post" action="AddRemoveStudentHold.php">
    <p><table>
      <tr>
        <th>Hold Name</th>
        <th>Action</th>
      </tr>
        <?php 
        $counter = 1;
        $hold_Names = array("","Financial", "Academic", "Administrative");
      
        while($counter <= 3)
        {     
              $sql = "SELECT * FROM studenthold WHERE student_ID = '" .  $_SESSION["StudentID"] . "' AND hold_Type = '" .  $counter . "'";
              $holds = $conn->query($sql);

              if($row = mysqli_fetch_assoc($holds))
              {
                  echo "<tr><td>" . $hold_Names[$counter] . "</td>
                      <td><button name='ID' type='submit' value='"
                        . $row["studenthold_ID"] . "'> Remove Hold </button>
                        </td></tr>";

                        $row = mysqli_fetch_assoc($holds);

              }
              else
              {     
                    echo "<tr><td>" . $hold_Names[$counter] . "</td>
                    <td><button name='ID2' type='submit' value='"
                      . $counter . "'> Add Hold </button>
                      </td></tr>";
              }

              $counter++;
          
        } ?>
    </table></p>
    </form>

</body>
</html>