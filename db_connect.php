<?php
// db_connect.php

$host = 'localhost';
$db = 'saide_db'; // Your database name
$user = 'root';     // DB username
$pass = '';         // DB password

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
