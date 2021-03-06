<?php

// Connect to the Database
require "connectDb.php";

// Redirect if the user id is not set or invalid
require("verifyUser.php");

// Delete song if delete button is pressed
if ($_POST)
{
	if (isset($_POST["delete"]))
	{
		$psId = htmlspecialchars($_POST["delete"]);

		$stmt2 = $db->prepare("DELETE FROM playlistsongs WHERE id = :psid");
		$stmt2->bindValue(':psid', $psId, PDO::PARAM_INT);
		$stmt2->execute();
	}
}

$stmt = $db->prepare("
	SELECT s.name AS song_name, s.url, s.id AS song_id, s.release_date, ps.id AS ps_id,
	s.lyrics, g.name AS genre_name, a.name AS artist_name, a.id AS artist_id
	FROM users u
	JOIN playlistsongs ps ON ps.user_id = u.id
	JOIN songs s ON s.id = ps.song_id
	JOIN artists a ON a.id = s.artist_id
	JOIN genres g ON s.genre_id = g.id
	WHERE u.id = $id");
$stmt->execute();
$playlist = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the username
$stmt2 = $db->prepare("SELECT username FROM users WHERE id = $id");
$stmt2->execute();
$userList = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$username = $userList[0]["username"];

$message = "";

?>


<!DOCTYPE html>
<html lang="en">

<head>

  <title>Rockin' Database Home</title>
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
		<h1>Welcome <?php echo $username; ?></h1>
	</div>
	<div class="col-sm-5" style="text-align:right">
		<form method="get" action="results.php">
			<input type="text" name="searchSongs">
			<button type="submit" name="search" value="sent" class="btn btn-info">Search Songs and Artists</button>
		</form>
		<br/>
		<a href="song.php?id=0&edit=1"><span class="text-info">Add a New Song to the Database</span></a></br>
		<a href="artist.php?id=0&edit=1"><span class="text-info">Add a New Artist to the Database</span></a>
	</div>
	</div>
	<br/>
	<br/>
	<br/>
	<h2>Your Playlist:</h2>

<?php

foreach ($playlist as $song) {
	$songName = $song["song_name"];
	$songId = $song["song_id"];
	$url = $song["url"];
	$releaseDate = $song["release_date"];
	$lyrics = $song["lyrics"];
	$genre = $song["genre_name"];
	$artistName = $song["artist_name"];
	$artistId = $song["artist_id"];
	$psId = $song["ps_id"];
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
	<h3><a href="song.php?id=<?php echo $songId; ?>&edit=0" class="text-info"><?php echo $songName; ?></a> by
	<a href="artist.php?id=<?php echo $artistId; ?>&edit=0" class="text-info"><?php echo $artistName; ?></a></h3>
	</div>
	<div class="col-sm-3">
		<form method="post" action="user.php">
			<button type="submit" name="delete" class="btn btn-danger" value="<?php echo $psId; ?>">
				Remove Song from Playlist
			</button>
		</form>
	</div>
	</div>
	<p>Rating: <?php echo round($rating) . "/5"; ?><br/>
	Genre: <?php echo $genre; ?><br/>
	Released: <?php echo $releaseDate; ?></p>
<?php if ($url != "") { ?>
	<p><a href="<?php echo $url; ?>" class="text-info" target="_blank">Watch the Music Video</a></p>
<?php }
} ?>

</div>

</body>

</html>