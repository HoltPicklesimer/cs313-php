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
	<div class="col-sm-4"></div>
	<form class="sm-form col-sm-4" method="post" action="signup.php">
		<h2>Sign Up</h2>
		<p>Create a Username: <input type="text" name="user"></p>
		<p>Create a Password: <input type="text" name="pass"></p>
		<p><a class="text-info" href="signup.php">Don't Have an Account? Click here to sign up!</a></p>
	</form>
	<div class="col-sm-4"></div>
</div>

</body>

</html>