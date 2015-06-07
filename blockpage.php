<?php
include "infostash.php";
session_start();
if ($_SESSION['loginStatus'] != 1) {
	//user is not actually logged in.  destroy session and send them back to homepage.
}
if ($_SESSION['loginStatus'] == 1) {
	$sessionUser = $_SESSION['user'];
}
if (isset($_GET['block_id'])) {
	$block_id = $_GET['block_id'];
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
		<li><a href="logout.php">Log Out</a></li>
	</ul>
</div>
<div class="viewport"><br>

	<div class="leftCol">
		Add station:
		<form method="post" action="userhome.php">
			<label>Name:</label>
			<input type="text" name="station-name" required><br>
			<label>Description:</label>
			<input type="text" name="station-desc">
			<input type="hidden" name="owner-id" value="<?php echo $owner_id ?>">
			<input type="submit" value="Create Station">
		</form>
		<br><br><u>Your Stations:</u><br><br>
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
			if ($row['desc'] == "") {
				$row['desc'] = "[No description.]";
			}
			echo "<tr>";
			echo "<td>" . "<a href='stationpage.php?station_id=" . $row['station_id'] . "'>" . $row['name'] . "</a>" . "</td>";
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
</div>