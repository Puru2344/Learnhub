<?php
$servername = "localhost";  // Change if needed
$username = "root";         // MySQL username
$password = "";             // MySQL password
$dbname = "smart_elearning";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8");
?>