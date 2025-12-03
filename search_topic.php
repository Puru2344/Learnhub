<?php
include 'config.php';

$q = trim($_GET['q'] ?? '');
if (empty($q)) { echo json_encode(null); exit; }

$sql = "SELECT 
            n.id AS note_id,
            n.topic_name,
            s.name AS subject_name,
            s.year_id
        FROM notes n
        JOIN subjects s ON n.subject_id = s.id
        WHERE s.branch_id = 1
          AND (n.topic_name LIKE ? OR n.content LIKE ?)
        ORDER BY 
            CASE WHEN n.topic_name LIKE ? THEN 0 ELSE 1 END,
            n.topic_name
        LIMIT 1";

$stmt = $conn->prepare($sql);
$search = "%$q%";
$exact = "$q%";
$stmt->bind_param("sss", $search, $search, $exact);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode($row ?: null);
?>