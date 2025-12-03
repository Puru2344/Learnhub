<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $subject_id = intval($_POST['subject_id']);
    $topic_name = mysqli_real_escape_string($conn, $_POST['topic_name']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $image_path = NULL; // Will update if new image
    
    // Get current image to keep if no new
    $current = $conn->query("SELECT image_path FROM notes WHERE id = $id")->fetch_assoc()['image_path'];
    
    // Handle new image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . time() . "_" . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        if (in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif']) && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
            // Delete old image if exists
            if ($current && file_exists($current)) unlink($current);
        }
    } else {
        $image_path = $current;
    }
    
    $img_sql = $image_path ? ", image_path = '$image_path'" : ", image_path = NULL";
    $sql = "UPDATE notes SET subject_id = $subject_id, topic_name = '$topic_name', content = '$content' $img_sql WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        $message = "Note updated successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

header("Location: dashboard.php?msg=" . urlencode($message));
exit();
?>