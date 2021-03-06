<?php

// Connect to the Database
require "connectDb.php";

if ($_POST)
{
	if (isset($_POST["submit"]))
	{
		$user = htmlspecialchars($_POST["user"]);
		$pass = htmlspecialchars($_POST["pass"]);

		// See if the user and pass match
		$stmt = $db->prepare('SELECT username, password, id FROM users WHERE username=:user');
		$stmt->bindValue(':user', $user, PDO::PARAM_STR);
		$stmt->execute();
		$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$checkPass = $users[0]["password"];

		// if they do, redirect to the next page, otherwise reload the page with errors
		if (!empty($users) && password_verify($pass, $checkPass))
		{
			if (!isset($_SESSION))
				session_start();
			$signInError = false;
			$_SESSION["userId"] = $users[0]["id"];
			header('Location: user.php');
		}
		else
		{
			$signInError = true;
		}
	}
}

?>


<!DOCTYPE html>
<html lang="en">

<head>

  <title>Rockin' Database Sign-In</title>
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

<div class="container-fluid header">
	<h1>My Rockin' Database</h1>
</div>

<div class="container" align="center">
	<h1>Welcome to My Rockin' Database.</h1>
	<h3>Search songs, song reviews, and artists and find music videos!</h3><br/>
	<div class="col-sm-4"></div>
	<form class="sm-form col-sm-4" method="post" action="signin.php">
		<h2>Sign In</h2>
		<p>Username: <input type="text" name="user"></p>
		<p>Password: <input type="password" name="pass"></p>
<?php if (isset($signInError) && $signInError) { ?>
		<p class="text-danger">*Invalid Username and/or Password.</p>
<?php } ?>
		<button class="btn btn-info" type="submit" value="signedin" name="submit">Sign-In</button><br/>
		<p><a class="text-info" href="signup.php" style="color:darkblue">Don't Have an Account? Click here to sign up!</a></p>
	</form>
	<div class="col-sm-4"></div>
</div>

</body>

</html>