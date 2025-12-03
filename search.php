<?php
include 'config.php';

$q = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';
if (empty($q)) { echo json_encode([]); exit; }

$sql = "SELECT n.topic_name, n.content, n.image_path, s.name as subject, y.year_num as year
        FROM notes n
        JOIN subjects s ON n.subject_id = s.id
        JOIN years y ON s.year_id = y.id
        WHERE s.branch_id = 1
        AND (n.topic_name LIKE '%$q%' OR n.content LIKE '%$q%')
        ORDER BY y.year_num, s.name, n.topic_name
        LIMIT 20";

$result = $conn->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>