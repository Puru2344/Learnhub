<?php
include 'config.php';
$id = intval($_GET['id'] ?? 0);

$sql = "SELECT topic_name, content, image_path FROM notes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$note = $res->fetch_assoc();

if (!$note) {
    $note = ['topic_name' => 'Not Found', 'content' => 'Topic deleted or not available.', 'image_path' => null];
}

header('Content-Type: application/json');
echo json_encode($note);
?>