<!DOCTYPE html>
<html lang="en">

  <head>
    <title>Ethan Picklesimer Home</title>
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

		<script src="home.js"></script>

		<link rel="stylesheet" href="home.css">
	</head>

	<body>

		<?php include 'heading.php'; ?>

		<div class="container-fluid bg-1 text-center">
			<h2>Ethan Picklesimer Home</h2>
			<img src="me.jpg" class="img-circle img-25 img-responsive"
			alt="Ethan Picklesimer" title="Ethan Picklesimer" id="pic"
			onmouseover="enlarge()" onmouseleave="shrink()">
			<h3>Welcome to my CS 313 Home Page</h3>
		</div>

		<div class="container-fluid bg-2 text-center" id="about">
			<h3>About Me</h3>
			<p class="container">Hello, I'm Ethan Picklesimer. I grew up in Ohio and I
			am studying Computer Science at Brigham Young University - Idaho.
			I became interested in programming in high school , taking an
			introductory class in Java and also experimenting in a simple game
			creation, Game Maker. I especially enjoy app and web development
			classes and seek to obtain a programming intership. In my free time,
			I exercise, learn the guitar, sketch, and work on side projects in
			Unity, programming in C#.</p>
		</div>

		<div class="container-fluid bg-3 text-center" id="skills">
			<h3>Skills</h3>
			<p>C++, Java, HTML, CSS, Javascript, PHP, Android Studio,
			Working in a Team, GitHub</p>
			<p>Familiar with: C#, Python, SQL</p>
		</div>

		<form class="container-fluid bg-3 text-center" onreset="clearErrors()">
			<p>Enter "hello" into username and "world"
			into password to see the secret message.</p>
			<p>
				Enter Username:<br>
				<input type="text" id="user"><br>
				<span id="userError" class="error">Incorrect Username</span><br>
				Enter Password:<br>
				<input type="text" id="pass"><br>
				<span id="passError" class="error">Incorrect Password</span><br>
				<input type="reset" onclick="clearErrors()">
				<input type="button" onclick="display()">
				Secret Message:<br>
				<div id="secret">
					"Without hard work, nothing grows but weeds."<br>
					<i>- Gordon B. Hinckley</i><br>
				</div>
			</p>
		</form>

		<?php include 'bottom.php'; ?>

	</body>

</html>