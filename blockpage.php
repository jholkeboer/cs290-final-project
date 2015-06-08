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
if (isset($_GET['block_name'])) {
	$block_name = $_GET['block_name'];
}

	//query to get items in this block
if (isset($block_id)) {
		//prepare statement
	if (!($getItems = $mysqli->prepare("SELECT * FROM item WHERE item_id in (SELECT item_id FROM item_block WHERE block_id=?)"))) {
		echo "Prepare failed on getitems";
	}
		//bind parameters
	if (!($getItems->bind_param("i", $block_id))) {
		echo "Binding failed on getitems";
	}
		//execute
	if (!($getItems->execute())) {
		echo "Error, getitems did not execute";		
	}
	else {
		$itemResult = $getItems->get_result();
	}
	$getItems->close();	
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
		Add video to <?php echo $block_name; ?>:
		<form method="post" action="blockpage.php">
			<label>Video Name:</label>
			<input type="text" name="item-name" required><br>
			<label>Video Description:</label>
			<input type="text" name="item-desc">
			<input type="hidden" name="block-id" value="<?php echo $block_id ?>">
			<input type="submit" value="Add Video">
		</form>
		<br><br><u>Items in <b><?php echo $block_name; ?></b>:</u><br><br>
		<table>
		<thead>
			<tr>
				<td></td>
				<td></td>
			</tr>
		</thead>
		<tbody>
		<?php
		while ($row = $itemResult->fetch_assoc()) {
			if ($row['desc'] == "") {
				$row['desc'] = "[No description.]";
			}
			echo "<tr>";
			echo "<td>" . "<a href='https://www.youtube.com/watch?v=" . $row['yid'] . "'>" . $row['title'] . "</a>" . "</td>";
			echo "<td style='padding: 10px;'>" . $row['desc'] . "</td>";
			echo "<td style='padding:2px;'>
			<form action='userhome.php' method='post'>
				<input type='hidden' name='stationToDelete' value='".$row['item_id']."'>
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