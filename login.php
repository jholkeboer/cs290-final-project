<?php
session_start();
include "infostash.php";
if (!empty($_SESSION['loginStatus']) && !empty($_SESSION['user'])) {
	//user is already logged in, redirect to userhome
	header("Location: {$redirect}/userhome.php", true);
	die();
}
elseif (!empty($_POST['username-input']) && !empty($_POST['password-input'])) {
	//user just logged in, get info from POST and redirect
	$_SESSION['user'] = $_POST['username-input'];
	$_SESSION['loginStatus'] = 1;
	header("Location: {$redirect}/userhome.php", true);
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
		<li><a href="login.php">Log In</a></li>
		<li><a href="signup.php">Sign Up</a></li>
	</ul>
</div>

<div class="viewport">
	<br>
	<div id="signupMsg"></div>
	<div id="errorMsg"></div>
	<h1>log in to auxjockey:</h1>
	<form action="login.php" method="post" id="login-form">
		<div>
			<label>Username: </label>
			<input type="text" name="username-input" id="username-input">
		</div>
		<div>
			<label>Password: </label>
			<input type="text" name="password-input" id="password-input">
		</div>
		<div>
			<input type="button" value="Log In" onclick="loginScript()" id="login-button">
		</div>
	</form>
</div>
</body>

<?php
if (isset($_GET['signin'])) {
	if ($_GET['signin'] == "true") {
		echo "
		<script>
			document.getElementById('signupMsg').textContent = 'Thanks for signing up. You can log in now.';
		</script>
		";
	}
}	
?>