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

$stmt = $db->prepare('SELECT u.id, u.username, s.name, s.contributor_id, s.url,
	s.release_date, s.lyrics, s.artist_id, s.genre_id
	FROM users u JOIN playlistsongs ps ON ps.user_id = u.id JOIN songs s ON s.id = ps.song_id');
$stmt->execute();
$userInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);

$id = $_SESSION["userId"];
// Get the username
$stmt2 = $db->prepare("SELECT username FROM users WHERE id = $id");
$stmt->execute();
$userList = $stmt->fetchAll(PDO::FETCH_ASSOC);

$username = $userList[0]["username"];

print_r($userInfo);

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
	<h1>Welcome <?php echo $username; ?></h1>
</div>

</body>

</html>