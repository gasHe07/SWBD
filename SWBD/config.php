<?php
// Start or resume the session
session_start();

// Set the session cookie to last for one week
session_set_cookie_params(7 * 24 * 60 * 60, '/');

// Set the session timeout to one week 
ini_set('session.gc_maxlifetime', 7 * 24 * 60 * 60);
?>
