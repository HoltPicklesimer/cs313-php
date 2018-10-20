<?php

// Connect to the Database
require "connectDb.php";

if (!isset($_SESSION))
	session_start();

// If the user is not logged in, go to the Log-In Screen
if (!isset($_SESSION["userId"]))
	header('Location: signin.php');
if ($_SESSION["userId"] < 1)
	header('Location: signin.php');

// Get the user id
$id = $_SESSION["userId"];

// Edit the artist if editing and submitted
if ($_POST)
{
	// If the artist is being deleted
	if (isset($_POST["deleteSong"]))
	{
		// Get the song id
		$songId = $_POST["deleteSong"];
		// Remove from the database if id is not 0
		// Then redirect to the user's page
	}
	else
	{
		// Get the song id
		$songId = $_POST["applyChanges"];

		// Editing?
		$edit = 0;
		// Sanatize the inputs
		// if (isset($_POST["applyChanges"]))
		// Insert into the database and reload the page
	}
}
else
{
	// Get the song id
	$songId = $_GET["id"];

	// Editing?
	$edit = $_GET["edit"];
}

$stmt = $db->prepare("
	SELECT s.name AS song_name, s.url, s.id AS song_id, s.release_date,
	s.lyrics, s.contributor_id, g.name AS genre_name, a.name AS artist_name, a.id AS artist_id
	FROM songs s
	JOIN artists a ON a.id = s.artist_id
	JOIN genres g ON s.genre_id = g.id
	WHERE s.id = $songId");
$stmt->execute();
$songList = $stmt->fetchAll(PDO::FETCH_ASSOC);
$song = $songList[0];
$songName = $song["song_name"];
$songId = $song["song_id"];
$songCon = $song["contributor_id"];
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

// Get the reviews
$stmt2 = $db->prepare("SELECT r.id, r.user_id, r.publish_date,
	r.content, r.rating, u.username
	FROM reviews r
	JOIN users u ON u.id = r.user_id
	WHERE song_id = $songId");
$stmt2->execute();
$reviewList = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Get all genres
$stmt3 = $db->prepare("SELECT id, name FROM genres");
$stmt3->execute();
$genreList = $stmt3->fetchAll(PDO::FETCH_ASSOC);

// Get all artists
$stmt4 = $db->prepare("SELECT id, name FROM artists");
$stmt4->execute();
$artistList = $stmt4->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">

<head>

  <title>Song Page</title>
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

<?php if ($edit == FALSE) { // if the user is not editing?>
<div class="container">
	<br/>
	
	<div class="col-sm-7">
		<h1><?php echo $songName; ?></h1>
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
	<br/>
	<br/>
	<br/>
	<a href="song.php?id=<?php echo $songId; ?>&edit=1"><span class="text-info">Edit this Song</span></a>

	<p>Artist: <a href="artist.php?id=<?php echo $artistId; ?>&edit=0" class="text-info"><?php echo $artistName; ?></a></p>
	<br/>
	<br/>
	<p>Rating: <?php echo round($rating) . "/5"; ?><br/>
	Genre: <?php echo $genre; ?><br/>
	Released: <?php echo $releaseDate; ?></p>
<?php if ($url != "") { ?>
	<p><a href="<?php echo $url; ?>" class="text-info" target="_blank">Watch the Music Video</a></p>
<?php } ?>
	<p>Lyrics:<br/><?php echo $lyrics; ?></p>

	<h2>Reviews:</h2>

<?php
foreach ($reviewList as $review) {
	$reviewId = $review["id"];
	$reviewConId = $review["user_id"];
	$reviewCon = $review["username"];
	$reviewDate = $review["publish_date"];
	$reviewContent = $review["content"];
	$reviewRating = $review["rating"];
?>

	<hr class="style14">
	<div class="col-sm-9">
<?php if ($id == $reviewConId) {?>
	<h3><a href="review.php?id=<?php echo $reviewId; ?>&edit=0" class="text-info">Review by <?php echo $reviewCon; ?></a></h3>
<?php } else { ?>
	<h3>Review by <?php echo $reviewCon; ?></h3>
<?php } ?>
	</div>
	<div class="col-sm-3"></div>
	<br/>
	<br/>
	<br/>
	<p>Date Published: <?php echo date("F jS, Y", strtotime($reviewDate)); ?><br/>
	Rating: <?php echo $reviewRating . "/5"; ?></p>
	<p><?php echo $reviewContent; ?></p>

<?php
}
?>

</div>

<?php } else { // The user is editing ?>

<div class="container">
	<br/>
	
	<div class="col-sm-7">
	<h1><?php echo "Review for: " . $songName; ?></h1>
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

	<form method="post" action="song.php">
		Song Name: <input type="text" name="newName" size="50" value="<?php echo $songName; ?>"><br/>

		<div class="form-group">
		  <label for="sel1">Select Genre</label>
		  <select class="form-control" id="sel1" name="newGenre">
<?php foreach ($genreList as $genreItem) { ?>
<option value="<?php echo $genreItem['id']; ?>"><?php echo $genreItem["name"]; ?></option>
<?php } ?>
		  </select>
		</div>

		<div class="form-group">
		  <label for="sel2">Select Artist</label>
		  <select class="form-control" id="sel2" name="newArtist">
<?php foreach ($artistList as $artistItem) { ?>
<option value="<?php echo $artistItem['id']; ?>"><?php echo $artistItem["name"]; ?></option>
<?php } ?>
		  </select>
		</div>

		Release Date (Format YYYY-MM-DD):
		<input type="text" name="newReleaseDate" maxlength="10" value="<?php echo $releaseDate; ?>"><br/>
		Music Video Link: <input type="text" name="newURL" size="100" value="<?php echo $url; ?>"><br/>
		Lyrics: <br/><textarea rows="50" cols="150" name="newLyrics"><?php echo $lyrics; ?></textarea><br/>

		<button type="submit" name="applyChanges" value="<?php echo $songId; ?>" class="btn btn-info">Save Changes</button>
		<button type="submit" name="deleteSong" value="<?php echo $songId; ?>" class="btn btn-danger">DELETE SONG</button>
	</form>

</div>

<?php } ?>

</body>

</html>