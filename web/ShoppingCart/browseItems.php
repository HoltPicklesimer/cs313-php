<?php
	
	// Create Item class and shopping_cart array to simulate database
	class Item{
		function Item($name, $price, $image){
			$this->name = $name;
			$this->price = $price;
			$this->image = $image;
			$this->quantity = 0;
		}
	}

	if (!isset($_SESSION))
		session_start();

	if (isset($_SESSION["shopping_cart"])){
		$count = count($_SESSION["shopping_cart"]);
	}else{
		echo "created shopping_cart";
		$_SESSION["shopping_cart"][0] = array(
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

	if($_POST['button1']){fun1();}
	if($_POST['button2']){fun2();}

	print_r($_SESSION);
	echo "<br/>";
	print_r($_POST);

	echo $_POST["quantity" . str_replace(' ', '_', "The Avengers")];
	echo $_SESSION["index"];

	function fun1()
	{
		echo "fun1          " . $_SESSION["shopping_cart"][0][$_SESSION["index"]]->quantity . "                   ";
		$_SESSION["shopping_cart"][0][$_POST["index"]]->quantity = $_POST["quantity" . str_replace(' ', '_', $selected->name)];
	}
	function fun2()
	{
	 //This function will update some column in database to 2
	}

?>


<!DOCTYPE html>
<html lang="en">

  <head>
    <title>Shopping Cart</title>
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
			<form method="post" action="browseItems.php">
			<!-- col-sm-4 and col-sm-3 changes width when device gets smaller -->
				<?php
					foreach ($_SESSION["shopping_cart"][0] as $key => $selected) {
						?>
						<div class="col-sm-4 col-md-3">
							<div class="product">
								<img src="<?php echo $selected->image; ?>" class="img-responsive" />
								<h4 class="text-info"><?php echo $selected->name; ?></h4>
								<h4>$ <?php echo money_format('%i', $selected->price); ?></h4>
								<input type="text" name="quantity<?php echo $selected->name; ?>" class="form-control" value="0" />
								<input type="submit" name="add_to_cart" class="btn btn-info"
								value="Add to Cart" onclick=location.href="?button2=1&index=<?php echo $key; ?>" />
								<?php echo $selected->quantity; ?>
							</div>
						</div>
						<?php
					}
				?>

			</form>
			<br/>
			<!-- The total and the list of items is displayed. -->
			<div class="table">
				<div class="table-responsive">
					<table class="table">

						<tr><th colspan="5"><h3>Checkout Details</h3></th></tr>

						<tr>
							<th width="40%">Product</th>
							<th width="10%">Quantity</th>
							<th width="20%">Price</th>
							<th width="15%">Total</th>
							<th width="5%">Action</th>
						</tr>

						<?php
							if (!empty($_SESSION["shopping_cart"])){
								$total = 0;
								foreach ($_SESSION["shopping_cart"] as $selected) {
									?>
									<tr>
										<td><?php echo $selected["name"]; ?></td>
										<td><?php echo $selected["quantity"]; ?></td>
										<td>$ <?php echo $selected["price"]; ?></td>
										<td>$ <?php echo money_format('%i',
										$selected["quantity"] * $selected["price"]); ?></td>
										<td>
											<input type="button" name="remove <?php echo $selected["name"]; ?>"
											class="btn-danger" value="Remove" />
										</td>
									</tr>

									<?php

									$total = $total + ($selected["quantity"] * $selected["price"]);

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
									if (isset($_SESSION["shopping_cart"])){
										if (count($_SESSION["shopping_cart"] > 0)){
											?>
											<a href="#" class="button" align="right">Checkout</a>
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