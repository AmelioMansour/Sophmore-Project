<?php session_start(); ?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="stylesheet" href="/public/css/stylesheet1.css">
</head>
<header>
    <h1 id="cover">Sunny Time - About</h1>
</header>

<body>
    <?php

    $button_choice = $_POST['signout'];
    if ($button_choice == 'signout') {
        $_SESSION["ID"] = null;
        $_SESSION["username"] = null;
        header("Location:/views/index.php");
    }

    $welcomeStyle = "style='display:none'";
    if (isset($_SESSION["ID"])) {
        $welcomeStyle = "";
    }

    $empStyle = "style=display:none";
    $parStyle = "";
    if ($_SESSION["employee"] == "true") {
        $empStyle = "";
        $parStyle = "style=display:none";
    }

    ?>

    <form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
        <button id="Signout" name="signout" value="signout" <?php echo $welcomeStyle; ?> method="POST" href="/views/index.php">Sign Out</button>
    </form>
    <div class="left" <?php echo $parStyle ?>>
        <nav>
            <h2> Welcome, <?php echo $_SESSION["name"]; ?></h2>
            <ul class="nav">
                <li><a href="/views/index.php">Home</a></li>
                <li><a href="/views/html/about.php">About</a></li>
                <li><a href="/views/html/schedule.php">Schedule</a></li>
                <li><a href="/views/html/childinfo.php">Child Information</a></li>
                <li><a href="/views/html/ourfamily.php">Our Family</a></li>
            </ul>
        </nav>
    </div>
    <div class="left" <?php echo $empStyle; ?>>
        <nav>
            <h2> Welcome, <?php echo $_SESSION["name"]; ?></h2>
            <ul class="nav">
                <li><a href="/views/index.php">Home</a></li>
                <li><a href="/views/html/about.php">About</a></li>
                <li><a href="/views/html/schedule.php">Schedule</a></li>
                <li><a href="/views/html/bio.php">Bio</a></li>
                <li><a href="/views/html/parents.php">Accounts</a></li>
            </ul>
        </nav>
    </div>


    <h2>About Us!</h2>
    <div id="aboutdiv">
        
        <p id="aboutp">Sunny Time is a daycare management system that helps 
            aid the day to day operations of a daycare! </p>
        <!-- <img id="aboutlogo" src="../../public/img/istockphoto-1142670098-612x612.jpg" alt="about logo"> -->
    </div>
</body>

<footer>
    <p>&copy;Sunny Time</p>
</footer>

</html>