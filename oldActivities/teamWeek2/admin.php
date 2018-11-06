<?php
	$_SESSION["user"] = $_GET['admin'];
	header('Location: siteHome.php');
?>