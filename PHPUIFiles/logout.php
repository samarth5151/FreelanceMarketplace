<?php
session_start(); // Start the session

$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page or home page
header("Location: login.php"); // Change "login.php" to your desired destination
exit();
?>