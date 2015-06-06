<?php
//$mysqli = new mysqli("oniddb.cws.oregonstate.edu","holkeboj-db",$holkebojpass,"holkeboj-db");
//if(!$mysqli || $mysqli->connect_errno) {
//	echo "Unable to connect to database.  Error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
//}

////local db
$mysqli = new mysqli("localhost","root",$localpass,"blockbuster","3306");
if(!$mysqli || $mysqli->connect_errno) {
	echo "Unable to connect to database.  Error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}	
?>
<!doctype html>
<head>
	<script type="text/javascript" src="scripts.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="titlebar">
	<a href="landing.html">auxjockey<a>
</div>

<div class="sidebar">
	<ul>
		<li><a href="login.php">Log In</a></li>
		<li><a href="signup.php">Sign Up</a></li>
	</ul>
</div>

<div class="viewport">
	<h1>sign up for auxjockey:</h1>
	<form>
		<div>
			<label>Choose your username: </label>
			<input type="text" name="username-input" id="username-input">
		</div>
		<div>
			<label>Choose your password: </label>
			<input type="text" name="password-input" id="password-input">
		</div>
		<div>
			<input type="button" value="Sign Up" onclick="signupScript()" id="signup-button">
		</div>
	</form>
</div>
</body>