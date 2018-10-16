<?php
require "dbConnect.php";

$book = $_GET["book"];

$stmt = $db->prepare('SELECT * FROM Scriptures WHERE book=:book');
$stmt->bindValue(':book', $book, PDO::PARAM_STR);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html>
<head>
	<title>Scripture Resources</title>
</head>
<body>

	<h1>Scriptures Resources</h1>

	<?php

	if ($book != "")
	{
		foreach ($rows as $row) // good practice to do each one
		{
		  echo '<p><b>' . $row['book'] . ' ' . $row['chapter'] . ':' . $row['verse'] . '</b> - "' . $row['content'] . '"</p>';
		}
	}
	

	?>

	<form action="scriptures.php" method="get">
		Search: <input type="text" name="book">
		<button type="submit">Search</button>
	</form>

</body>

</html>