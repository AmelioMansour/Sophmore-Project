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

  /* Ensure that the ACCOUNTS table exists. */
  VerifyEmployeeTable($connection, DB_DATABASE);
  VerifyParentTable($connection, DB_DATABASE);

  /* If input fields are populated, add a row to the ACCOUNTS table. */
  $account_name = htmlentities($_POST["NAME"]);
  $account_password = htmlentities($_POST["PASSWORD"]);
  $account_username = htmlentities($_POST["USERNAME"]);
  $button_pressed = $_POST['employeeBox'];

  if (strlen($account_name) && strlen($account_password) && strlen($account_username)) {
		  if($button_pressed=='employee') {
			AddEmployee($connection, $account_name, $account_username, $account_password);
		} else {
			AddParent($connection, $account_name, $account_username, $account_password);
		}
  }
?>

    <div id="loginbox">
        <h3>&nbsp Create Account</h3>
        <form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
            <hr>
            <h4>&nbspName</h4>
	    <input type="text" name="NAME"/>
	    <h4>&nbspUsername</h4>
	    <input type="text" name="USERNAME"/>
            <h4>&nbspPassword</h4>
	    <input type="text" name="PASSWORD"/>
            <br>
            <hr>
            <input type="checkbox" id="employee" name="employeeBox" value="employee">
            <label for="employee"> Employee? <br></label>
            <br>
	    <input type="submit" name="submit" id="create" class="buttons" value="Create Account">
	    <br>
	    <a href=/views/login.php> Click here to login </a>
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

  /* Check whether the table exists and, if not, create it. */
function VerifyEmployeeTable($connection, $dbName) {
	if(!TableExists("EMPLOYEES", $connection, $dbName))
	{
		$query = "CREATE TABLE EMPLOYEES (
		 	ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		 	NAME VARCHAR(45),
		 	USERNAME VARCHAR(45),
		 	PASSWORD VARCHAR(90)
		);";

     if(!mysqli_query($connection, $query)) echo("<p>Error creating EMPLOYEES table.</p>");
  }
}

function VerifyParentTable($connection, $dbName) {
	if (!TableExists("PARENTS", $connection, $dbName))
	{
		$query = "CREATE TABLE PARENTS (
			ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			NAME VARCHAR(45),
			USERNAME VARCHAR(45),
			PASSWORD VARCHAR(90)
		);";

		if(!mysqli_query($connection, $query)) echo ("<p>Error creating PARENTS table.</p>");
	}
}
/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);
  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}

/* Add parent account to PARENTS table*/
function AddParent($connection, $name, $username, $password) {
	if (AccountExists($connection, $username)) {
		echo "Error: username is taken";
		return false;
	}

	$n = mysqli_real_escape_string($connection, $name);
	$u = mysqli_real_escape_string($connection, $username);
	$p = mysqli_real_escape_string($connection, $password);

	$query = "INSERT INTO PARENTS (NAME, USERNAME, PASSWORD) VALUE ('$n', '$u', '$p');";
	if(!mysqli_query($connection, $query)) echo "<p>Error adding account data.</p>";
	return true;
}

/* Add employee account to EMPLOYEES table*/
function AddEmployee($connection, $name, $username, $password) {
	if (AccountExists($connection, $username)) {
		echo "Error: username is taken";
		return false;
	}

	$n = mysqli_real_escape_string($connection, $name);
	$u = mysqli_real_escape_string($connection, $username);
	$p = mysqli_real_escape_string($connection, $password);

	$query = "INSERT INTO EMPLOYEES (NAME, USERNAME, PASSWORD) VALUE ('$n', '$u', '$p')";
	if(!mysqli_query($connection, $query)) echo "<p>Error adding account data</p>";
	return true;
}

/* Verify userame does not exit in PARENTS table or EMPLOYEES table*/
function AccountExists($connection, $username) {
	if (mysqli_num_rows(mysqli_query($connection, 
		"SELECT USERNAME FROM PARENTS WHERE USERNAME='$username'")) > 0)
		return true;
	if (mysqli_num_rows(mysqli_query($connection,
		"SELECT USERNAME FROM EMPLOYEES WHERE USERNAME='$username'")) > 0)
		return true;
	return false;
}

/* Verify username does not exist in EMPLOYEES table*/
function EmployeeExists($connection, $username) {
	return mysqli_num_rows(mysqli_query($connection, 
			"SELECT USERNAME FROM EMPLOYEES WHERE USERNAME='$username'")) > 0;
}
?>
