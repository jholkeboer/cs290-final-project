<?php
include "infostash.php";
session_start;
$_SESSION = array();
session_destroy;
header("Location: {$redirect}/landing.php", true);
die();
?>