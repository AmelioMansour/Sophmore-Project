<?php
include("../../../inc/dbinfo.inc");
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Family</title>
    <link rel="stylesheet" href="/public/css/stylesheet1.css">
</head>

<body>
	<?php

		$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

		if(mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

		$database = mysqli_select_db($connection, DB_DATABASE);

		$button_choice = $_POST['signout'];
		if ($button_choice == 'signout') {
			$_SESSION["ID"] = null;
			$_SESSION["username"] = null;
			header("Location:/views/index.php");
		}

		$view = null;
		$viewStyle = "";
		$parStyle = "style=display:none";
		$result = mysqli_query($connection, "SELECT * FROM PARENTS");
		while($query_data = mysqli_fetch_row($result)) {
			if(array_key_exists($query_data[2], $_POST)){
				$view = $query_data[2];
			}
		}
		
		if($view != null) {
			$viewStyle = "style=display:none";
			$parStyle = "";
		}

		if(isset($_POST["back"])){
			$view = null;
			$viewStyle = "";
			$parStyle = "style=display:none";
		}

	?>
	<h1 id="cover">Our Family</h1>
	<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
		<button id="Signout" name="signout" value="signout" <?php echo $welcomeStyle; ?> method="POST" href="/views/index.php">Sign Out</button>
	</form>
	<div class="left">
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
	<h2>Accounts</h2>

	<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST" <?php echo $viewStyle?>>
		<?php
			$result = mysqli_query($connection, "SELECT * FROM PARENTS");
			while($query_data = mysqli_fetch_row($result)) {
				echo "<br>".$query_data[1]." - ".$query_data[2]." ";
				echo "<input type='submit' class='buttons' name='".$query_data[2]."' value='View Account'><br>";
			}
		?>
	</form>
  <div <?php echo $parStyle; ?>>
		<?php
			$result = mysqli_query($connection, "SELECT * FROM CHILDREN WHERE PUNAME='".$view."';");
			echo "<div id='printbioinfo'>";
			echo "<h2>". $view . "</h2>";
    	while($query_data = mysqli_fetch_row($result)) {
      	$file = $myFile = fopen("../../contacts/".$view.strtolower($query_data[1]). ".txt", "r");
      	echo "<p>".rtrim(fgets($file))."<br>";
      	echo "Age: ".rtrim(fgets($file))."<br>";
      	echo "Contact Number: ".rtrim(fgets($file))."</p><br>";
      	fclose($file);
			}
    	echo "</div>";
  	?>
	<form action="<?PHP echo $_SERVER['SCRIPT_NAME']?>" method="POST" <?php $bioStyle?>>
		<input type="submit" class="buttons" name="back" value="Back">
	</form>
	</div>
	
	<!-- Clean up. -->
	<?php
  	mysqli_close($connection);
	?>
</body>

<footer>
	<p>&copy;Sunny Time</p>
</footer>

</html> 
