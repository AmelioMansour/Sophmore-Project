<?php include "../../inc/dbinfo.inc";
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sunny Time</title>
  <link rel="stylesheet" href="/public/css/stylesheet1.css">
</head>
<header>
  <h1 id="cover">Sunny Time</h1>
</header>

<body>

  <?php

  /*Redirect to login if session variables are not set*/
  if ($_SESSION["ID"] == null) header("Location: login.php");

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  $button_choice = $_POST['signout'];
  if ($button_choice == 'signout') {
    $_SESSION["ID"] = null;
    $_SESSION["username"] = null;
    header("Location: login.php");
  }

  $empStyle = "style=display:none";
  $parStyle = "";
  if ($_SESSION["employee"] == "true") {
    $empStyle = "";
    $parStyle = "style=display:none";
  }

  ?>
  <div class="left" <?php echo $parStyle ?>>
    <nav>
      <h2> Welcome, <?php echo $_SESSION["name"]; ?></h2>
      <ul class="nav">
        <li><a href="../views/index.php">Home</a></li>
        <li><a href="../views/html/about.php">About</a></li>
        <li><a href="../views/html/schedule.php">Schedule</a></li>
        <li><a href="../views/html/childinfo.php">Child Information</a></li>
        <li><a href="../views/html/ourfamily.php">Our Family</a></li>
      </ul>
    </nav>
  </div>
  <div class="left" <?php echo $empStyle; ?>>
    <nav>
      <h2> Welcome, <?php echo $_SESSION["name"]; ?></h2>
      <ul class="nav">
        <li><a href="../views/index.php">Home</a></li>
        <li><a href="../views/html/about.php">About</a></li>
        <li><a href="../views/html/schedule.php">Schedule</a></li>
        <li><a href="../views/html/bio.php">Bio</a></li>
        <li><a href="../views/html/parents.php">Accounts</a></li>
      </ul>
    </nav>
  </div>
  <form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
    <button id="Signout" name="signout" value="signout" method="POST">Sign Out</button>
  </form>

  <img id="logo" src="../public/img/istockphoto-1142670098-612x612.jpg" alt="Logo">
  <?php

  mysqli_free_result($result);
  mysqli_close($connection);

  ?>

</body>

<footer>
  <p>&copy;Sunny Time</p>
</footer>

</html>
