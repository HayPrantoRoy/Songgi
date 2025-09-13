<?php
$host = "localhost";   // Database host
$user = "root";        // Database username
$pass = "";            // Database password
$db   = "songgi_db"; // Replace with your database name

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
