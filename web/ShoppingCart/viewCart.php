<?php
	
	session_start();

	print_r($_SESSION);

	class Item{
		function Item($name, $price, $image){
			$this->name = $name;
			$this->price = $price;
			$this->image = $image;
		}
	}
	
	if ($_POST)
	{
	  // Execute code here.
	  if (isset($_POST["remove"])){
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
    <title>View Cart</title>
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
											<form method="post" action="viewCart.php">
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
							<td colspan="3"></td>
						</tr>

						<tr>
							<td colspan="5">
								<?php
									if (isset($_SESSION["cart"])){
										if (count($_SESSION["cart"]) > 0){
											?>
											<a href="browseItems.php" class="button" align="right">Add More Items to Cart</a><br/>
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