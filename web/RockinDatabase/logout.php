<?php

session_destroy();
$_SESSION = [];
header('Location: signin.php');

?>