<?php

    session_start();
    $time = time();
    $_SESSION["locked"] = isset($_SESSION["locked"]) ? $_SESSION["locked"] : FALSE;
    $_SESSION["attempts"] = isset($_SESSION["attempts"]) ? $_SESSION["attempts"] : 0;
    $_SESSION["timeout"] = isset($_SESSION["timeout"]) ? $_SESSION["timeout"] : 0;
    $_SESSION["Error"] = isset($_SESSION["Error"]) ? $_SESSION["Error"] : "";

    if($_SESSION["locked"] == TRUE)
    {
        if($time > $_SESSION["timeout"])
        {
            $_SESSION["attempts"] = 0;
            $_SESSION["locked"] = FALSE;
        }
    }


?>

<link rel="stylesheet" href="stylesheet.css">
<!doctype html>
<html>

<head>
    <ul>
    <!-- <li><img src = "UpdatedLogo.png" style="width:136;height:131;padding:10px;"></li> -->
    <!-- <li><a class="active" href="#home">Home</a></li> -->
    <li><div class="heading" style="padding-bottom:60px; padding-left: 10px;">The University of Western Long Island Registration System</div></li>
    </ul>
    <img src = "Logo1.png" style="width:250px;height:183px;padding-top:15px;">
    <title>Login Page</title>
</head>

<body>
    <h1>Welcome!</h1>

    <form method="post" action="LoginPost.php">
        <p>
            <label>
                E-mail Address:
                <input name="email" type="email" value="@westernlongisland.edu"size="40"  maxlength="255" required>
            </label>
        </p>
        <p>
            <label>
                Password:
                <input name="password" type="password" size="25" required>
            </label>
        </p>

        <p>
            <?php
                if(($_SESSION["attempts"] != "") && ($_SESSION["attempts"] > 3))
                {
                    echo "Too many failed login attempts, try again later.";
                    if($_SESSION["locked"] == FALSE)
                    {
                        $_SESSION["locked"] = TRUE;
                        $_SESSION["timeout"] = time() + 300; // 5 minutes
                    } 
                }
                else
                    echo "<input type='submit' value='Submit' > <input type='reset' value='Clear'>";
            ?>
        </p>

        <p>
            <a href="CentralHomepage.php">Back to Central Homepage</a><br>
        </p> 
    </form>
    <p>
        <?php 
        if($_SESSION["Error"] != "") 
            echo $_SESSION["Error"];
        $_SESSION["Error"] = "";

        session_unset();
        session_destroy();
        session_start();
        ?>
    </p>
</body>
</html>