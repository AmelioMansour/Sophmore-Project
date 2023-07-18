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
  <a href="/views/index.php" style="text-decoration:none"><h1 id="cover">Sunny Time</h1></a>
</header>
<body>
  <?php
	$exists = file_exists("../../bios/" . $_SESSION["username"] . "bio.txt");
	if(!$exists){
		echo "File does not exist, writing " . $_SESSION["username"] . "<br>";
		$biofile = fopen("../../bios/" . $_SESSION["username"] . "bio.txt", "w");
		fwrite($biofile, "Enter your info here\n");
		fclose($biofile);
	}

	if ($_POST["submit"]=="Submit") {
		echo "submitted";
		$biofile = fopen("../../bios/" . $_SESSION["username"] . "bio.txt", "w");
		$textdata = $_POST["namedata"]."\n".$_POST["emaildata"]."\n".$_POST["textdata"];
		fwrite($biofile, $textdata);	
		fclose($biofile);
		header("Location: bio.php");
	}
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
  <div id="printbioinfo">
	<label>Current Photo:</label>	
	<br>
	<br>
	<img src="<?php echo "../../images/".$_SESSION["username"].".png";?>" id="profilepic">
	<form action="upload.php" method="post" enctype="multipart/form-data">
		<label for="upload file">Select image to upload:</label>
		<input type="file" name="fileToUpload" id="fileToUpload">
		<input type="submit" value="Upload Image" name="submit">
	</form>
  <form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">

	<label for="namedata">Name:</label>
	<textarea name="namedata" id="namedata"><?php 
		$biofile = fopen("../../bios/".$_SESSION["username"]."bio.txt", "r");
		$line = fgets($biofile);
		echo rtrim($line);
	?></textarea>
	<br>
	
	<label for="emaildata">Email:</label>
  <textarea name="emaildata" id="emaildata"><?php
    $line = fgets($biofile);
    echo rtrim($line);
  ?></textarea>

	<br>
	<label for="textdata">Bio:</label>
	<br>	
	<textarea rows="40" cols="150" name="textdata" id="textdata"><?php
		#$biofile = fopen("../../bios/" . $_SESSION["username"] . "bio.txt", "r");
		while (!feof($biofile)) {
			echo fgets($biofile);
		}
	?></textarea>
	<br><br>
	<input class="buttons" name="submit" type="submit" value="Submit">
	</div>
  </form>
</body>
</html>
