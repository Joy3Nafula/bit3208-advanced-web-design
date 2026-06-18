<?php
session_start();
include 'check_login.php';

// Only admin and manager can assign tasks
if($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'manager') {
    header("Location: dashboard.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "week6_taskmanager");

$page_title = "Assign Task";
include 'header.php';

// Get all students (role_id = 3)
$students_query = "SELECT id, fullname, email FROM users WHERE role_id = 3 ORDER BY fullname";
$students_result = mysqli_query($conn, $students_query);

// Handle task assignment
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $assigned_by = $_SESSION['user_id'];
    
    $query = "INSERT INTO tasks (user_id, title, description, due_date, assigned_by) 
              VALUES ('$user_id', '$title', '$description', '$due_date', '$assigned_by')";
    
    if(mysqli_query($conn, $query)) {
        echo '<div style="background:#d4edda; color:#155724; padding:15px; border-radius:8px; margin-bottom:20px;">✅ Task assigned successfully!</div>';
    } else {
        echo '<div style="background:#f8d7da; color:#721c24; padding:15px; border-radius:8px; margin-bottom:20px;">❌ Error: ' . mysqli_error($conn) . '</div>';
    }
}
?>

<div style="background:white; padding:30px; border-radius:15px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width:600px; margin:0 auto;">
    <h2>📤 Assign Task</h2>
    <p>Assign a task to a student</p>
    
    <form method="POST">
        <div class="form-group">
            <label>Select Student *</label>
            <select name="user_id" required style="width:100%; padding:12px; border:2px solid #e0e0e0; border-radius:8px;">
                <option value="">-- Select Student --</option>
                <?php while($student = mysqli_fetch_assoc($students_result)): ?>
                <option value="<?php echo $student['id']; ?>">
                    <?php echo htmlspecialchars($student['fullname']); ?> (<?php echo $student['email']; ?>)
                </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Task Title *</label>
            <input type="text" name="title" required style="width:100%; padding:12px; border:2px solid #e0e0e0; border-radius:8px;">
        </div>
        
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3" style="width:100%; padding:12px; border:2px solid #e0e0e0; border-radius:8px;"></textarea>
        </div>
        
        <div class="form-group">
            <label>Due Date</label>
            <input type="date" name="due_date" style="width:100%; padding:12px; border:2px solid #e0e0e0; border-radius:8px;">
        </div>
        
        <button type="submit" class="btn-submit">📤 Assign Task</button>
    </form>
    
    <div style="margin-top:20px;">
        <a href="dashboard.php" style="color:#667eea; text-decoration:none;">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>