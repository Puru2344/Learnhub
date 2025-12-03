<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $branch_id = intval($_POST['branch_id']);
    $year_id = intval($_POST['year_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    
    $sql = "INSERT INTO subjects (branch_id, year_id, name) VALUES ($branch_id, $year_id, '$name')";
    
    if ($conn->query($sql) === TRUE) {
        $message = "Subject added successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

header("Location: dashboard.php?" . ($message ? "msg=" . urlencode($message) : ""));
exit();
?>