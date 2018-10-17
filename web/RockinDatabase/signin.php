<?php
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

<div class="container" align="center">
	<h1>Welcome to My Rockin' Database.</h1>
	<h3>Search songs, song reviews, and artists and find music videos!</h3>
	<h2>Sign In</h2>
	<form class="sm-form col-sm-4 col-md-3 align-items-center" method="post" action="signin.php">
		<p>Username: <input type="text" name="user"></p>
		<p>Password: <input type="text" name="pass"></p>
		<p><a class="text-info" href="signup.php">Don't Have an Account? Click here to sign up!</a></p>
	</form>
</div>

</body>

</html>