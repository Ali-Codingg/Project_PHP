<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Start a new session to store the logout message
session_start();
$_SESSION['logout_success'] = "You have been successfully logged out.";

// Redirect to login page after a short delay
header("Refresh: 3; URL=login.php");
?>
