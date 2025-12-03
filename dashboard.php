<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch branches, years for dropdowns (subjects via AJAX)
$branches = $conn->query("SELECT * FROM branches ORDER BY name");
$years = $conn->query("SELECT * FROM years ORDER BY year_num");

// Fetch all notes for listing (fixed alias: subjects -> subject)
$notes_sql = "SELECT n.id, n.topic_name, n.content, n.image_path, n.created_at, s.name as subject, b.name as branch, y.year_num as year 
              FROM notes n 
              JOIN subjects s ON n.subject_id = s.id 
              JOIN branches b ON s.branch_id = b.id 
              JOIN years y ON s.year_id = y.id 
              ORDER BY n.created_at DESC";
$notes = $conn->query($notes_sql);

// Handle message display
$message = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Smart E-Learning</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        form { margin-bottom: 20px; padding: 10px; border: 1px solid #ccc; }
        select, input, textarea { width: 100%; padding: 5px; margin: 5px 0; box-sizing: border-box; }
        table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        img { max-width: 100px; height: auto; }
        .delete { color: red; cursor: pointer; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <a href="logout.php">Logout</a>
    <?php if ($message): ?>
        <p class="<?php echo strpos($message, 'Error') === false ? 'success' : 'error'; ?>"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <h2>Add Subject</h2>
    <form action="add_subject.php" method="POST">
        <select name="branch_id" required>
            <option value="">Select Branch</option>
            <?php $branches->data_seek(0); while($row = $branches->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select>
        <select name="year_id" required>
            <option value="">Select Year</option>
            <?php $years->data_seek(0); while($row = $years->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>">Year <?php echo $row['year_num']; ?></option>
            <?php endwhile; ?>
        </select>
        <input type="text" name="name" placeholder="Subject Name" required>
        <input type="submit" value="Add Subject">
    </form>

    <h2>Add Note/Topic</h2>
    <form action="add_note.php" method="POST" enctype="multipart/form-data">
        <select name="branch_id" id="branch_id" required onchange="filterSubjects()">
            <option value="">Select Branch</option>
            <?php $branches->data_seek(0); while($row = $branches->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select>
        <select name="year_id" id="year_id" required onchange="filterSubjects()">
            <option value="">Select Year</option>
            <?php $years->data_seek(0); while($row = $years->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>">Year <?php echo $row['year_num']; ?></option>
            <?php endwhile; ?>
        </select>
        <select name="subject_id" id="subject_id" required>
            <option value="">Select Subject (will filter based on Branch & Year)</option>
        </select>
        <input type="text" name="topic_name" placeholder="Topic Name" required>
        <textarea name="content" placeholder="Content (Unlimited text)" rows="10" required></textarea>
        <input type="file" name="image" accept="image/*">
        <input type="submit" value="Add Note">
    </form>

    <h2>Manage Notes</h2>
    <table>
        <tr><th>ID</th><th>Branch</th><th>Year</th><th>Subject</th><th>Topic</th><th>Content Preview</th><th>Image</th><th>Created</th><th>Actions</th></tr>
        <?php $notes->data_seek(0); while($row = $notes->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['branch']); ?></td>
                <td><?php echo $row['year']; ?></td>
                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                <td><?php echo htmlspecialchars($row['topic_name']); ?></td>
                <td><?php echo htmlspecialchars(substr($row['content'], 0, 100)) . '...'; ?></td>
                <td><?php if($row['image_path']): ?><img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Note Image"><?php endif; ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <a href="edit_note.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                    <a href="delete_note.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
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
                            subjectSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    subjectSelect.innerHTML = '<option value="">Error loading subjects</option>';
                    console.error('Error:', error);
                });
        }
        
        // Load all subjects initially
        document.addEventListener('DOMContentLoaded', function() {
            filterSubjects();
        });
    </script>
</body>
</html>