<html>

	<body>

		<?php

			$continents = $_POST["continents"];
			
			$name = $_POST["name"];
			$email = $_POST["email"];
			$major = $_POST["major"];
			$comment = $_POST["comment"];

			echo "Name: $name<br/>
						mailto: $email<br/>
						Major: $major<br/>
						Comments: $comment<br/>";

			var_dump($continents);

			if (!isempty($continents){
				foreach ($continents as $c) {
					echo $c . " ";
				}
			}

		?>

	</body>

</html>