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
if (isset($_GET['station_id'])) {
	$station_id = $_GET['station_id'];
}
if (isset($_GET['station_name'])) {
	$station_name = $_GET['station_name'];
}
if (isset($_GET['blockToAdd'])) {
	$blockToAdd = intval($_GET['blockToAdd']);
}
if (isset($_GET['blockToDelete'])) {
	$blockToDelete = intval($_GET['blockToDelete']);
}

	//add block to station
if (isset($blockToAdd) && isset($station_id) && isset($station_name)) {
	//associate block with station
		//prepare statement
	if (!($addBlockStation = $mysqli->prepare("INSERT INTO block_station (station_id,block_id) values (?,?)"))) {
		echo "Prepare failed on addBlockStation";
	}
		//bind parameters
	if (!($addBlockStation->bind_param("ii",intval($station_id),$blockToAdd))) {
		echo "Binding failed on addBlockStation";
	}
		//execute
	if (!($addBlockStation->execute())) {
		echo "Error. addBlockStation did not execute.";		
	}
	$addBlockStation->close();
	header("Location: {$redirect}/stationpage?station_id=".$station_id."&station_name=".$station_name, true);
	die();
}
	//get list of blocks you own
if (isset($owner_id)) {
		//prepare statement
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
	//get list of blocks in station
if (isset($station_id)) {
	if (!($getStationBlocks = $mysqli->prepare("SELECT * FROM block WHERE block_id IN (SELECT block_id FROM block_station WHERE station_id=?)"))) {
		echo "Prepare failed on getStationBlocks";
	}
		//bind parameters
	if (!($getStationBlocks->bind_param("i", $station_id))) {
		echo "Binding failed on getStationBlocks";
	}
		//execute
	if (!($getStationBlocks->execute())) {
		echo "Error, getStationBlocks did not execute";		
	}
	else {
		$stationBlockResult = $getStationBlocks->get_result();
	}
	$getStationBlocks->close();	
}
	//delete block from station
if (isset($station_id) && isset($blockToDelete)) {
	if (!($deleteStationBlocks = $mysqli->prepare("DELETE FROM block_station WHERE block_id=? AND station_id=?"))) {
		echo "Prepare failed on deleteStationBlocks";
	}
		//bind parameters
	if (!($deleteStationBlocks->bind_param("ii", intval($blockToDelete), intval($station_id)))) {
		echo "Binding failed on deleteStationBlocks";
	}
		//execute
	if (!($deleteStationBlocks->execute())) {
		echo "Error, deleteStationBlocks did not execute";		
	}
	$deleteStationBlocks->close();	
	header("Location: {$redirect}/stationpage?station_id=".$station_id."&station_name=".$station_name, true);
}
?>
<!doctype html>
<head>
	<script type="text/javascript" src="scripts.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
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
		<li><a href="logout.php">Log Out</a></li>
	</ul>
</div>
<div class="viewport"><br>

	<div class="leftCol">
		<br><br><u>Blocks in the station <b><?php echo $station_name; ?></b>:</u><br><br>
		<table>
		<thead>
			<tr>
				<td></td>
				<td></td>
			</tr>
		</thead>
		<tbody>
		<?php
		while ($row = $stationBlockResult->fetch_assoc()) {
			if ($row['desc'] == "") {
				$row['desc'] = "[No description.]";
			}
			echo "<tr>";
			echo "<td>" . "<a href='blockpage.php?block_id=" . $row['block_id'] . "&block_name=".$row['name'].".'>" . $row['name'] . "</a>" . "</td>";
			echo "<td style='padding: 10px;'>" . $row['desc'] . "</td>";
			echo "<td style='padding:2px;'>
			<form action='stationpage.php' method='get'>
				<input type='hidden' name='blockToDelete' value='".$row['block_id']."'>
				<input type='hidden' name='station_id' value='".$station_id."'>
				<input type='hidden' name='station_name' value='".$station_name."'>
				<input type='submit' value='Delete'>
			</form>
			</td>
			";
			echo "</tr>";
		}	
		?>
		</tbody>
		</table>
	</div>
	<div class="rightCol"><br><br>
		<u>Your Blocks:</u><br><br>
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
			if ($row['desc'] == "") {
				$row['desc'] = "[No description.]";
			}
			echo "<tr>";
			echo "<td>" . "<a href='blockpage.php?block_id=" . $row['block_id'] . "&block_name=".$row['name'].".'>" . $row['name'] . "</a>" . "</td>";
			echo "<td style='padding: 10px;'>" . $row['desc'] . "</td>";
			echo "<td style='padding:2px;'>
			<form action='stationpage.php' method='get'>
				<input type='hidden' name='blockToAdd' value='".$row['block_id']."'>
				<input type='hidden' name='station_id' value='".$station_id."'>
				<input type='hidden' name='station_name' value='".$station_name."'>
				<input type='submit' value='Add to Station'>
			</form>
			</td>
			";
			echo "</tr>";
		}	
		?>
		</tbody>
		</table>
	</div>
</div>