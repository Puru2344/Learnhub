<?php
include 'config.php';

$subject_id = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : 0;

$sql = "SELECT topic_name, content, image_path 
        FROM notes 
        WHERE subject_id = $subject_id 
        ORDER BY topic_name";

$result = $conn->query($sql);
$notes = [];
while ($row = $result->fetch_assoc()) {
    $notes[] = $row;
}

header('Content-Type: application/json');
echo json_encode($notes);
?>