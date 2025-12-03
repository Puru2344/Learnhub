<?php
include 'config.php';

$branch_id = 1;  // CSE
$year_id = intval($_GET['year_id'] ?? 0);

if ($year_id == 0) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT s.id, s.name 
        FROM subjects s 
        WHERE s.branch_id = ? AND s.year_id = ? 
        ORDER BY s.name";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $branch_id, $year_id);
$stmt->execute();
$result = $stmt->get_result();

$subjects = [];
while ($row = $result->fetch_assoc()) {
    $subject_id = $row['id'];

    // Get topics for this subject
    $topic_sql = "SELECT id, topic_name FROM notes WHERE subject_id = ? ORDER BY topic_name";
    $tstmt = $conn->prepare($topic_sql);
    $tstmt->bind_param("i", $subject_id);
    $tstmt->execute();
    $tresult = $tstmt->get_result();

    $topics = [];
    while ($t = $tresult->fetch_assoc()) {
        $topics[] = ['id' => $t['id'], 'topic_name' => $t['topic_name']];
    }
    $tstmt->close();

    $subjects[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'topics' => $topics
    ];
}

header('Content-Type: application/json');
echo json_encode($subjects);
?>