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
    <title>Update User Info</title>
</head>

<body>
    <h1>Update User Info</h1>
    <a href="BackToHomepage.php">Back to Homepage</a><br>
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

        $sql = "SELECT * FROM user WHERE ID = '" . $_POST["ID"] . "'";
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

        if($type == "Student")
        {
            $sql2 = "SELECT * FROM student WHERE ID = '" . $_POST["ID"] . "'";
            $result2 = $conn->query($sql2);
            $row2 = $result2->fetch_assoc();

            $studentYear = $row2["year"];
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

            $sql = "SELECT * FROM major";
            $majorList = $conn->query($sql);

            $sql = "SELECT * FROM minor";
            $minorList = $conn->query($sql);
            
        }
        else if($type == "Faculty")
        {
            $sql2 = "SELECT * FROM faculty WHERE ID = '" . $_POST["ID"] . "'";
            $result2 = $conn->query($sql2);
            $row2 = $result2->fetch_assoc();

            $employStatus = $row2["employment_Status"];
            $department = $row2["department_Name"];
            $office = $row2["office_Hours"];

            $sql = "SELECT department_Name FROM department WHERE hidden = 0";
            $departmentTable = $conn->query($sql);
        }
    ?>

    <form method="post" action="UpdateUserInfoPost.php">
        <p>
            <input type ="hidden" name ="ID" value="<?php echo $ID ?>">
            <label>
                First Name:
                <input name="fname" type="text" size="25" maxlength="255" required value="<?php echo $fname ?>">
            </label>
        </p>
        <p>
            <label>
                Middle Initial:
                <input name="mname" type="text" size="1" maxlength="1" value="<?php echo $mname ?>">
            </label>
        </p>
        <p>
            <label>
                Last Name:
                <input name="lname" type="text" size="25" maxlength="255" required value="<?php echo $lname ?>">
            </label>
        </p>
         <p>
            <label>
                Phone Number:
                <input name="phone" type="text" size="25" maxlength="10" value="<?php echo $phone ?>">
            </label>
        </p>
        <p>
            <label>
                Date of Birth:
                <input name="dob" type="date" size="10" value="<?php echo $dob ?>">
            </label>
        </p>
        
        <?php
            if($type == "Student")
            {
                echo "<p><label>Student Year <select name='studentYear'>";
                    if($studentYear == "Freshman")
                        {echo "<option selected>Freshman</option><option>Sophomore</option><option>Junior</option><option>Senior</option><option>Post-Grad</option>";}
                    else if($studentYear == "Sophomore")
                        {echo "<option>Freshman</option><option selected>Sophomore</option><option>Junior</option><option>Senior</option><option>Post-Grad</option>";}
                    else if($studentYear == "Junior")
                        {echo "<option>Freshman</option><option>Sophomore</option><option selected>Junior</option><option>Senior</option><option>Post-Grad</option>";}
                    else if($studentYear == "Senior")
                        {echo "<option>Freshman</option><option>Sophomore</option><option>Junior</option><option selected>Senior</option><option>Post-Grad</option>";}
                    else if($studentYear == "Post-Grad")
                        {echo "<option>Freshman</option><option>Sophomore</option><option>Junior</option><option>Senior</option><option selected>Post-Grad</option>";}
                    else echo "<option selected></option>";
                echo "</select></label></p>";
                echo "<p><label>Major:<select name='major'>";
                        while($row = mysqli_fetch_array($majorList))
                        {
                            if($row["major_Title"] == $majorTitle)
                                echo "<option selected value = '";
                            else
                                echo "<option value = '";
                            echo $row["major_ID"] . "'>" . $row["major_Title"] . "</option>";
                        }
                    echo "</select></label></p>";
                echo "<p><label>Minor:<select name='minor'>";
                        echo "<option value = '0'>None</option>";
                        while($row = mysqli_fetch_array($minorList))
                        {
                            if($row["minor_Title"] == $minorTitle)
                                echo "<option selected value = '";
                            else
                                echo "<option value = '";
                            echo $row["minor_ID"] . "'>" . $row["minor_Title"] . "</option>";
                        }
                    echo "</select></label></p>";        
                    echo "<p><label>Enrollment Type <select name='enrollType'>";
                                    if($enrollType == "Undergraduate")
                                    {echo "<option selected>Undergraduate</option><option>Graduate</option>";}
                                    else if($enrollType == "Graduate")
                                    {echo "<option>Undergraduate</option><option selected>Graduate</option>";}
                                    else echo "<option selected></option>";
                            echo "</select></label></p>
                    <p><label>Enrollment Status <select name='enrollStatus'>";
                                    if($enrollStatus == "Part-Time")
                                    {echo "<option selected>Part-Time</option><option>Full-Time</option>";}
                                    else if($enrollStatus == "Full-Time")
                                    {echo "<option>Part-Time</option><option selected>Full-Time</option>";}
                                    else echo "<option selected></option>";
                            echo "</select></label></p>";
            }
            else if($type == "Faculty")
            {
                echo "<p><label>Employment Status <select name='employStatus'>";
                            if($employStatus == "Part-Time")
                            {echo "<option selected>Part-Time</option><option>Full-Time</option>";}
                            else if($employStatus == "Full-Time")
                            {echo "<option>Part-Time</option><option selected>Full-Time</option>";}
                            else echo "<option selected></option>";     
                    echo "</select></label></p>
            <p><label>Department:<select name='department'>";
                        while($row = mysqli_fetch_array($departmentTable))
                        {
                            if($row["department_Name"] == $department)
                                echo "<option selected>";
                            else
                                echo "<option>";
                            echo $row["department_Name"] . "</option>";
                        }
                    echo "</select></label></p>
            <p><label>Office Hours (Day/Time)
                    <input name = 'office' type='text' size='25' maxlength='25' value='" . $office . "'>
                </label></p>";
            }
        ?>

        <p>
            <input type="submit" value="Submit">
            <input type="reset" value="Reset">
        </p> 
    </form>

</body>
</html>