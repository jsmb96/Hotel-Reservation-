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
    <title>Add a semester</title>
</head>

<body>
    <h1>Add a semester</h1>
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

        if($_SESSION["type"] != "Admin")
            {header('Location: UserHomepage.php');}

        $sql = "SELECT * FROM semesteryear";
        $semester = $conn->query($sql);
    ?>
        <p>
            <label>
                Current semester list:
                <select>
                    <?php while($row = mysqli_fetch_array($semester))
                    {echo "<option>" . $row["Season"] . " " . $row["Year"] . "</option>";} ?>
                </select>
            </label>
        </p>
    <form method="post" action="AddSemesterPost.php">
        <p>
            <label>
                Season:
                <select name="season">
                    <option>Winter</option>";
                    <option>Spring</option>";
                    <option>Summer</option>";
                    <option>Fall</option>";      
                </select>
            </label>
        </p>
        <p>
            <label>
                Year:
                <input name="year" type="number" size="4"  value ="2018" min="2000" max="3000" required>
            </label>
        </p>
        <p>
            <input type="submit" value="Submit">
            <input type="reset" value="Clear">
        </p> 
    </form>

    <p>
        <?php 
        if($_SESSION["Error"] != "") echo $_SESSION["Error"]; 
        $_SESSION["Error"] = "";
        ?>
    </p>

</body>
</html>