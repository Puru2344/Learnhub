<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM notes WHERE id = $id";
$result = $conn->query($sql);
$note = $result->fetch_assoc();

if (!$note) {
    header("Location: dashboard.php?msg=Note not found");
    exit();
}

// Fetch branches, years (subjects via AJAX)
$branches = $conn->query("SELECT * FROM branches ORDER BY name");
$years = $conn->query("SELECT * FROM years ORDER BY year_num");

// Get current branch/year/subject for pre-selection
$current_subject_query = "SELECT s.branch_id, s.year_id, s.id as subject_id FROM subjects s WHERE s.id = " . $note['subject_id'];
$current = $conn->query($current_subject_query)->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Note</title>
    <style> body { font-family: Arial; margin: 20px; } form { max-width: 600px; margin: 50px auto; padding: 10px; border: 1px solid #ccc; } select, input, textarea { width: 100%; padding: 5px; margin: 5px 0; box-sizing: border-box; } .success { color: green; } .error { color: red; } </style>
</head>
<body>
    <h2>Edit Note</h2>
    <?php if (isset($_GET['msg'])): ?>
        <p class="<?php echo strpos($_GET['msg'], 'Error') === false ? 'success' : 'error'; ?>"><?php echo htmlspecialchars($_GET['msg']); ?></p>
    <?php endif; ?>
    <form action="update_note.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $note['id']; ?>">
        <select name="branch_id" id="branch_id" required onchange="filterSubjects()">
            <option value="">Select Branch</option>
            <?php while($row = $branches->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>" <?php if($row['id'] == $current['branch_id']) echo 'selected'; ?>><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select>
        <select name="year_id" id="year_id" required onchange="filterSubjects()">
            <option value="">Select Year</option>
            <?php $years->data_seek(0); while($row = $years->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>" <?php if($row['id'] == $current['year_id']) echo 'selected'; ?>>Year <?php echo $row['year_num']; ?></option>
            <?php endwhile; ?>
        </select>
        <select name="subject_id" id="subject_id" required>
            <option value="">Select Subject (will filter based on Branch & Year)</option>
        </select>
        <input type="text" name="topic_name" value="<?php echo htmlspecialchars($note['topic_name']); ?>" required>
        <textarea name="content" rows="10" required><?php echo htmlspecialchars($note['content']); ?></textarea>
        <input type="file" name="image" accept="image/*"> (Optional - current: <?php if($note['image_path']) echo htmlspecialchars(basename($note['image_path'])); ?>)
        <input type="submit" value="Update Note">
    </form>
    <a href="dashboard.php">Back to Dashboard</a>

    <script>
        let currentSubjectId = <?php echo $note['subject_id']; ?>;
        
        function filterSubjects() {
            const branchId = document.getElementById('branch_id').value;
            const yearId = document.getElementById('year_id').value;
            const subjectSelect = document.getElementById('subject_id');
            
            subjectSelect.innerHTML = '<option value="">Loading subjects...</option>';
            
            fetch(`get_subjects.php?branch_id=${branchId}&year_id=${yearId}`)
                .then(response => response.json())
                .then(data => {
                    subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                    if (data.length === 0) {
                        const placeholder = document.createElement('option');
                        placeholder.value = '';
                        placeholder.textContent = 'No subjects available for selected Branch & Year';
                        placeholder.disabled = true;
                        subjectSelect.appendChild(placeholder);
                    } else {
                        data.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.id;
                            option.textContent = `${subject.branch} - Year ${subject.year} - ${subject.name}`;
                            if (subject.id == currentSubjectId) {
                                option.selected = true;
                            }
                            subjectSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    subjectSelect.innerHTML = '<option value="">Error loading subjects</option>';
                    console.error('Error:', error);
                });
        }
        
        // Load initial subjects (pre-select current)
        document.addEventListener('DOMContentLoaded', function() {
            filterSubjects();
        });
    </script>
</body>
</html>