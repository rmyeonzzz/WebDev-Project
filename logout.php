<?php
session_start();
// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

// Redirect back to the homepage
header("location: index.php");
exit;
?>