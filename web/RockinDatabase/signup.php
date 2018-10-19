<?php

// Connect to the Database
require "connectDb.php";

if (!isset($_SESSION))
	session_start();

if ($_POST)
{
	if (isset($_POST["submit"]))
	{
		$user = htmlspecialchars($_POST["user"]);
		$pass = htmlspecialchars($_POST["pass"]);

		// See if the username is already taken
		$stmt = $db->prepare('SELECT username FROM users WHERE username=:user');
		$stmt->bindValue(':user', $user, PDO::PARAM_STR);
		$stmt->execute();
		$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// if it is not taken, redirect to the user page
		// and create the account, otherwise reload the page with errors
		if (empty($users) && $user != "" && $pass != "")
		{
			$_SESSION["signUpError"] = false;
			$_SESSION["signUpComplete"] = true;
			// Create the account here

			// And redirect to the user page
			header('Location: user.php');
		}
		else
		{
			$_SESSION["signUpError"] = true;
			$_SESSION["signUpComplete"] = false;
		}
	}
}

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

<div class="container align-middle" align="center">
	<div class="col-sm-4"></div>
	<form class="sm-form col-sm-4" method="post" action="signup.php">
		<h2>Sign Up</h2>
		<p>Create a Username: <input type="text" name="user"></p>
		<p>Create a Password: <input type="text" name="pass"></p>
<?php if (isset($_SESSION["signUpError"]) && $_SESSION["signUpError"]) { ?>
		<p class="text-danger">*Either that username has already been taken or you did not
		enter a Username or Password.</p>
<?php } ?>
		<button class="btn btn-info" type="submit" value="signin" name="submit">Sign-In</button>
		<p><a class="text-info" href="signin.php">Back to Log-In</a></p>
	</form>
	<div class="col-sm-4"></div>
</div>

</body>

</html>