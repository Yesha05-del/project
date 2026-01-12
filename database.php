<?php
// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'desi_routes_india';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check if admin is logged in

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

