<?php

// Connect to the Database
require "connectDb.php";

require "verifyUser.php";

$searchItem = "";

// Get the searched item
if ($_GET)
{
	if (isset($_GET["searchSongs"]))
	{
		$searchItem = htmlspecialchars($_GET["searchSongs"]);
	}
}

$message = "";

if ($_POST)
{
	if (isset($_POST["add"]))
	{
		$songId = htmlspecialchars($_POST["add"]);

		// Find any occurrence of the song in the playlist already
		$stmt = $db->prepare("SELECT user_id, song_id FROM playlistsongs WHERE user_id = :user AND song_id = :song");
		$stmt->bindValue(':user', $id, PDO::PARAM_INT);
		$stmt->bindValue(':song', $songId, PDO::PARAM_INT);
		$stmt->execute();
		$list = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if (empty($list)) //  if the song is not already in the user's playlist, insert it
		{
			$stmt2 = $db->prepare("INSERT INTO playlistsongs (user_id, song_id) VALUES (:user, :song)");
			$stmt2->bindValue(':user', $id, PDO::PARAM_INT);
			$stmt2->bindValue(':song', $songId, PDO::PARAM_INT);
			$stmt2->execute();

			$message = "The song was added to your playlist.";
		}
		else
		{
			$message = "That song is already in your playlist.";
		}
	}
}

$searchTerm = "%" . $searchItem . "%";

$stmt = $db->prepare("SELECT s.id AS song_id, s.name AS song_name, a.id AS artist_id, a.name AS artist_name, g.name AS genre_name
	FROM songs s
	JOIN artists a ON s.artist_id = a.id
	JOIN genres g ON s.genre_id = g.id
	WHERE UPPER(s.name) LIKE UPPER(:searchTerm)
	OR UPPER(a.name) LIKE UPPER(:searchTerm)");
$stmt->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);
$stmt->execute();
$resultList = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">

<head>

  <title>Search Results</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<!-- Latest compiled and minified CSS for Bootstrap -->
	<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
	</script>

	<!-- Latest compiled JavaScript for Bootstrap -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js">
	</script>

	<link rel="stylesheet" href="rmd.css">

</head>

<body>

<?php include "header.php"; ?>

<div class="container">
	<br/>
	
	<div class="row">
	<div class="col-sm-7">
		<h1>Results for: <?php echo $searchItem; ?></h1>
	</div>
	<div class="col-sm-5" style="text-align:right">
		<form method="get" action="results.php">
			<input type="text" name="searchSongs">
			<button type="submit" name="search" value="sent" class="btn btn-info">Search Again</button>
		</form>
		<br/>
		<a href="song.php?id=0&edit=1"><span class="text-info">Add a New Song to the Database</span></a></br>
		<a href="artist.php?id=0&edit=1"><span class="text-info">Add a New Artist to the Database</span></a>
	</div>
	</div>

<?php foreach ($resultList as $song) {
	$songName = $song["song_name"];
	$songId = $song["song_id"];
	$genre = $song["genre_name"];
	$artistName = $song["artist_name"];
	$artistId = $song["artist_id"];
	$stmtRating = $db->prepare("
		SELECT  AVG(rating) AS avg_rating
		FROM reviews r
		JOIN songs s ON r.song_id = s.id
		WHERE s.id = $songId");
	$stmtRating->execute();
	$ratingList = $stmtRating->fetchAll(PDO::FETCH_ASSOC);
	$rating = $ratingList[0]["avg_rating"];
?>

	<hr class="style14">
	<div class="row">
	<div class="col-sm-9">
	<h4><a href="song.php?id=<?php echo $songId; ?>&edit=0" class="text-info"><?php echo $songName; ?></a> by
	<a href="artist.php?id=<?php echo $artistId; ?>&edit=0" class="text-info"><?php echo $artistName; ?></a></h4>
	<p>Rating: <?php echo round($rating) . "/5"; ?><br/>
	Genre: <?php echo $genre; ?></p>
	</div>
	<div class="col-sm-3">
		<form action="results.php?searchSongs=<?php echo $searchItem; ?>&search=sent" method="post">
			<button class="btn btn-info" type="submit" name="add" value="<?php echo $songId; ?>">Add to Playlist</button>
		</form>
	</div>
	</div>

<?php } if ($message != "") { echo "<script type='text/javascript'>$(document).ready(function(){alert('" . $message . "');});</script>"; } ?>

</div>

</body>

</html>