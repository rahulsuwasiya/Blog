<?php
// logout.php - Logout logic
session_start();
session_destroy();
header("Location: login.php"); // Redirect to login page after logout
exit;
?>
