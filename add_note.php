<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_id = intval($_POST['subject_id']);
    $topic_name = mysqli_real_escape_string($conn, $_POST['topic_name']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $image_path = NULL;
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . time() . "_" . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Basic validation (allow jpg, png, gif)
        if (in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            }
        }
    }
    
    $sql = "INSERT INTO notes (subject_id, topic_name, content, image_path) VALUES ($subject_id, '$topic_name', '$content', " . ($image_path ? "'$image_path'" : "NULL") . ")";
    
    if ($conn->query($sql) === TRUE) {
        $message = "Note added successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

header("Location: dashboard.php?" . ($message ? "msg=" . urlencode($message) : ""));
exit();
?>