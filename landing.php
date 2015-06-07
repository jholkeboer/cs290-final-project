<?php
session_start();
include "infostash.php";
if (isset($_SESSION['loginStatus'])) {
	if ($_SESSION['loginStatus'] == 1) {
		header("Location: {$redirect}/userhome.php", true);
		die();	
	}
}
?>
<!doctype html>
<link rel="stylesheet" type="text/css" href="style.css">

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
	<h1>welcome to auxjockey.</h1><br>
	<a href="login.php">Log in</a> or <a href="signup.php">sign up.</a>
</div>