<?php

// Connect to the Database
require "connectDb.php";

require "verifyUser.php";

$message = "";

// Edit the artist if editing and submitted
if ($_POST)
{
	// If the artist is being deleted
	if (isset($_POST["deleteArtist"]))
	{
		// Get the artist id
		$artistId = htmlspecialchars($_POST["deleteArtist"]);
		// Remove from the database if id is not 0
		if ($artistId != 0)
		{
			// Remove all playlist associations to songs by the artist, reviews to songs by the artist,
			// songs by the artist, and the artist themself
			$stmt = $db->prepare('DELETE FROM playlistsongs WHERE song_id = (SELECT id FROM songs WHERE artist_id = :id)');
			$stmt->bindValue(':id', $artistId, PDO::PARAM_INT);
			$stmt->execute();

			$stmt2 = $db->prepare('DELETE FROM reviews WHERE song_id = (SELECT id FROM songs WHERE artist_id = :id)');
			$stmt2->bindValue(':id', $artistId, PDO::PARAM_INT);
			$stmt2->execute();

			$stmt3 = $db->prepare('DELETE FROM songs WHERE artist_id = :id');
			$stmt3->bindValue(':id', $artistId, PDO::PARAM_INT);
			$stmt3->execute();

			$stmt4 = $db->prepare('DELETE FROM artists WHERE id = :id');
			$stmt4->bindValue(':id', $artistId, PDO::PARAM_INT);
			$stmt4->execute();

			$message = 'Artist successfully removed from the Database.';
		}
		// Go into edit mode
		$artistId = 0;
		$edit = 1;
	}
	else
	{
		// Get the artist id, name, and genre given
		$artistId = htmlspecialchars($_POST["applyChanges"]);
		$artistName = htmlspecialchars($_POST["newName"]);
		$artistGenre = htmlspecialchars($_POST["newGenre"]);

		if ($artistName === "") // No name given
		{
			$message = 'Please enter a name for the artist.';
			$edit = 1;
		}
		else if ($artistId == 0) // Adding a new artist
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

				// Get the newly added id
				$stmt3 = $db->prepare("SELECT id FROM artists WHERE name = :name AND genre_id = :genre");
				$stmt3->bindValue(':name', $artistName, PDO::PARAM_STR);
				$stmt3->bindValue(':genre', $artistGenre, PDO::PARAM_INT);
				$stmt3->execute();
				$artistId = $stmt3->fetch(PDO::FETCH_ASSOC)["id"];

				$edit = 0;

				$message = $artistName . ' was added successfully.';
			}
			else // Already in the database
			{
				$message = 'Sorry, ' . $artistName . ' is already in the database.';
				// Continue editing
				$edit = 1;
				$artistId = 0;
			}
		}
		else // Updating an existing artist
		{
			$stmt = $db->prepare("UPDATE artists SET name = :name, genre_id = :genre WHERE id = :id");
			$stmt->bindValue(':name', $artistName, PDO::PARAM_STR);
			$stmt->bindValue(':genre', $artistGenre, PDO::PARAM_INT);
			$stmt->bindValue(':id', $artistId, PDO::PARAM_INT);
			$stmt->execute();

			$edit = 0;
			$message = $artistName . ' was updated successfully.';
		}
	}
}
else
{
	// Get the artist id
	$artistId = htmlspecialchars($_GET["id"]);

	// Editing?
	$edit = htmlspecialchars($_GET["edit"]);
}

if ($artistId > 0)
{

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
		a.genre_id AS genre_id,
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
	$genreId = $artistList[0]["genre_id"];

}

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
	<div class="row">
	<div class="col-sm-9">
	<h3><a href="song.php?id=<?php echo $songId; ?>&edit=0" class="text-info"><?php echo $songName; ?></a></h3>
	</div>
	<div class="col-sm-3"></div>
	</div>
	<p>Rating: <?php echo round($rating) . "/5"; ?><br/>
	Genre: <?php echo $genre; ?><br/>
	Released: <?php echo $releaseDate; ?></p>
<?php if ($url != "") { ?>
	<p><a href="<?php echo $url; ?>" class="text-info" target="_blank">Watch the Music Video</a></p>
<?php }
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
<option value="<?php echo $genreItem['id']; ?>" <?php if ($genreId == $genreItem['id']) echo 'selected="selected"' ?>>
	<?php echo $genreItem["name"]; ?>
</option>
<?php } ?>
		  </select>
		</div>
		<button type="submit" name="applyChanges" value="<?php echo $artistId; ?>" class="btn btn-info">Save Changes</button>
		<button type="submit" name="deleteArtist" value="<?php echo $artistId; ?>" class="btn btn-danger">DELETE ARTIST</button><br/>
	</form>

</div>

<?php } if ($message != "") { echo "<script type='text/javascript'>$(document).ready(function(){alert('" . $message . "');});</script>"; } ?>

</body>

</html>