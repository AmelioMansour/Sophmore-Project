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

	/* Connect to MySQL and select the database. */
	$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

	if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

	$database = mysqli_select_db($connection, DB_DATABASE);

	/* If input fields are populated, add a row to the ACCOUNTS table. */
	$account_username = htmlentities($_POST['USERNAME']);
	$account_password = htmlentities($_POST['PASSWORD']);

	if (strlen($account_username) && strlen($account_password)) {
		$accType = login($connection, $account_username, $account_password);
		if ($accType == 0) {
			echo "login 0";
		} else {
			if ($accType == 1) {
				$fetch = mysqli_fetch_assoc(mysqli_query(
					$connection,
					"SELECT * FROM PARENTS WHERE USERNAME='$account_username'"
				));
				$_SESSION["employee"] = "false";
			} else if ($accType == 2) {
				$fetch = mysqli_fetch_assoc(mysqli_query(
					$connection,
					"SELECT * FROM EMPLOYEES WHERE USERNAME='$account_username'"
				));
				$_SESSION["employee"] = "true";
			}
			$_SESSION["ID"] = $fetch["ID"];
			$_SESSION["name"] = $fetch["NAME"];
			$_SESSION["username"] = $fetch["USERNAME"];
			header("Location: /views/index.php");
		}
		/*echo "Login succesful<br>";
		$_SESSION["username"] = $account_name;
	    $fetch = mysqli_fetch_assoc(mysqli_query($connection, 
			"SELECT * FROM ACCOUNTS WHERE NAME='$account_name'"));
	    $_SESSION["ID"] = $fetch["ID"];
	    $_SESSION["username"] = $fetch["USERNAME"];
	    #echo print_r($_SESSION);		
	} else {
	     echo "Incorrect Username or Password";
	}*/
	}

	?>
	<div id="loginbox" <?php echo $loginStyle; ?>>
		<h2>Welcome to SunnyTime</h2>
		<h3>&nbsp Login below</h3>
		<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
			<hr>
			<h4>&nbspUsername</h4>
			<input type="text" name="USERNAME" />
			<h4>&nbspPassword</h4>
			<input type="password" name="PASSWORD" />
			<br>
			<hr>
			<input type="submit" name="submit" id="submit" class="buttons" value="Login" style="margin-bottom:10px">
			<br>
			<a href="/views/create-account.php"> Click here to create account </a>
		</form>
	</div>


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

<?php

/* Return 1 if account exists in PARENTS table, 2 if in EMPLOYEES, 0 otherwise */
function login($connection, $username, $password)
{
	$storedPass = mysqli_fetch_assoc(mysqli_query(
		$connection,
		"SELECT PASSWORD FROM PARENTS WHERE USERNAME='$username'"
	));
	if ($storedPass["PASSWORD"] == $password && strlen($password)) return 1;
	$storedPass = mysqli_fetch_assoc(mysqli_query(
		$connection,
		"SELECT PASSWORD FROM EMPLOYEES WHERE USERNAME='$username'"
	));
	if ($storedPass["PASSWORD"] == $password && strlen($password)) return 2;
	return 0;
}
?>
