<?php require "dbConnect.php"; ?>

<!DOCTYPE html>

<html>
<head>
	<title>SCripture Resources</title>
</head>
<body>

	<h1>Scriptures Resources</h1>

	<?php

	foreach ($db->query('SELECT * FROM Scriptures') as $row)
	{
	  echo '<p><b>' . $row['book'] . ' ' . $row['chapter'] . ':' . $row['verse'] . '</b> - "' . $row['content'] . '"</p>';
	}

	?>

</body>

</html>