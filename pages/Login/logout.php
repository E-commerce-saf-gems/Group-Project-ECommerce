<?php
session_start();

session_unset();  // Remove all session variables
session_destroy();  // Destroy the session itself

setcookie(session_name(), '', time() - 3600, '/');  // This clears the session cookie

// Redirect the user to the homepage or login page after logging out
header("Location: ./login.php");  // Redirect to login page (or home page)
exit();
?>
