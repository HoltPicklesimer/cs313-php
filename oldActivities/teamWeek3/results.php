<html>

	<body>

		<?php
			
			$name = $_POST["name"];
			$email = $_POST["email"];
			$major = $_POST["major"];
			$comment = $_POST["comment"];

			echo "Name: $name<br/>
						mailto: $email<br/>
						Major: $major<br/>
						Comments: $comment<br/>";

			foreach ($_POST["continents"] as $item) {
				echo $item . " ";
			}

		?>

	</body>

</html>