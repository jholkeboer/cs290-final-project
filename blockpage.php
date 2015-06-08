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
if (isset($_GET['block_id'])) {
	$block_id = $_GET['block_id'];
}
if (isset($_GET['block_name'])) {
	$block_name = $_GET['block_name'];
}
if (isset($_GET['itemToDelete']) && isset($_GET['block_id'])) {
	$itemToDelete = $_GET['itemToDelete'];
}
if (isset($_GET['item-name'])) {
	$item_name = $_GET['item-name'];
}
if (isset($_GET['item-desc'])) {
	$item_desc = $_GET['item-desc'];
}
if (isset($_GET['youtube-url']) && $urlParse = parse_url($_GET['youtube-url'], PHP_URL_QUERY)) {
	parse_str($urlParse, $query);
	$yid = $query['v'];
}
echo ($block_name);
echo ($block_id);

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

	//delete item from block
if (isset($itemToDelete)) {
	//delete from item
			//prepare statement
	if (!($deleteItem = $mysqli->prepare("DELETE FROM block WHERE block_id=?"))) {
		echo "Prepare failed on deleteItem.";
	}
		//bind parameters
	if (!($deleteItem->bind_param("i",$itemToDelete))) {
		echo "Binding failed on deleteItem.";
	}
		//execute
	if (!($deleteItem->execute())) {
		echo "Error. deleteItem did not execute.";		
	}
	$deleteItem->close();
	//delete from item_block
		//prepare statement
	if (!($deleteBlockItem = $mysqli->prepare("DELETE FROM item_block WHERE item_id=?"))) {
		echo "Prepare failed on deleteBlockStation.";
	}
		//bind parameters
	if (!($deleteBlockItem->bind_param("i",$itemToDelete))) {
		echo "Binding failed on deleteBlockItem.";
	}
		//execute
	if (!($deleteBlockItem->execute())) {
		echo "Error. deleteBlockItem did not execute.";		
	}
	$deleteBlockItem->close();
	header("Location: {$redirect}/blockpage?block_id=".$block_id."&block_name=".$block_name, true);
	die();	
}
	//add item to block
if (isset($item_name) && isset($item_desc) && isset($yid)) {
	//first create the item
		//prepare statement
	if (!($addItem = $mysqli->prepare("INSERT INTO item (`title`,`desc`,yid) values (?,?,?)"))) {
		echo "Prepare failed on addItem";
	}
		//bind parameters
	if (!($addItem->bind_param("sss", $item_name, $item_desc, $yid))) {
		echo "Binding failed on addItem";
	}
		//execute
	if (!($addItem->execute())) {
		echo "Error. addItem did not execute.";		
	}
	$addItem->close();
	//get the item id (the most recent one)
	if (!($getItemID = $mysqli->prepare("SELECT max(item_id) as `newid` from item"))) {
		echo "Prepare failed on getItemID";
	}
		//execute
	if (!($getItemID->execute())) {
		echo "Error. getItemID did not execute.";		
	}
	else {
		$itemIDResult = $getItemID->get_result();
	}
	while ($row = $itemIDResult->fetch_assoc()) {
		$newItemID = intval($row['newid']);
	}
	$getItemID->close();
	//now associate it with the block
	if (isset($newItemID)) {
			//prepare statement
		if (!($addItemBlock = $mysqli->prepare("INSERT INTO item_block (block_id,item_id) values (?,?)"))) {
			echo "Prepare failed on addItemBlock";
		}
			//bind parameters
		if (!($addItemBlock->bind_param("ii",intval($block_id),$newItemID))) {
			echo "Binding failed on addItemBlock";
		}
			//execute
		if (!($addItemBlock->execute())) {
			echo "Error. addItemBlock did not execute.";		
		}
		$addItemBlock->close();
	}
	header("Location: {$redirect}/blockpage?block_id=".$block_id."&block_name=".$block_name, true);
	die();
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
		<form action="blockpage.php" method="get">
			<label>Video Title:</label>
			<input type="text" name="item-name" required><br>
			<label>Video Description:</label>
			<input type="text" name="item-desc">
			<label>Youtube URL:</label>
			<input type="text" name="youtube-url">
			<?php echo "
				<input type='hidden' name='block_id' value='".$block_id."'>
				<input type='hidden' name='block_name' value='".$block_name."'>	
			"
			?>
			<input type="hidden" name="tester" value='testerval'>
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
			<form action='blockpage.php' method='get'>
				<input type='hidden' name='itemToDelete' value='".$row['item_id']."'>
				<input type='hidden' name='block_id' value='".$block_id."'>
				<input type='hidden' name='block_name' value='".$block_name."'>
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