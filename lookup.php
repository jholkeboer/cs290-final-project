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

//login query
if (isset($_POST['loginuser'])) {
	$loginuser = $_POST['loginuser'];
}
if (isset($_POST['loginpass'])) {
	$loginpass = md5($_POST['loginpass']);
}
if (isset($loginuser) && isset($loginpass)) {
		//prepare lookup statement
	if (!($loginQuery = $mysqli->prepare("SELECT count(1) AS `count` FROM user_ WHERE u_name=? AND u_pass=?"))) {
		echo "Prepare failed on loginQuery";
	}
		//bind parameters
	if (!($loginQuery->bind_param("ss", $loginuser, $loginpass))) {
		echo "Binding failed on loginQuery";
	}
		//execute
	if (!($loginQuery->execute())) {
		echo json_encode(array("status" => "failed", "user" => $loginuser));		
	}
	else {
		$loginResult = $loginQuery->get_result();
		while ($row = $loginResult->fetch_assoc()) {
			if ($row['count'] == 0) {
				//in this case, the user/pass combo did not find a match
				echo json_encode(array("status" => "failed", "user" => $loginuser));				
			}
			else if ($row['count'] == 1) {
				echo json_encode(array("status" => "ok", "user" => $loginuser));
			}
		}
	}
}

?>