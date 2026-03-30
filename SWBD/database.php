<?php
// Set up the database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "swbd";

// Create a new connection object
$conn = new mysqli($servername, $username, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the character set
$conn->set_charset("utf8");

?>
