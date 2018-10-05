<?php

	if (!isset($_SESSION))
		session_start();
	
	if ($_POST)
	{
	  // Redirect to this page.
	  header("Location: " . $_SERVER['REQUEST_URI']);
	  exit();
	}

?>


<!DOCTYPE html>
<html lang="en">

  <head>
    <title>Checkout</title>
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

		<link rel="stylesheet" href="shop.css">

	</head>

	<body>

		<div class="container">

			<!-- The total and the list of items is displayed. -->
			<div class="table">
				<div class="table-responsive">
					<table class="table">
						<form action="confirm.php" method="post">

							<tr><th><h4>Checkout</h4></th></tr>
							<tr><td>Total:</td><td>$ <?php
							echo money_format('%i', $_SESSION["total"]); ?></td></tr>
							<tr>
								<td>Enter Street Address: </td>
								<td><input type="text" name="street"></td>
							</tr>
							<tr>
								<td>Enter Apartment Number: </td>
								<td><input type="text" name="number"></td>
							</tr>
							<tr>
								<td>Enter City: </td>
								<td><input type="text" name="city"></td>
							</tr>
							<tr>
								<td>Enter State/Province: </td>
								<td><input type="text" name="state"></td>
							</tr>
							<tr>
								<td>Enter Zip Code: </td>
								<td><input type="text" name="zip"></td>
							</tr>
							<tr>
								<td>Enter Country: </td>
								<td><input type="text" name="country"></td>
							</tr>

							<tr>
								<td><a href="browseItems.php" class="button"
									align="right">Return to Cart</a></td>
								<td align="right">
									<button type="submit" name="checkout" class="btn-success"
										value="<?php echo $key; ?>">Checkout</button>
								</td>
							</tr>

						</form>
					</table>
				</div>
			</div>
		</div>

	</body>

</html>