<?php
//This file is purely server side code to handle AJAX requests.
include "infostash.php";
//$mysqli = new mysqli("oniddb.cws.oregonstate.edu","holkeboj-db",$holkebojpass,"holkeboj-db");
//if(!$mysqli || $mysqli->connect_errno) {
//	echo "Unable to connect to database.  Error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
//}

////local db
$mysqli = new mysqli("localhost","root",$localpass,"auxjockey","3306");
if(!$mysqli || $mysqli->connect_errno) {
	echo "Unable to connect to database.  Error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

//signup query
if (isset($_POST['username'])) {
	$username_candidate = $_POST['username'];
}
if (isset($_POST['userpass'])) {
	$password_candidate = md5($_POST['userpass']);
}
if (isset($username_candidate) && isset($password_candidate)) {
		//prepare insert statement
		if (!($addUser = $mysqli->prepare("INSERT INTO user_ (u_name,u_pass) values (?,?)"))) {
		echo "Prepare failed on addUser";
	}
		//bind parameters
	if (!($addUser->bind_param("ss", $username_candidate, $password_candidate))) {
		echo "Binding failed on addUser";
	}
		//execute
	if (!($addUser->execute())) {
		echo json_encode(array("status" => "failed", "user" => $username_candidate));
	}
	else {
		echo json_encode(array("status" => "ok", "user" => $username_candidate));
	}
	$addUser->close();
}


?>