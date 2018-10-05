<?php

	class Item {
		function Item($name, $price, $image) {
			$this->name = $name;
			$this->price = $price;
			$this->image = $image;
		}
	}

	if (!isset($_SESSION))
		session_start();
	
	if ($_POST)
	{
	  // Execute code here.
  	$street = htmlspecialchars($_POST["street"]);
	  $number = htmlspecialchars($_POST["number"]);
	  $city = htmlspecialchars($_POST["city"]);
	  $state = htmlspecialchars($_POST["state"]);
	  $zip = htmlspecialchars($_POST["zip"]);
	  $country = htmlspecialchars($_POST["country"]);
	  // Redirect to this page.
	  header("Location: " . $_SERVER['REQUEST_URI']);
	  exit();
	}

	print_r($_POST);

?>


<!DOCTYPE html>
<html lang="en">

  <head>
    <title>Order Confirmation</title>
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

							<tr><th><h4>Order Complete</h4></th></tr>
							<tr><td>Sent to Address: <?php
							echo $street . " " . $number . "<br/>"
							. $city . ", " . $state . " " . $zip . "<br/>"
							. $country; ?>
							<tr>
								<td>Total:</td>
								<td>$ <?php
								echo money_format('%i', $_SESSION["total"]); ?>
								</td>
							</tr>
							<tr><th><h4>Products Purchased</h4></th></tr>

							<?php
								foreach ($_SESSION["cart"] as $key => $quantity) {
									?>
									<tr>
										<td>
											<img src="<?php echo $_SESSION['items'][$key]->image; ?>"
											class="img-responsive" />
										</td>
										<td><?php echo $_SESSION["items"][$key]->name; ?></td>
										<td>Quantity: </td>
										<td><?php echo $quantity; ?></td>
									</tr>

									<?php
								}
							?>

					</table>
				</div>
			</div>
		</div>

	</body>

</html>