<?php
// Start or resume the session
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect the user to the login page or any other desired page after logout
header('Location: PhysiMonitor.php'); 
exit();
?>
