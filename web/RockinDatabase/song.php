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

$message = "";

// Edit the song if editing and submitted
if ($_POST)
{
	// If the song is being deleted
	if (isset($_POST["deleteSong"]))
	{
		// Get the song id
		$songId = htmlspecialchars($_POST["deleteSong"]);
		// Remove from the database if id is not 0
		if ($songId != 0)
		{
			// Remove all playlist associations to the song, reviews to song, and the song itself
			$stmt = $db->prepare('DELETE FROM playlistsongs WHERE song_id = :id');
			$stmt->bindValue(':id', $songId, PDO::PARAM_INT);
			$stmt->execute();

			$stmt2 = $db->prepare('DELETE FROM reviews WHERE song_id = :id');
			$stmt2->bindValue(':id', $songId, PDO::PARAM_INT);
			$stmt2->execute();

			$stmt3 = $db->prepare('DELETE FROM songs WHERE id = :id');
			$stmt3->bindValue(':id', $songId, PDO::PARAM_INT);
			$stmt3->execute();

			$message = 'Song successfully removed from the Database.';
		}
		// Go into edit mode
		$songId = 0;
		$edit = 1;
	}
	else
	{
		// Get the song information
		$songId = htmlspecialchars($_POST["applyChanges"]);
		$songName = htmlspecialchars($_POST["newName"]);
		$songGenre = htmlspecialchars($_POST["newGenre"]);
		$songLyrics = nl2br(htmlspecialchars($_POST["newLyrics"]));
		$songArtist = htmlspecialchars($_POST["newArtist"]);
		$songReleaseDate = htmlspecialchars($_POST["newReleaseDate"]);
		$songURL = htmlspecialchars($_POST["newURL"]);

		if ($artistName === "") // No name given
		{
			$message = 'Please enter a name for the song.';
			$edit = 1;
		}
		else if ($songId == 0) // Adding a new song
		{
			// Make sure the song is not already in the database
			$stmt = $db->prepare('SELECT id FROM songs WHERE name = :name AND artist_id = :artist');
			$stmt->bindValue(':name', $songName, PDO::PARAM_STR);
			$stmt->bindValue(':artist', $songArtist, PDO::PARAM_INT);
			$stmt->execute();
			$songList = $stmt->fetchAll(PDO::FETCH_ASSOC);

			if (empty($songList)) // Not taken, add to database
			{
				$stmt2 = $db->prepare("INSERT INTO songs
					(name, genre_id, contributor_id, artist_id, lyrics, release_date, url)
					VALUES (:name, :genre, $id, :artist, :lyrics, :release, :url)");
				$stmt2->bindValue(':name', $songName, PDO::PARAM_STR);
				$stmt2->bindValue(':genre', $songGenre, PDO::PARAM_INT);
				$stmt2->bindValue(':artist', $songArtist, PDO::PARAM_INT);
				$stmt2->bindValue(':lyrics', $songLyrics, PDO::PARAM_STR);
				$stmt2->bindValue(':release', $songReleaseDate, PDO::PARAM_INT);
				$stmt2->bindValue(':url', $songURL, PDO::PARAM_STR);
				$stmt2->execute();

				// Get the newly added id
				$stmt3 = $db->prepare("SELECT id FROM songs WHERE name = :name AND artist_id = :artist");
				$stmt3->bindValue(':name', $songName, PDO::PARAM_STR);
				$stmt3->bindValue(':artist', $songArtist, PDO::PARAM_INT);
				$stmt3->execute();
				$songId = $stmt3->fetch(PDO::FETCH_ASSOC)["id"];

				$edit = 0;

				$message = $songName . ' was added successfully.';
			}
			else // Already in the database
			{
				$message = 'Sorry, ' . $songName . ' is already in the database.';
				// Continue editing
				$edit = 1;
				$songId = 0;
			}
		}
		else // Updating an existing song
		{
			$stmt = $db->prepare("UPDATE songs
				SET name = :name, genre_id = :genre, artist_id = :artist, lyrics = :lyrics,
				release_date = :release, url = :url WHERE id = :id");
			$stmt->bindValue(':name', $songName, PDO::PARAM_STR);
			$stmt->bindValue(':genre', $songGenre, PDO::PARAM_INT);
			$stmt->bindValue(':artist', $songArtist, PDO::PARAM_INT);
			$stmt->bindValue(':lyrics', $songLyrics, PDO::PARAM_STR);
			$stmt->bindValue(':release', $songReleaseDate, PDO::PARAM_INT);
			$stmt->bindValue(':url', $songURL, PDO::PARAM_STR);
			$stmt->bindValue(':id', $songId, PDO::PARAM_INT);
			$stmt->execute();

			$edit = 0;
			$message = $songName . ' was updated successfully.';
		}
	}
}
else
{
	// Get the song id
	$songId = $_GET["id"];

	// Editing?
	$edit = $_GET["edit"];
}

if ($songId > 0)
{

	$stmt = $db->prepare("
		SELECT s.name AS song_name, s.url, s.id AS song_id, s.release_date, s.genre_id AS genre_id,
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
	$genreId = $song["genre_id"];
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
	
}

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
	
	<div class="row">
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
	</div>
	<a href="song.php?id=<?php echo $songId; ?>&edit=1"><span class="text-info">Edit this Song</span></a>
	<a href="song.php?id=0&songId=<?php echo $songId; ?>&edit=1"><span class="text-info">Write a Review for this Song</span></a>

	<p>Artist: <a href="artist.php?id=<?php echo $artistId; ?>&edit=0" class="text-info"><?php echo $artistName; ?></a></p>
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
	<div class="row">
	<div class="col-sm-9">
<?php if ($id == $reviewConId) {?>
	<h3><a href="review.php?id=<?php echo $reviewId; ?>&edit=0" class="text-info">Review by <?php echo $reviewCon; ?></a></h3>
<?php } else { ?>
	<h3>Review by <?php echo $reviewCon; ?></h3>
<?php } ?>
	</div>
	<div class="col-sm-3"></div>
	</div>
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
	
	<div class="row">
	<div class="col-sm-7">
	<h1><?php echo "Edit Song: " . $songName; ?></h1>
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

	<form method="post" action="song.php">
		Song Name: <input type="text" name="newName" size="50" value="<?php echo $songName; ?>"><br/>

		<div class="form-group">
		  <label for="sel1">Select Genre</label>
		  <select class="form-control" id="sel1" name="newGenre">
<?php foreach ($genreList as $genreItem) { ?>
<option value="<?php echo $genreItem['id']; ?>" <?php if ($genreId == $genreItem['id']) echo 'selected="selected"' ?>>
	<?php echo $genreItem["name"]; ?>
</option>
<?php } ?>
		  </select>
		</div>

		<div class="form-group">
		  <label for="sel2">Select Artist</label>
		  <select class="form-control" id="sel2" name="newArtist">
<?php foreach ($artistList as $artistItem) { ?>
<option value="<?php echo $artistItem['id']; ?>" <?php if ($artistId == $artistItem['id']) echo 'selected="selected"' ?>>
	<?php echo $artistItem["name"]; ?>
</option>
<?php } ?>
		  </select>
		</div>

		Release Date:
		<input type="text" name="newReleaseDate" value="<?php echo $releaseDate; ?>"><br/>
		Music Video Link: <input type="text" name="newURL" size="100" value="<?php echo $url; ?>"><br/>
		Lyrics: <br/><textarea rows="20" cols="150" name="newLyrics"><?php echo $lyrics; ?></textarea><br/>

		<button type="submit" name="applyChanges" value="<?php echo $songId; ?>" class="btn btn-info">Save Changes</button>
		<button type="submit" name="deleteSong" value="<?php echo $songId; ?>" class="btn btn-danger">DELETE SONG</button><br/>
	</form>

</div>

<?php } if ($message != "") { echo "<script type='text/javascript'>$(document).ready(function(){alert('" . $message . "');});</script>"; } ?>

</body>

</html>