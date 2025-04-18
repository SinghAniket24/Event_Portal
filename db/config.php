<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "event_portal";

// Connect to database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
