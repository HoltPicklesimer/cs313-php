<?php

	$page = $_SERVER['PHP_SELF'];
?>

<div class='topNav container'>
	<h1>Piano Movers</h1>
	<?php echo $page; ?>
	<a class='<?php if ($page == "siteHome.php") echo active; ?>' href='siteHome.php'>HOME</a>
	<a class='<?php if ($page == "login.php") echo active; ?>' href='login.php'>LOGIN</a>
	<a class='<?php if ($page == "aboutUs.php") echo active; ?>' href='aboutUs.php'>ABOUT</a>
</div>