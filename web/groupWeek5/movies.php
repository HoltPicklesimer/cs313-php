<?php

// connect to db
require "../teamWeek5/dbConnect.php";
//query for all movies
$stmt = $db->prepare('SELECT id, title, year FROM movie');
$stmt->execute();
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// go through each movie in the result and display it

?>

<!DOCTYPE html>
<html>
<head>
	<title>Movies</title>
</head>
<body>

	<h1>Movies</h1>


	<ul>
<?php

	foreach ($movies as $movie) {
		$title = $movie['title'];
		$year = $movie['year'];
		echo "<li><p>$title $movie</p></li>";
	}

?>

	</ul>

</body>
</html>