<?php
include 'config.php';

$q = strtolower(trim($_GET['q'] ?? ''));

$sql = "SELECT s.id AS subject_id, s.name AS subject_name, s.year_id 
        FROM subjects s 
        WHERE s.branch_id = 1 AND LOWER(s.name) LIKE ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$search = "%$q%";
$stmt->bind_param("s", $search);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    echo json_encode($row);
} else {
    echo json_encode(null);
}
?>