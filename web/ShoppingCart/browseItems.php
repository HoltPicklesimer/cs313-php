<!-- The Home Page -->
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

		<?php
			class Item{
				function Item($name, $price, $image){
					$this->name = $name;
					$this->price = $price;
					$this->image = $image;
				}
			}
			$items = array(
				new Item("The Avengers", 10.0,
					"images/avengers.jpg"),
				new Item("The Avengers: Infinity War", 26.0,
					"images/avengersinfinitywar.jpg"),
				new Item("Back to the Future", 16.0,
					"images/backtothefuture.jpg"),
				new Item("Black Panther", 22.0,
					"images/blackpanther.jpg"),
				new Item("The Incredibles 2", 26.0,
					"images/incredibles2.jpg"),
				new Item("Jaws", 15.0, "images/jaws.jpg"),
				new Item("Jurassic Park", 12.0,
					"images/jurassicpark.jpg"),
				new Item("Raiders of the Lost Ark", 17.0,
					"images/raidersofthelostark.jpg"),
				new Item("Star Wars: A New Hope", 18.0,
					"images/starwarsanewhope.jpg"),
				new Item("Star Wars: The Empire Strikes Back", 18.0,
					"images/starwarstheempirestrikesback.jpg"),
				new Item("Star Wars: The Force Awakens", 22.0,
					"images/starwarstheforceawakens.jpg"),
				new Item("Star Wars: The Last Jedi", 20.0,
					"images/starwarsthelastjedi.jpg"),
				new Item("The Dark Knight", 20.0,
					"images/thedarkknight.jpg"),
				new Item("The Godfather", 20.0,
					"images/thegodfather.jpg"),
				new Item("Titanic", 10.0,
					"images/titanic.jpg"),
				);
		?>
	</head>

	<body>

		<div class="container">
			<form method="post" action="total.php">
			<!-- col-sm-4 and col-sm-3 changes width when device gets smaller -->
				<?php
					foreach ($items as $selected) {
						?>
						<div class="col-sm-4 col-md-3">
							<div class="product">
								<img src="<?php echo $selected->image; ?>" class="img-responsive" />
								<h4 class="text-info"><?php echo $selected->name; ?></h4>
								<h4>$ <?php echo money_format('%i', $selected->price); ?></h4>
								<input type="text" name="quantity" class="form-control" value="0" />
								<!-- So we can send the values -->
								<input type="hidden" name="name" value="<?php echo $selected->name; ?>" />
								<input type="hidden" name="price" value="<?php echo $selected->price; ?>" />
								<input type="submit" name="add_to_cart" class="btn btn-info" value="Add to Cart" />
							</div>
						</div>
						<?php
					}
				?>

			</form>
		</div>

	</body>

</html>