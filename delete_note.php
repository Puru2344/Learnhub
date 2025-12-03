<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id']);

// Delete image if exists
$image = $conn->query("SELECT image_path FROM notes WHERE id = $id")->fetch_assoc()['image_path'];
if ($image && file_exists($image)) unlink($image);

$sql = "DELETE FROM notes WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    $message = "Note deleted successfully!";
} else {
    $message = "Error: " . $conn->error;
}

header("Location: dashboard.php?msg=" . urlencode($message));
exit();
?>