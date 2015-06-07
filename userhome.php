<?php
session_start();
if ($_SESSION['loginStatus'] != 1) {
	//user is not actually logged in.  destroy session and send them back to homepage.
}
if ($_SESSION['loginStatus'] == 1) {
	$sessionUser = $_SESSION['user'];
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

<div class="viewport">

</div>
</body>
