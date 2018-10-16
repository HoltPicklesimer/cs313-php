<?php
require "dbConnect.php";

$query = "";

if (isset($_GET))
	$query = $_GET["name"];

$search = true;

if ($query == "")
	$search = false;

?>

<!DOCTYPE html>

<html>
<head>
	<title>Scripture Resources</title>
</head>
<body>

	<h1>Scriptures Resources</h1>

	<?php

	if ($search)
	{
		foreach ($db->query("SELECT book, chapter, verse, content FROM Scriptures WHERE book = $query") as $row) // good practice to do each one
		{
		  echo '<p><b>' . $row['book'] . ' ' . $row['chapter'] . ':' . $row['verse'] . '</b> - "' . $row['content'] . '"</p>';
		}
	}
	

	?>

	<form action="scriptures.php" method="get">
		Search: <input type="text" name="name">
		<button type="submit">Search</button>
	</form>

</body>

</html>