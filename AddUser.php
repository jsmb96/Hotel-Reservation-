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
    <title>Add User</title>
</head>

<body>
    <h1>Add User</h1>
    <a href="BackToHomepage.php">Back to Homepage</a><br>
    <a href="ViewAllUsers.php">View All Users</a><br>

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

        $sql = "SELECT department_Name FROM department WHERE hidden = 0";
        $departmentList = $conn->query($sql);

        $sql = "SELECT * FROM major";
        $majorList = $conn->query($sql);

        $sql = "SELECT * FROM minor";
        $minorList = $conn->query($sql);

    ?>

    <form method="post" action="AddUserPost.php">
        <p>
            <label>
                User Type:
                <select onchange="yesnoCheck(this);" name="userType" required>
                    <option>Admin</option>
                    <option>Student</option>
                    <option>Faculty</option>
                    <option>Research</option>
                </select>
            </label>
        </p>

        <p>
            <label>
                E-mail Address:
                <input name="email" type="email" value="@westernlongisland.edu"size="40"  maxlength="255" required>
            </label>
        </p>
        <p>
            <label>
                Password:
                <input name="password" type="text" size="25" maxlength="255" required>
            </label>
        </p>
        <p>
            <label>
                First Name:
                <input name="fname" type="text" size="25" maxlength="255" required>
            </label>
        </p>
        <p>
            <label>
                Middle Initial:
                <input name="mname" type="text" size="1" maxlength="1">
            </label>
        </p>
        <p>
            <label>
                Last Name:
                <input name="lname" type="text" size="25" maxlength="255" required>
            </label>
        </p>
        <p>
            <label>
                Phone Number:
                <input name="phone" type="text" size="10" maxlength="10" >
            </label>
        </p>
        <p>
            <label>
                Date of Birth:
                <input name="dob" type="date" size="10">
            </label>
        </p>

        <div id="ifStudent" style="display: none;">
            <p>
                <label>
                    Student Year <select name="studentYear">
                        <option>Freshman</option>
                        <option>Sophomore</option>
                        <option>Junior</option>
                        <option>Senior</option>
                        <option>Post-Grad</option>
                    </select>
                </label>
            </p>
            <p>
                <label>
                    Enrollment Type <select name="enrollType">
                        <option>Undergraduate</option>
                        <option>Graduate</option>
                    </select>
                </label>
            </p>
            <p>
                <label>
                    Enrollment Status <select name="enrollStatus">
                        <option>Full-Time</option>
                        <option>Part-Time</option>
                    </select>
                </label>
            </p>
            <p><label>Major:<select name='major'>
                        <?php while($row = mysqli_fetch_array($majorList))
                        {
                            if($row["major_Title"] == $majorTitle)
                                echo "<option selected value = '";
                            else
                                echo "<option value = '";
                            echo $row["major_ID"] . "'>" . $row["major_Title"] . "</option>";
                        } ?>
            </select></label></p>
            <p><label>Minor:<select name='minor'>
                        <?php 
                        echo "<option value = '0'>None</option>";
                        while($row = mysqli_fetch_array($minorList))
                        {
                            if($row["minor_Title"] == $minorTitle)
                                echo "<option selected value = '";
                            else
                                echo "<option value = '";
                            echo $row["minor_ID"] . "'>" . $row["minor_Title"] . "</option>";
                        } ?>
            </select></label></p>   
        </div>

        <div id="ifFaculty" style="display: none;">
            <p>
                <label>
                    Employment Status <select name="employStatus">
                        <option>Full-Time</option>
                        <option>Part-Time</option>
                    </select>
                </label>
            </p>
            <p>
                <label>
                    Department <select name="department">
                    <?php while($row = mysqli_fetch_array($departmentList))
                    {echo "<option>" . $row["department_Name"] . "</option>";} ?>
                </select>
                    </select>
                </label>
            </p>
            <p>
                <label>
                    Office Hours (Day/Time)
                    <input name = "office "type="text" size="25" maxlength="20">
                </label>
            </p>
        </div>

        <script>
            function yesnoCheck(that) {
                if (that.value == "Student") {
                    document.getElementById("ifStudent").style.display = "block";
                } else {
                    document.getElementById("ifStudent").style.display = "none";
                }
                if (that.value == "Faculty") {
                    document.getElementById("ifFaculty").style.display = "block";
                } else {
                    document.getElementById("ifFaculty").style.display = "none";
                }
            }
        </script>

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