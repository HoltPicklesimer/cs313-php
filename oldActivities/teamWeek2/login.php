<!DOCTYPE html>
<html lang="en">

  <head>
    <title>Piano Movers Login</title>
  	<meta charset="utf-8" />
  	<meta name="viewport" content="width=device-width, initial-scale=1" />

  	<!-- Latest compiled and minified CSS for Bootstrap -->
		<link rel="stylesheet"
		href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

		<link rel="stylesheet"
		href="team2.css"/>

		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
		</script>

		<!-- Latest compiled JavaScript for Bootstrap -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js">
		</script>
	</head>

	<body>

		<?php
			include 'header.php';
		?>

		<div class="container">
			<h1>LOGIN</h1>
		</div>

		<div class="container">
			<p>Welcome, you are not logged in.</p>
		</div>

		<button type="button" value="admin" onclick="admin.php">Login as Administrator</button>
		<button type="button" value="tester" onclick="user.php">Login as Tester</button>


	</body>

</html>