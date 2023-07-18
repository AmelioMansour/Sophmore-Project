<?php
include"../../../inc/dbinfo.inc";
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Child Info</title>
    <link rel="stylesheet" href="/public/css/stylesheet1.css">
</head>

<body>
    <?php
    /* connect to database */
    $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

    if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();
  
    $database = mysqli_select_db($connection, DB_DATABASE);

    VerifyChildrenTable($connection, DB_DATABASE);

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

    #if($_POST["submit"]=="Add Child") {
		#	AddChild($connection, strtolower($_POST["name"]));
		#	createFile($_POST["name"], $_POST["age"], $_POST["conNum"]);
    #}

    ?>
   <h1 id="cover">Child Info</h1>
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
                <li><a href="../views/index.php">Home</a></li>
                <li><a href="../views/html/about.php">About</a></li>
                <li><a href="../views/html/schedule.php">Schedule</a></li>
                <li><a href="../views/html/bio.php">Bio</a></li>
                <li><a href="../views/html/parents.php">Accounts</a></li>
            </ul>
        </nav>
    </div>
	
    <h2>Fill in Child Info</h2>
    
    
	<form action="<?php echo $_SERVER['SCRIPT_NAME']?>" method="POST">
        
		Name: <input type="text" name="name">
  
		<br><br>

		Age: <input type="text" name="age">
    
		<br><br>

		Contact Number: <input type="text" name="conNum">
    
		<br><br>
		<input type="submit" name="submit" id="create" class="buttons" value="Add Child">
	</form>

	<?php
		if($_POST["submit"]=="Add Child") {
      AddChild($connection, $_POST["name"]);
      createFile($_POST["name"], $_POST["age"], $_POST["conNum"]);
    }
	?>

	<?php
		$result = mysqli_query($connection, "SELECT * FROM CHILDREN WHERE PUNAME='".$_SESSION["username"]."';");
		echo "<div id='printbioinfo'>";
		while($query_data = mysqli_fetch_row($result)) {
			$file = fopen("../../contacts/".$_SESSION["username"].strtolower($query_data[1]). ".txt", "r");
			echo "<p>".rtrim(fgets($file))."<br>";
			echo "Age: ".rtrim(fgets($file))."<br>";
			echo "Contact Number: ".rtrim(fgets($file))."</p><br>";
			fclose($file);
		}
		echo "</div>";
	?>

</body>
<footer>
    <p>&copy;Sunny Time</p>
</footer>

</html>
<?php
/*make sure the table exists*/
function VerifyChildrenTable($connection, $dbName) {
	if(!TableExists("CHILDREN", $connection, $dbName))
	{
		$query = "CREATE TABLE CHILDREN (
		 	ID int(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		 	NAME VARCHAR(45) NOT NULL,
		 	PUNAME VARCHAR(90) NOT NULL
		);";

     if(!mysqli_query($connection, $query)) echo("<p>Error creating CHILDREN table.</p>");
  }
}
  function TableExists($tableName, $connection, $dbName) {
    $t = mysqli_real_escape_string($connection, $tableName);
    $d = mysqli_real_escape_string($connection, $dbName);
    $checktable = mysqli_query($connection,
        "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");
  
    if(mysqli_num_rows($checktable) > 0) return true;
  
    return false;
  }

/*Check if child exists*/

function childExists($connection, $name) {
		if (mysqli_num_rows(mysqli_query($connection, "SELECT NAME FROM CHILDREN WHERE NAME='$name' AND PUNAME='".$_SESSION["username"]."'")) > 0)
		return true;
}




/* Add child to table*/

function AddChild($connection, $name) {
	if (childExists($connection, $name)) {
		echo "Updating ".$name;
		return false;
	} else {
		echo "Adding ".$name;
	}

	$n = mysqli_real_escape_string($connection, $name);
	$u = mysqli_real_escape_string($connection, $_SESSION["username"] );
	

	$query = "INSERT INTO CHILDREN (NAME, PUNAME) VALUE ('$n', '$u')";
	if(!mysqli_query($connection, $query)) echo "<p>Error adding child</p>";
	return true;
}

/*create file with other info*/

function createFile($name, $Age, $conNum){
    #echo "creating File";
    $myFile = fopen("../../contacts/".$_SESSION["username"].strtolower($name). ".txt", "w") or die("Unable to open file");
    fwrite($myFile, $name."\n");
    $txt = $Age . "\n";
    fwrite($myFile, $txt);
    $txt = $conNum . "\n";
    fwrite($myFile, $txt);
    fclose($myFile);

}









?>
