<?php

	//start a session
	session_start();

	// increase the session variable visits if it exists, if not, create it
	if (!isset($_SESSION["visits"]))
		$_SESSION["visits"] = 1;
	else
		$_SESSION["visits"]++;

	// Set a cookie
	setcookie("fav-text", "c is for cookie", time() + (86400 * 7));
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<p>This is a page</p>
	<?php echo "You have visited : " . $_SESSION["visits"] . " time(s).; ?>

</body>
</html>