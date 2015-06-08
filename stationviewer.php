<?php
include "infostash.php";
session_start();
if ($_SESSION['loginStatus'] != 1) {
	//user is not actually logged in.  destroy session and send them back to homepage.
	unset($_SESSION["loginStatus"]); 
	$_SESSION = array();
	session_destroy;
	header("Location: {$redirect}/landing.php");
	die();
}
if ($_SESSION['loginStatus'] == 1) {
	$sessionUser = $_SESSION['user'];
}
if (isset($_GET['station_id'])) {
	$station_id = $_GET['station_id'];
}
if (isset($_GET['station_name'])) {
	$station_name = $_GET['station_name'];
}
	//get user id from database
if (isset($sessionUser)) {
		//prepare statement
	if (!($getUID = $mysqli->prepare("SELECT u_id as owner FROM user_ WHERE u_name=?"))) {
		echo "Prepare failed on getUID";
	}
		//bind parameters
	if (!($getUID->bind_param("s", $sessionUser))) {
		echo "Binding failed on getUID";
	}
		//execute
	if (!($getUID->execute())) {
		echo "Error, logged in but user not found.";		
	}
	else {
		$uidResult = $getUID->get_result();
		while ($row = $uidResult->fetch_assoc()) {
			$owner_id = $row['owner'];
		}
	}
	$getUID->close();	
}
	//query to get list of stations
if (isset($owner_id)) {
		//prepare insert statement
	if (!($getStations = $mysqli->prepare("SELECT * FROM station WHERE `owner_id`=?"))) {
		echo "Prepare failed on getStations";
	}
		//bind parameters
	if (!($getStations->bind_param("i", $owner_id))) {
		echo "Binding failed on getStations";
	}
		//execute
	if (!($getStations->execute())) {
		echo "Error, getStations did not execute";		
	}
	else {
		$stationResult = $getStations->get_result();
	}
}
	//query to get list of items in station
if (isset($station_id)) {
		//prepare insert statement
	if (!($getStationItems = $mysqli->prepare("SELECT * FROM item WHERE item_id IN (SELECT item_id FROM item_block WHERE block_id IN (SELECT block_id FROM block_station WHERE station_id=?))"))) {
		echo "Prepare failed on getStationItems";
	}
		//bind parameters
	if (!($getStationItems->bind_param("i", intval($station_id)))) {
		echo "Binding failed on getStationItems";
	}
		//execute
	if (!($getStationItems->execute())) {
		echo "Error, getStationItems did not execute";		
	}
	else {
		$stationItemResult = $getStationItems->get_result();
	}
	$getStationItems->close();	
}
?>
<!doctype html>
<head>
	<script type="text/javascript" src="scripts.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="titlebar">
	<a href="landing.php">auxjockey<a>
</div>

<div class="sidebar">
	<ul>
		<?php
		if (!empty($sessionUser)) {
			echo "
			<li id='userMsg'>You're logged in as <b>" . $sessionUser . "</b>.</li>";
		}
		?>
		<li><a href="userhome.php">Homepage</a></li>
		<li><a href="logout.php">Log Out</a></li><br>
	</ul>
		<h3 style="color:#D0F5EE; text-align: center">View Your Stations:</h3>
	<ul>
		<?php
			//echo a list entry for each station, linking to stationviewer.php with GET
			while ($row = $stationResult->fetch_assoc()) {
			echo "<li>" . "<a href='stationviewer.php?station_id=" . strval($row['station_id']) . "&station_name=" . htmlspecialchars($row['name']) ."'>" . $row['name'] . "</a>" . "</li>";
			}
		?>	
	</ul>
</div>
<a onclick="showHelp()">Need Help?</a>
<div class="viewport"><br>
	<h1><?php echo $station_name; ?></h1>
	<?php
		//show all youtube videos from blocks in station
	while ($row = $stationItemResult->fetch_assoc()) {
		echo "
		<br>
		<table>
		<tr><td>";
		echo $row['title'];
		echo "</td></tr>
		<tr><td>";
		echo $row['desc'];
		echo "</td></tr>
		<tr><th colspan=2>";
		echo '<iframe width="420" height="315" src="https://www.youtube.com/embed/' . $row['yid'] . '" frameborder="0" allowfullscreen></iframe>';
		echo "</th></tr>
		</table>
		";
	}	
	?>
</div>