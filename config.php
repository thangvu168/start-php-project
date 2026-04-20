<?php
define("DB_SERVER", "127.0.0.1");
define("DB_USERNAME", "admin");
define("DB_PASSWORD", "admin");
define("DB_NAME", "first_app");

// Create connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";
