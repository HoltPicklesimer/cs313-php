<?php

// Redirect if the user id is not set or invalid

if (!isset($_SESSION))
	session_start();

// If the user is not logged in, go to the Log-In Screen
if (!isset($_SESSION["userId"]) || $_SESSION["userId"] < 1)
{
	$_SESSION["userId"] = "";
	unset($_SESSION["userId"]);
	session_destroy();
	$_SESSION = [];
	header('Location: signin.php');
}

// Get the user id
$id = $_SESSION["userId"];

?>