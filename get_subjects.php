<?php
include 'config.php';

$branch_id = 1; // CSE Branch ID (change if different)
$year_id = isset($_GET['year_id']) ? intval($_GET['year_id']) : 0;

$sql = "SELECT s.id, s.name 
        FROM subjects s 
        WHERE s.branch_id = $branch_id AND s.year_id = $year_id 
        ORDER BY s.name";

$result = $conn->query($sql);
$subjects = [];
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row;
}

header('Content-Type: application/json');
echo json_encode($subjects);
?>