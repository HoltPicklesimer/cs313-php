<?php
	session_start();
	if (!isset($_SESSION["count"]))
		$_SESSION["count"] = 0;
	else
		$_SESSION["count"]++;
	setcookie("fav-text", "c is for cookie", time() + (86400 * 7));
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<p>This is a page</p>
	<?php echo "count: " . $_SESSION["count"]; ?>

</body>
</html>