<?php require "dbConnect.php"; ?>

<!DOCTYPE html>

<html>
<head>
	<title>Scripture Resources</title>
</head>
<body>

	<h1>Scriptures Resources</h1>

	<?php

	foreach ($db->query('SELECT book, chapter, verse, content FROM Scriptures') as $row) // good practice to do each one
	{
	  echo '<p><b>' . $row['book'] . ' ' . $row['chapter'] . ':' . $row['verse'] . '</b> - "' . $row['content'] . '"</p>';
	}

	?>

</body>

</html>