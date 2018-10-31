<?php

if (!isset($_SESSION))
	session_start();

// If the user is not logged in, go to the Log-In Screen
if (!isset($_SESSION["userId"]) || $_SESSION["userId"] < 1)
{
	$_SESSION["userId"] = "";
	unset($_SESSION["userId"]);
	session_destroy();
	header('Location: signin.php');
}

// Get the user id
$id = $_SESSION["userId"];

?>