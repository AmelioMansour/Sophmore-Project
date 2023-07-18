<?php
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
  <a href="/views/index.php" style="text-decoration:none">
    <h1 id="cover">Sunny Time</h1>
  </a>
</header>

<body>

  <?php
  if ($_SESSION["username"] == null)
    header("Location: /views/login.php");
  $button_choice = $_POST['signout'];
  if ($button_choice == 'signout') {
    #$_SESSION["ID"] = 0;
    #$_SESSION["username"] = null;
    header("Location: /views/index.php");
  }
  
  if (!file_exists("../../bios/".$_SESSION["username"]."bio.txt"))
		header("Location: /views/html/editbio.php");
  ?>
  <div class="left">
    <nav>
      <h2> Welcome, <?php echo $_SESSION["name"]; ?></h2>
      <ul class="nav">
        <li><a href="../index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="schedule.php">Schedule</a></li>
        <li><a href="bio.php">Bio</a></li>
        <li><a href="parents.php">Accounts</a></li>
      </ul>
    </nav>
  </div>
  <form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
    <button id="Signout" name="signout" value="signout" method="POST" href="/views/index.php">Sign Out</button>
  </form>
  <div id="printbioinfo">
    <!-- Display photo-->
    <img src=<?php echo "../../images/" . $_SESSION["username"] . ".png" ?> alt="Image not found" id="profilepic">
    <?php
    #Precondition: first line in file is name, second is phone or email or somethin
    $biofile = fopen("../../bios/" . $_SESSION["username"] . "bio.txt", "r");

    #Print name
    echo "<h2>" . fgets($biofile) . "</h2><br><br>";
    $email = fgets($biofile);
    #Print contact
    echo "Contact: " . "<a href = 'mailto:" . $email . ".'>$email</a><br><br>";

    #Print bio
    echo "<p>";
    while (!feof($biofile)) {
      echo fgets($biofile) .
        "<br>";
    }
    echo "</p>";
    ?>
  </div>
  <br>
  <a href="editbio.php" style="text-decorations:none"><input class="buttons" value="Edit" type="submit"></a>
</body>

</html>
