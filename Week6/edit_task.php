<?php
session_start();
include 'check_login.php';

$conn = mysqli_connect("localhost", "root", "", "week6_taskmanager");

$user_id = $_SESSION['user_id'];
$task_id = $_GET['id'];

// Check if user has permission to edit this task
$query = "SELECT * FROM tasks WHERE id = $task_id AND (user_id = $user_id OR assigned_by = $user_id)";
$result = mysqli_query($conn, $query);
$task = mysqli_fetch_assoc($result);

if(!$task) {
    header("Location: dashboard.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];
    
    $update = "UPDATE tasks SET 
               title = '$title', 
               description = '$description', 
               due_date = '$due_date', 
               status = '$status' 
               WHERE id = $task_id AND (user_id = $user_id OR assigned_by = $user_id)";
    
    if(mysqli_query($conn, $update)) {
        header("Location: dashboard.php?success=Task updated");
    }
}

$page_title = "Edit Task";
include 'header.php';
?>

<div style="background:white; padding:30px; border-radius:15px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width:600px; margin:0 auto;">
    <h2>✏️ Edit Task</h2>
    
    <form method="POST">
        <div class="form-group">
            <label>Task Title *</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required style="width:100%; padding:12px; border:2px solid #e0e0e0; border-radius:8px;">
        </div>
        
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3" style="width:100%; padding:12px; border:2px solid #e0e0e0; border-radius:8px;"><?php echo htmlspecialchars($task['description']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Due Date</label>
            <input type="date" name="due_date" value="<?php echo $task['due_date']; ?>" style="width:100%; padding:12px; border:2px solid #e0e0e0; border-radius:8px;">
        </div>
        
        <div class="form-group">
            <label>Status</label>
            <select name="status" style="width:100%; padding:12px; border:2px solid #e0e0e0; border-radius:8px;">
                <option value="pending" <?php echo $task['status'] == 'pending' ? 'selected' : ''; ?>>⏳ Pending</option>
                <option value="completed" <?php echo $task['status'] == 'completed' ? 'selected' : ''; ?>>✅ Completed</option>
            </select>
        </div>
        
        <button type="submit" class="btn-submit">💾 Update Task</button>
    </form>
    
    <div style="margin-top:20px;">
        <a href="dashboard.php" style="color:#667eea; text-decoration:none;">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>