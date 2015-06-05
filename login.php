<!doctype html>
<head>
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
	<h1>log in to auxjockey:</h1>
	<form>
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