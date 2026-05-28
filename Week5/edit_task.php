<?php
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$task_id = $_GET['id'];

$conn = mysqli_connect("localhost", "root", "", "week3_taskmanager");

// Get task
$query = "SELECT * FROM tasks WHERE id = $task_id AND user_id = $user_id";
$result = mysqli_query($conn, $query);
$task = mysqli_fetch_assoc($result);

if(!$task) {
    header("Location: dashboard.php");
    exit;
}

// Update task
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
               WHERE id = $task_id AND user_id = $user_id";
    
    if(mysqli_query($conn, $update)) {
        header("Location: dashboard.php?success=Task updated");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <a href="dashboard.php" class="btn-back">← Back to Dashboard</a>
        
        <div class="page-card">
            <h2>✏️ Edit Task</h2>
            
            <form method="POST">
                <div class="form-group">
                    <label>Task Title *</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3"><?php echo htmlspecialchars($task['description']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Due Date</label>
                    <input type="date" name="due_date" value="<?php echo $task['due_date']; ?>">
                </div>
                
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="pending" <?php echo $task['status'] == 'pending' ? 'selected' : ''; ?>>⏳ Pending</option>
                        <option value="completed" <?php echo $task['status'] == 'completed' ? 'selected' : ''; ?>>✅ Completed</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-submit">💾 Update Task</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php mysqli_close($conn); ?>