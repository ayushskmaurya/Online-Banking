<?php
	session_start();
	session_unset();
	$_SESSION['logout'] = "You have been logged out."; 
	header("Location: index.php");
?>
