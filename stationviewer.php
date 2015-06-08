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
	
?>