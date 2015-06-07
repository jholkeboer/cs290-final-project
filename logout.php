<?php
include "infostash.php";
session_start();
unset($_SESSION["loginStatus"]); 
$_SESSION = array();
session_destroy;
header("Location: {$redirect}/landing.php");
die();
?>