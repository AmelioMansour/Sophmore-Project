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
		$bioStyle = "style=display:none";
		$result = mysqli_query($connection, "SELECT * FROM EMPLOYEES");
		while($query_data = mysqli_fetch_row($result)) {
			if(array_key_exists($query_data[2], $_POST)){
				$view = $query_data[2];
			}
		}
		
		if($view != null) {
			$viewStyle = "style=display:none";
			$bioStyle = "";
		}

		if(isset($_POST["back"])){
			$view = null;
			$viewStyle = "";
			$bioStyle = "style=display:none";
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
				<li><a href="/views/html/childinfo.php">Child Information</a></li>
				<li><a href="/views/html/ourfamily.php">Our Family</a></li>
			</ul>
		</nav>
	</div>
	<h2>Accounts</h2>

	<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST" <?php echo $viewStyle?>>
		<?php
			$result = mysqli_query($connection, "SELECT * FROM EMPLOYEES");
			while($query_data = mysqli_fetch_row($result)) {
				echo "<div id='printbioinfo'>";			
				echo "<p>".$query_data[1]."</p><br>";
				echo "<img src='../../images/".$query_data[2].".png' alt='Image not found' id='profilepic'><br>";
				echo "<input type='submit' class='buttons' name='".$query_data[2]."' value='View Bio'><br>";
				echo "</div>";
			}
		?>
	</form>
  <div <?php echo $bioStyle; ?>>
		<!--Display photo-->
  	<img src=<?php echo "../../images/" . $view . ".png" ?> alt="Image not found" id="profilepic">
  	<?php

			if(file_exists("../../bios/".$view."bio.txt")){
				#Precondition: first line in file is name, second is phone or email or somethin
  			$biofile = fopen("../../bios/" . $view . "bio.txt", "r");
  			echo "<div id=printbioinfo>";
			
				#Print name
  			echo "<h2>" . fgets($biofile) . "</h2><br><br>";
			
				#Print contact
  			$email = fgets($biofile);
  			echo "<label for=email>Personal Email:</label>"."<a href=mailto:".$email.">".$email."</a><br><br>";

  			#Print bio
  			echo"<p>";
  			while (!feof($biofile)) {
    			echo fgets($biofile);
    			echo "<br>";
				}
			} else {
				echo "Sorry, that employee has not completed sign up yet";
			}
  		echo "<br>";
  		echo"</p>";
  		echo "</div>";
  	?>
	<form action="<?PHP echo $_SERVER['SCRIPT_NAME']?>" method="POST" <?php echo $bioStyle; ?>>
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
