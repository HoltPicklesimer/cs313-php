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
	if (isset($_POST["deleteArtist"]))
	{
		// Get the artist id
		$artistId = $_POST["deleteArtist"];
		// Remove from the database if id is not 0
		// Then redirect to the user's page
	}
	else
	{
		// Get the artist id, name, and genre given
		$artistId = htmlspecialchars($_POST["applyChanges"]);
		$artistName = htmlspecialchars($_POST["newName"]);
		$artistGenre = htmlspecialchars($_POST["newGenre"]);

		// Editing or adding a new artist?
		if ($artistId == 0) // Adding a new artist
		{
			// Make sure the artist is not already in the database
			$stmt = $db->prepare('SELECT id FROM artists WHERE name = :name');
			$stmt->bindValue(':name', $artistName, PDO::PARAM_STR);
			$stmt->execute();
			$artistList = $stmt->fetchAll(PDO::FETCH_ASSOC);

			if (empty($artistList)) // Not taken, add to database
			{
				$stmt2 = $db->prepare("INSERT INTO artists (name, genre_id, contributor_id) VALUES (:name, :genre, $id)");
				$stmt2->bindValue(':name', $artistName, PDO::PARAM_STR);
				$stmt2->bindValue(':genre', $artistGenre, PDO::PARAM_INT);
				$stmt2->execute();
				echo '<script type="text/javascript">alert("' . $artistName . 'was added successfully.");</script>';
			}
			else
			{
				echo '<script type="text/javascript">alert("Sorry, ' . $artistName . ' is already in the database.");</script>';
			}
		}
		else // Updating an existing artist
		{
			$artistId = $artistList[0]["id"];
			$stmt3 = $db->prepare("UPDATE artists SET name = :name, genre_id = :genre WHERE id = :id");
			$stmt3->bindValue(':name', $artistName, PDO::PARAM_STR);
			$stmt3->bindValue(':genre', $artistGenre, PDO::PARAM_INT);
			$stmt3->bindValue(':id', $artistId, PDO::PARAM_INT);
			$stmt3->execute();
			echo '<script type="text/javascript">alert("' . $artistName . ' was updated successfully.");</script>';
		}
		// Redirect
		header("Location: artist.php?id=" . $artistId . "&edit=0");
	}
}
else
{
	// Get the artist id
	$artistId = $_GET["id"];

	// Editing?
	$edit = $_GET["edit"];
}

$stmt = $db->prepare("
	SELECT s.name AS song_name, s.url, s.id AS song_id, s.release_date,
	s.lyrics, g.name AS genre_name, a.name AS artist_name, a.id AS artist_id
	FROM artists a
	JOIN songs s ON a.id = s.artist_id
	JOIN genres g ON s.genre_id = g.id
	WHERE a.id = $artistId");
$stmt->execute();
$playlist = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the contributor and genre
$stmt2 = $db->prepare("
	SELECT a.name AS name,
	u.username AS user,
	g.name AS genre
	FROM artists a
	JOIN users u ON u.id = a.contributor_id
	JOIN genres g ON g.id = a.genre_id
	WHERE a.id = $artistId");
$stmt2->execute();
$artistList = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$artistName = $artistList[0]["name"];
$artistCon = $artistList[0]["user"];
$artistGenre = $artistList[0]["genre"];

// Get all genres
$stmt3 = $db->prepare("SELECT id, name FROM genres");
$stmt3->execute();
$genreList = $stmt3->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">

<head>

  <title>Artist Page</title>
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
		<h1><?php echo $artistName; ?></h1>
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
	<p>Genre: <?php echo $artistGenre; ?><br/>
	<p>Contributed by <?php echo $artistCon; ?></p>
	<a href="artist.php?id=<?php echo $artistId; ?>&edit=1"><span class="text-info">Edit this Artist</span></a>
	<h2>Songs by this Artist:</h2>

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
	<div class="col-sm-9">
	<h3><a href="song.php?id=<?php echo $songId; ?>&edit=0" class="text-info"><?php echo $songName; ?></a></h3>
	</div>
	<div class="col-sm-3"></div>
	<br/>
	<br/>
	<br/>
	<p>Rating: <?php echo round($rating) . "/5"; ?><br/>
	Genre: <?php echo $genre; ?><br/>
	Released: <?php echo $releaseDate; ?></p>
<?php if ($url != "") { ?>
	<p><a href="<?php echo $url; ?>" class="text-info" target="_blank">Watch the Music Video</a></p>
<?php } ?>
	<p>Lyrics:<br/><?php echo $lyrics; ?></p>
<?php
}
?>

</div>

<?php } else { // The user is editing ?>

<div class="container">
	<br/>
	
	<div class="row">
	<div class="col-sm-7">
<?php if ($artistId != 0) { ?>
		<h1><?php echo "Edit Page for: " . $artistName; ?></h1>
<?php } else { ?>
		<h1>Add Artist</h1>
<?php } ?>
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

	<form method="post" action="artist.php">
		Artist Name: <input type="text" name="newName" size="50" value="<?php echo $artistName; ?>"><br/>
		<div class="form-group">
		  <label for="sel1">Select Genre</label>
		  <select class="form-control" id="sel1" name="newGenre">
<?php foreach ($genreList as $genreItem) { ?>
<option value="<?php echo $genreItem['id']; ?>"><?php echo $genreItem["name"]; ?></option>
<?php } ?>
		  </select>
		</div>
		<button type="submit" name="applyChanges" value="<?php echo $artistId; ?>" class="btn btn-info">Save Changes</button>
		<button type="submit" name="deleteArtist" value="<?php echo $artistId; ?>" class="btn btn-danger">DELETE ARTIST</button><br/>
	</form>

</div>

<?php } ?>

</body>

</html>