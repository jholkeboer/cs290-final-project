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

	//check for station add post
if (isset($_POST['station-name'])) {
		//prepare statement
	if (!($addStation = $mysqli->prepare("INSERT INTO station (`name`,`desc`,owner_id) values (?,?,?)"))) {
		echo "Prepare failed on addStation";
	}
		//bind parameters
	if (!($addStation->bind_param("ssi", $_POST['station-name'], $_POST['station-desc'], $owner_id))) {
		echo "Binding failed on addStation";
	}
		//execute
	if (!($addStation->execute())) {
		echo "Error. addStation did not execute.";		
	}
	else {
		header("Location: {$redirect}/userhome.php", true);
		die();
	}
	$addStation->close();		
}

	//check for block add post
if (isset($_POST['block-name'])) {
		//prepare statement
	if (!($addBlock = $mysqli->prepare("INSERT INTO block (`name`,`desc`,owner_id) values (?,?,?)"))) {
		echo "Prepare failed on addBlock";
	}
		//bind parameters
	if (!($addBlock->bind_param("ssi", $_POST['block-name'], $_POST['block-desc'], $owner_id))) {
		echo "Binding failed on addBlock";
	}
		//execute
	if (!($addBlock->execute())) {
		echo "Error. addBlock did not execute.";		
	}
	else {
		header("Location: {$redirect}/userhome.php", true);
		die();
	}
	$addBlock->close();	
}

	//check for station delete post
if (isset($_POST['stationToDelete'])) {
		//prepare statement
	if (!($deleteStation = $mysqli->prepare("DELETE FROM station WHERE station_id=?"))) {
		echo "Prepare failed on deleteStation.";
	}
		//bind parameters
	if (!($deleteStation->bind_param("i",$_POST['stationToDelete']))) {
		echo "Binding failed on deleteStation.";
	}
		//execute
	if (!($deleteStation->execute())) {
		echo "Error. deleteStation did not execute.";		
	}
	$deleteStation->close();
		//prepare statement
	if (!($deleteBlockStation = $mysqli->prepare("DELETE FROM block_station WHERE station_id=?"))) {
		echo "Prepare failed on deleteBlockStation.";
	}
		//bind parameters
	if (!($deleteBlockStation->bind_param("i",$_POST['stationToDelete']))) {
		echo "Binding failed on deleteBlockStation.";
	}
		//execute
	if (!($deleteBlockStation->execute())) {
		echo "Error. deleteBlockStation did not execute.";		
	}
	$deleteBlockStation->close();
	header("Location: {$redirect}/userhome.php", true);
	die();	
}

	//check for block delete post
if (isset($_POST['blockToDelete'])) {
	//we have to perform deletes on:
									//block
		//prepare statement
	if (!($deleteBlock = $mysqli->prepare("DELETE FROM block WHERE block_id=?"))) {
		echo "Prepare failed on deleteBlock.";
	}
		//bind parameters
	if (!($deleteBlock->bind_param("i",$_POST['blockToDelete']))) {
		echo "Binding failed on deleteBlock.";
	}
		//execute
	if (!($deleteBlock->execute())) {
		echo "Error. deleteBlock did not execute.";		
	}
	$deleteBlock->close();
									//item_block
		//prepare statement
	if (!($deleteBlockItem = $mysqli->prepare("DELETE FROM item_block WHERE block_id=?"))) {
		echo "Prepare failed on deleteBlockStation.";
	}
		//bind parameters
	if (!($deleteBlockItem->bind_param("i",$_POST['blockToDelete']))) {
		echo "Binding failed on deleteBlockItem.";
	}
		//execute
	if (!($deleteBlockItem->execute())) {
		echo "Error. deleteBlockItem did not execute.";		
	}
	$deleteBlockItem->close();
									//block_block
		//prepare statement
	if (!($deleteBlockBlock = $mysqli->prepare("DELETE FROM block_block WHERE parent_block=? OR child_block=?"))) {
		echo "Prepare failed on deleteBlockStation.";
	}
		//bind parameters
	if (!($deleteBlockBlock->bind_param("ii",$_POST['blockToDelete'],$_POST['blockToDelete']))) {
		echo "Binding failed on deleteBlockStation.";
	}
		//execute
	if (!($deleteBlockBlock->execute())) {
		echo "Error. deleteBlockStation did not execute.";		
	}
	$deleteBlockBlock->close();
	header("Location: {$redirect}/userhome.php", true);
	die();	
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

	<div class="leftCol">
		Create station:
		<form method="post" action="userhome.php">
			<label>Name:</label>
			<input type="text" name="station-name" required><br>
			<label>Description:</label>
			<input type="text" name="station-desc">
			<input type="hidden" name="owner-id" value="<?php echo $owner_id ?>">
			<input type="submit" value="Create Station">
		</form>
		<br><br><u>Edit Your Stations:</u><br><br>
		<table>
		<thead>
			<tr>
				<td></td>
				<td></td>
			</tr>
		</thead>
		<tbody>
		<?php
		$stationResult->data_seek(0);
		while ($row = $stationResult->fetch_assoc()) {
			if ($row['desc'] == "") {
				$row['desc'] = "[No description.]";
			}
			echo "<tr>";
			echo "<td>" . "<a href='stationpage.php?station_id=" . $row['station_id'] . "&station_name=".$row['name']."'>" . $row['name'] . "</a>" . "</td>";
			echo "<td style='padding: 10px;'>" . $row['desc'] . "</td>";
			echo "<td style='padding:2px;'>
			<form action='userhome.php' method='post'>
				<input type='hidden' name='stationToDelete' value='".$row['station_id']."'>
				<input type='submit' value='Delete'>
			</form>
			</td>
			";
			echo "</tr>";
			echo "</tr>";
		}	
		?>
		</tbody>
		</table>
	</div>
	<div class="rightCol">
		Create block:
		<form method="post" action="userhome.php">
			<label>Name:</label>
			<input type="text" name="block-name" required><br>
			<label>Description:</label>
			<input type="text" name="block-desc">
			<input type="hidden" name="owner-id" value="<?php echo $owner_id ?>">
			<input type="submit" value="Create block">
		</form><br><br>
		<u>Edit Your Blocks:</u><br><br>
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
			<form action='userhome.php' method='post'>
				<input type='hidden' name='blockToDelete' value='".$row['block_id']."'>
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
</div>
</body>
