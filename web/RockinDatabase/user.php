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

$stmt = $db->prepare("
	SELECT s.name AS song_name, s.url, s.id AS song_id, s.release_date,
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
	
	<div class="col-sm-8">
		<h1>Welcome <?php echo $username; ?></h1>
		<br/>
		<h2>Your Playlist:</h2>
	</div>
	<div class="col-sm-4">
		Search Songs and Artists: <input type="text" name="searchSongs"><br/>
		<a href="song.php"><span class="text-info">Add a New Song to the Database</span></a></br>
		<a href="artist.php"><span class="text-info">Add a New Artist to the Database</span></a>
	</div>
</div>
	
<div class="container">

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
?>
	<hr class="style14">
	<h3><a href="song.php?id=<?php echo $songId; ?>" class="text-info"><?php echo $songName; ?></a> by
	<a href="song.php?id=<?php echo $artistId; ?>" class="text-info"><?php echo $artistName; ?></a></h3>
	<p>Genre: <?php echo $genre; ?>
	<?php echo $releaseDate; ?></p>
<?php if ($url != "") { ?>
	<p><a href="<?php echo $url; ?>" class="text-info" target="_blank">Watch the Music Video</a></p>
<?php } ?>
	<p>Lyrics:<br/><?php echo $lyrics; ?></p>
<?php
}
?>

</div>

</body>

</html>