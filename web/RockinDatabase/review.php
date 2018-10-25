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

// Edit the review if editing and submitted
if ($_POST)
{
	// If the review is being deleted
	if (isset($_POST["deleteReview"]))
	{
		// Get the review id
		$reviewId = htmlspecialchars($_POST["deleteReview"]);
		// Remove from the database if id is not 0
		if ($reviewId > 0)
		{
			// Remove the review from the database
			$stmt = $db->prepare('DELETE FROM reviews WHERE id = :id');
			$stmt->bindValue(':id', $reviewId, PDO::PARAM_INT);
			$stmt->execute();

			$message = 'Your Review was successfully removed from the Database.';
		}
		// Go back to the 
		header('Location: user.php');
	}
	else
	{
		// Get the review information
		$reviewId = htmlspecialchars($_POST["applyChanges"]);
		$reviewRating = htmlspecialchars($_POST["newRating"]);
		$reviewContent = nl2br(htmlspecialchars($_POST["newContent"]));
		$reviewSong = htmlspecialchars($_POST["songId"]);

		if ($reviewId == 0) // Writing a new review
		{
			// Insert the review into the review table
			$stmt = $db->prepare("INSERT INTO reviews (user_id, song_id, publish_date, content, rating)
				VALUES (:user, :song, CURRENT_DATE, :content, :rating)");
			$stmt->bindValue(':user', $id, PDO::PARAM_INT);
			$stmt->bindValue(':song', $reviewSong, PDO::PARAM_INT);
			$stmt->bindValue(':content', $reviewContent, PDO::PARAM_STR);
			$stmt->bindValue(':rating', $reviewRating, PDO::PARAM_INT);
			$stmt->execute();

			// Get the newly added id
			$stmt2 = $db->prepare("SELECT id FROM reviews WHERE user_id = :user AND song_id = :song
				AND content=:content AND rating=:rating");
			$stmt2->bindValue(':user', $id, PDO::PARAM_INT);
			$stmt2->bindValue(':song', $reviewSong, PDO::PARAM_INT);
			$stmt2->bindValue(':content', $reviewContent, PDO::PARAM_STR);
			$stmt2->bindValue(':rating', $reviewRating, PDO::PARAM_INT);
			$stmt2->execute();
			$reviewId = $stmt2->fetch(PDO::FETCH_ASSOC)["id"];

			$edit = 0;

			$message = 'Your review was published successfully.';
		}
		else // Editing an existing review
		{
			$stmt = $db->prepare("UPDATE reviews SET user_id = :user, song_id = :song,
				publish_date = CURRENT_DATE, content = :content, rating = :rating WHERE id = :id");
			$stmt->bindValue(':user', $id, PDO::PARAM_INT);
			$stmt->bindValue(':song', $reviewSong, PDO::PARAM_INT);
			$stmt->bindValue(':content', $reviewContent, PDO::PARAM_STR);
			$stmt->bindValue(':rating', $reviewRating, PDO::PARAM_INT);
			$stmt->bindValue(':id', $reviewId, PDO::PARAM_INT);
			$stmt->execute();

			$edit = 0;
			$message = 'Your review was updated successfully.';
		}
	}
}
else
{
	// Get the review id
	$reviewId = $_GET["id"];

	// Get the song id
	$songId = $_GET["songId"];

	// Editing?
	$edit = $_GET["edit"];
}

if ($reviewId > 0)
{
	$stmt = $db->prepare("SELECT r.user_id, r.publish_date,
		r.content, r.rating, u.username, s.name AS song_name
		FROM reviews r
		JOIN users u ON u.id = r.user_id
		JOIN songs s ON r.song_id = s.id
		WHERE r.id = $reviewId");
	$stmt->execute();
	$reviewList = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$reviewDate = $reviewList[0]["publish_date"];
	$reviewContent = $reviewList[0]["content"];
	$reviewRating = $reviewList[0]["rating"];
	$username = $reviewList[0]["username"];
	$songName = $reviewList[0]["song_name"];
}

?>


<!DOCTYPE html>
<html lang="en">

<head>

  <title>Review Page</title>
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
		<h1>Review for <?php echo $songName; ?></h1>
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
	<a href="review.php?id=<?php echo $reviewId; ?>&edit=1"><span class="text-info">Edit this Review</span></a>

	<p>Published on <?php echo date("F jS, Y", strtotime($reviewDate)); ?><br/>
	Rating: <?php echo $reviewRating . "/5"; ?></p>
	<hr class="style14">
	<p><?php echo $reviewContent; ?></p>

</div>

<?php } else { // The user is editing ?>

<div class="container">
	<br/>
	
	<div class="row">
	<div class="col-sm-7">
	<h1><?php echo "Edit Review for: " . $songName; ?></h1>
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

	<form method="post" action="review.php">

		<div class="form-group">
		  <label for="sel1">Select Rating: </label>
		  <select class="form-control" id="sel1" name="newRating">
				<option value=1>1</option>
				<option value=2>2</option>
				<option value=3>3</option>
				<option value=4>4</option>
				<option value=5>5</option>
		  </select>
		</div>

		Write your review below: <br/><textarea rows="20" cols="150" name="newContent"><?php echo $reviewContent; ?></textarea><br/>

		<input type="hidden" name="songId" value="<?php echo $songId; ?>">

		<button type="submit" name="applyChanges" value="<?php echo $reviewId; ?>" class="btn btn-info">Save Changes</button>
		<button type="submit" name="deleteReview" value="<?php echo $reviewId; ?>" class="btn btn-danger">DELETE REVIEW</button><br/>
	</form>

</div>

<?php } ?>

</body>

</html>