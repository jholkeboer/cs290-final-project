<?php
include "infostash.php";
session_start();
if ($_SESSION['loginStatus'] != 1) {
	//user is not actually logged in.  destroy session and send them back to homepage.
}
if ($_SESSION['loginStatus'] == 1) {
	$sessionUser = $_SESSION['user'];
}
	//get user id from database
if (isset($sessionUser)) {
		//prepare insert statement
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
	$getStations->close();	
}

	//query to get list of blocks
if (isset($owner_id)) {
		//prepare insert statement
	if (!($getBlocks = $mysqli->prepare("SELECT * FROM block WHERE `owner_id`=?"))) {
		echo "Prepare failed on getBlocks";
	}
		//bind parameters
	if (!($getBlocks->bind_param("i", $owner_id))) {
		echo "Binding failed on getBlocks";
	}
		//execute
	if (!($getBlocks->execute())) {
		echo "Error, getBlocks did not execute";		
	}
	else {
		$blockResult = $getBlocks->get_result();
	}
	$getBlocks->close();	
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
			<li id='userMsg'>You're logged in as " . $sessionUser . ".</li>";
		}
		?>
		<li><a href="stations.php">Stations</a></li>
		<li><a href="blocks.php">Blocks</a></li>
		<li><a href="logout.php">Log Out</a></li>
	</ul>
</div>

<div class="viewport"><br>
	<div class="leftCol">
		Your Stations:<br><br>
		<table>
		<thead>
			<tr>
				<td></td>
				<td></td>
			</tr>
		</thead>
		<tbody>
		<?php
		while ($row = $stationResult->fetch_assoc()) {
			echo "<tr>";
			echo "<td>" . "<a href='stationpage.php?station_id=" . $row['station_id'] . "'>" . $row['name'] . "</a>" . "</td>";
			echo "<td>" . $row['desc'] . "</td>";
			echo "</tr>";
		}	
		?>
		</tbody>
		</table>
	</div>
	<div class="rightCol">
		Your Blocks:<br><br>
		<table>
		<thead>
			<tr>
				<td></td>
				<td></td>
			</tr>
		</thead>
		<tbody>
		<?php
		while ($row = $blockResult->fetch_assoc()) {
			echo "<tr>";
			echo "<td>" . "<a href='blockpage.php?block_id=" . $row['block_id'] . "'>" . $row['name'] . "</a>" . "</td>";
			echo "<td>" . $row['desc'] . "</td>";
			echo "</tr>";
		}	
		?>
		</tbody>
		</table>
	</div>
</div>
</body>
