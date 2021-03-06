<?php
	
	// Create Item class and items array to simulate database
	class Item {
		function Item($name, $price, $image) {
			$this->name = $name;
			$this->price = $price;
			$this->image = $image;
		}
	}

	if (!isset($_SESSION))
		session_start();

	if (!isset($_SESSION["items"]))
	{
		$_SESSION["items"] = array(
			1=>new Item("The Avengers", 10.0,
				"images/avengers.jpg", 0),
			2=>new Item("The Avengers: Infinity War", 26.0,
				"images/avengersinfinitywar.jpg"),
			3=>new Item("Back to the Future", 16.0,
				"images/backtothefuture.jpg"),
			4=>new Item("Black Panther", 22.0,
				"images/blackpanther.jpg"),
			5=>new Item("The Incredibles 2", 26.0,
				"images/incredibles2.jpg"),
			6=>new Item("Jaws", 15.0,
				"images/jaws.jpg"),
			7=>new Item("Jurassic Park", 12.0,
				"images/jurassicpark.jpg"),
			8=>new Item("Raiders of the Lost Ark", 17.0,
				"images/raidersofthelostark.jpg"),
			9=>new Item("Star Wars: A New Hope", 18.0,
				"images/starwarsanewhope.jpg"),
			10=>new Item("Star Wars: The Empire Strikes Back", 18.0,
				"images/starwarstheempirestrikesback.jpg"),
			11=>new Item("Star Wars: The Force Awakens", 22.0,
				"images/starwarstheforceawakens.jpg"),
			12=>new Item("Star Wars: The Last Jedi", 20.0,
				"images/starwarsthelastjedi.jpg"),
			13=>new Item("The Dark Knight", 20.0,
				"images/thedarkknight.jpg"),
			14=>new Item("The Godfather", 20.0,
				"images/thegodfather.jpg"),
			15=>new Item("Titanic", 10.0,
				"images/titanic.jpg"),
		);
	}

	if (!isset($_SESSION["cart"]))
		$_SESSION["cart"] = array();
	
	if ($_POST)
	{
	  // Execute code here.
	  if (isset($_POST["add_to_cart"]) && isset($_POST["quantity"]))
		{
			// add quantity of item to cart
			$quantity = htmlspecialchars($_POST["quantity"]);
			if ((int)$quantity == $quantity && (int)$quantity > 0)
				$_SESSION["cart"][htmlspecialchars($_POST["add_to_cart"])] += (int)$quantity;
		}
		else if (isset($_POST["remove"])){
			// remove item from cart
			unset($_SESSION["cart"][htmlspecialchars($_POST["remove"])]);
		}
	  // Redirect to this page.
	  header("Location: " . $_SERVER['REQUEST_URI']);
	  exit();
	}

?>


<!DOCTYPE html>
<html lang="en">

  <head>
    <title>FlickParadise Shopping</title>
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

		<?php include "header.php"; ?>

		<div class="container">
			<h1>Welcome to FlickParadise!</h1>
			<h3>FlickParadise is there for all your movie needs!</h3>
		</div>

		<div class="container">
			<!-- col-sm-4 and col-sm-3 changes width when device gets smaller -->
			<?php
				foreach ($_SESSION["items"] as $key => $selected) {
					?>
					<form method="post" action="browseItems.php">

						<div class="col-sm-4 col-md-3">
							<div class="product">
								<img src="<?php echo $selected->image; ?>" class="img-responsive" />
								<h4 class="text-info"><?php echo $selected->name; ?></h4>
								<h4>$ <?php echo money_format('%i', $selected->price); ?></h4>
								<input type="text" name="quantity" class="form-control" />
								<button type="submit" name="add_to_cart" class="btn btn-info"
								value="<?php echo $key; ?>">Add to Cart</button>
							</div>
						</div>
						
					</form>
					<?php
				}
			?>

			<br/>
			<!-- The total and the list of items is displayed. -->
			<div class="table">
				<div class="table-responsive">
					<table class="table">

						<tr><th colspan="5"><h3>Shopping Cart Details</h3></th></tr>

						<tr>
							<th width="40%">Product</th>
							<th width="10%">Quantity</th>
							<th width="20%">Price</th>
							<th width="15%">Total</th>
							<th width="5%">Action</th>
						</tr>

						<?php
							if (!empty($_SESSION["cart"])){
								$total = 0;
								foreach ($_SESSION["cart"] as $key => $quantity) {
									?>
									<tr>
										<td><?php echo $_SESSION["items"][$key]->name; ?></td>
										<td><?php echo $quantity; ?></td>
										<td>$ <?php echo money_format('%i',
										$_SESSION["items"][$key]->price); ?></td>
										<td>$ <?php echo money_format('%i',
										$quantity * $_SESSION["items"][$key]->price); ?></td>
										<td>
											<form method="post" action="browseItems.php">
												<button type="submit" name="remove" class="btn-danger"
												value="<?php echo $key; ?>">Remove</button>
											</form>
										</td>
									</tr>

									<?php

									$total = $total + ($quantity * $_SESSION["items"][$key]->price);
									$_SESSION["total"] = $total;

								} // end of the foreach
							}
						?>
						<tr>
							<td colspan="3" align="right">Total</td>
							<td align="right">$ <?php echo money_format('%i', $total); ?></td>
						</tr>

						<tr>
							<td colspan="5">
								<?php
									if (isset($_SESSION["cart"])){
										if (count($_SESSION["cart"]) > 0){
											?>
											<a href="viewCart.php" class="button" align="right">View Cart</a><br/>
											<a href="checkout.php" class="button" align="right">Checkout</a>
											<?php
										}
									}
								?>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>

	</body>

</html>