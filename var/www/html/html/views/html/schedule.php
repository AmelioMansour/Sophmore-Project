<?php 
include "Calendar.php";
include "../../../inc/dbinfo.inc";
session_start(); 
?>
<!DOCTYPE html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Schedule</title>
	<link href="../../public/css/style.css" rel="stylesheet" type="text/css">
	<link href="../../public/css/calendar.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="/public/css/stylesheet1.css">
    <!-- <style>
        table {
            border-collapse: collapse;
            background: white;
            color: black;
            table-layout: fixed;
            /* width: 1000px;
                height: 1000px; */
            margin-left: 30%;
						/*margin-top: -650px;*/


        }

        th,
        td {
            font-weight: bold;

        }

        td {
            border: 2px;
            border-style: solid;
        }

        td,
        td>div,
        p {
            /* border: 2px;
            border-style: solid; */
            margin: 0px;
            padding: 10px;
            height: 80px;
            width: 80px;
            overflow: hidden;

            word-wrap: break-word overflow: hidden;
        }
    </style>-->
</head>
<header>
    <h1 id="cover">Sunny Time - Schedule</h1>
</header>

<body>
    <?php

		/* Connect to MySQL and select the database. */
		$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

		if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

		$database = mysqli_select_db($connection, DB_DATABASE);

		VerifyScheduleTable($connection, DB_DATABASE);

		$calendar = new Calendar(date("Y-m-d"));

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
	$parent = $_SESSION["username"];
    
    if ($_SESSION["employee"] == "true") {
        $empStyle = "";
			$parStyle = "style=display:none";
			$parent = $_POST["parentName"];
		}

		$result = mysqli_query($connection, "SELECT * FROM SCHEDULE");
		while($query_data = mysqli_fetch_row($result)) {
			if ($_SESSION["employee"] == "false" && preg_match("/^".$_SESSION["username"]."/",$query_data[0])){
				$calendar->add_event($query_data[1]."\n".$query_data[3]."-".$query_data[4], $query_data[2]);	
			} else if ($_SESSION["employee"] == "true") {
				$calendar->add_event($query_data[0]."-".$query_data[1]."\n".$query_data[3]."-".$query_data[4], $query_data[2]);
			}
		}
		mysqli_free_result($result);

    if ((strlen($_POST["childName"]) || strlen($_POST["childNameEmp"])) && isset($_POST["reserve"])) {
			$child = strlen($_POST["childName"]) ? $_POST["childName"] : $_POST["childNameEmp"];
			$arrive = $_POST["arrive"];
			$depart = $_POST["depart"];
			$date = $_POST["date"];
			AddAppt($connection, $parent, $child, $arrive, $depart, $date);
			if ($_SESSION["employee"] == "false")
				$calendar->add_event($child."\n".$arrive."-".$depart, $date);
			else
				$calendar->add_event($parent."-".$child."\n".$arrive."-".$depart, $date);
		}

    ?>
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

    <form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
        <button id="Signout" name="signout" value="signout" <?php echo $welcomeStyle; ?> method="POST" href="/views/index.php">Sign Out</button>
    </form>
    <div>
        <h2>schedule</h2>
        
    </div>

    <!-- schedule bar -->
    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
        <ul class="reservebar">
            <h2>Reserve Time</h2>
				<li <?php echo $empStyle; ?>>
                    Parent Username:
				    <input type="text" name="parentName">
                </li>
                <li>
				    Child Name: 
					<select name="childName" <?php echo $parStyle; ?>>
					<?php
					    if ($_SESSION["employee"] == "false"){
						    $child_query = "SELECT * FROM CHILDREN WHERE PUNAME='".$_SESSION['username']."';";
						    $child_data = mysqli_query($connection, $child_query);
						    while ($kid = mysqli_fetch_row($child_data)) {
						        echo "<option value='".$kid[1]."'>";
						        echo	$kid[1];
						        echo "</option>";
                            }
					    }
				    ?>
				    </select>
				    <input type="text" name="childNameEmp" <?php echo $empStyle; ?>>
                </li>
                <li>
                    Arrival:
                        <input type="time" id="arrive" name="arrive" required class="reservetimeinputs">
                </li>

                <li>
                    Departure Time:
                    <input type="time" id="depart" name="depart" required class="reservetimeinputs">
                </li>
                <li>
                    Appointment Date:
                    <input type="date" id="date" name="date" required class="reservetimeinputs">
                </li>
                <input type="submit" class="buttons" name="reserve" value="Reserve Time">
            </ul>
        </form>
		
		<div class="content home">
			<?=$calendar?>
		</div>

</body>

<footer>
    <p>&copy;Sunny Time</p>
</footer>

</html>

<?php

function VerifyScheduleTable($connection, $dbName) {
	if(!TableExists("SCHEDULE", $connection, $dbName)){
		$query = "CREATE TABLE SCHEDULE (
			PARENT VARCHAR(45),
			NAME VARCHAR(45),
			APPTDATE DATE NOT NULL,
			ARRIVE TIME NOT NULL,
			DEPART TIME NOT NULL
			);";
		if(!mysqli_query($connection, $query)) echo "Error creating SCHEDULE table.";
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

function AddAppt($connection, $parent, $name, $arrive, $depart, $date) {
	$p = mysqli_real_escape_string($connection, $parent);
	$n = mysqli_real_escape_string($connection, $name);
	$a = mysqli_real_escape_string($connection, $arrive);
	$de = mysqli_real_escape_string($connection, $depart);
	$da = mysqli_real_escape_string($connection, $date);
	
	$query = "INSERT INTO SCHEDULE (PARENT, NAME, APPTDATE, ARRIVE, DEPART) VALUE ('$p','$n','$da','$a','$de')";
	if(!mysqli_query($connection, $query)) echo	"Error adding appointment information";
}
?>

