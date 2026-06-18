<?php
session_start();
include 'check_login.php';
include 'header.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$user_name = $_SESSION['user_name'];

$conn = mysqli_connect("localhost", "root", "", "week6_taskmanager");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get statistics based on role
if($role == 'admin') {
    // Admin sees all tasks
    $stats_query = "SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
        (SELECT COUNT(*) FROM users) as total_users
    FROM tasks";
    $tasks_query = "SELECT t.*, u.fullname as user_name, r.role_name 
                    FROM tasks t 
                    JOIN users u ON t.user_id = u.id 
                    JOIN roles r ON u.role_id = r.id 
                    ORDER BY t.created_at DESC";
} elseif($role == 'manager') {
    // Manager sees tasks assigned by them + their own
    $stats_query = "SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
        (SELECT COUNT(*) FROM users WHERE role_id = 3) as total_students
    FROM tasks 
    WHERE user_id = $user_id OR assigned_by = $user_id";
    $tasks_query = "SELECT t.*, u.fullname as user_name, r.role_name 
                    FROM tasks t 
                    JOIN users u ON t.user_id = u.id 
                    JOIN roles r ON u.role_id = r.id 
                    WHERE t.user_id = $user_id OR t.assigned_by = $user_id
                    ORDER BY t.created_at DESC";
} else {
    // Student sees only their own tasks
    $stats_query = "SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending
    FROM tasks WHERE user_id = $user_id";
    $tasks_query = "SELECT t.*, u.fullname as user_name 
                    FROM tasks t 
                    JOIN users u ON t.user_id = u.id 
                    WHERE t.user_id = $user_id
                    ORDER BY t.created_at DESC";
}

$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);
$tasks_result = mysqli_query($conn, $tasks_query);
?>

<div class="dashboard-container">
    <h2>Welcome, <?php echo $user_name; ?>!</h2>
    <p style="margin-bottom:20px;">Role: <strong><?php echo ucfirst($role); ?></strong></p>
    
    <div class="stats-container">
        <div class="stat-card" style="border-bottom:4px solid #667eea;">
            <div class="stat-number"><?php echo $stats['total'] ?? 0; ?></div>
            <div class="stat-label">Total Tasks</div>
        </div>
        <div class="stat-card" style="border-bottom:4px solid #27ae60;">
            <div class="stat-number"><?php echo $stats['completed'] ?? 0; ?></div>
            <div class="stat-label">✅ Completed</div>
        </div>
        <div class="stat-card" style="border-bottom:4px solid #f39c12;">
            <div class="stat-number"><?php echo $stats['pending'] ?? 0; ?></div>
            <div class="stat-label">⏳ Pending</div>
        </div>
        <?php if($role == 'admin'): ?>
        <div class="stat-card" style="border-bottom:4px solid #e74c3c;">
            <div class="stat-number"><?php echo $stats['total_users'] ?? 0; ?></div>
            <div class="stat-label">👥 Total Users</div>
        </div>
        <?php endif; ?>
        <?php if($role == 'manager'): ?>
        <div class="stat-card" style="border-bottom:4px solid #f39c12;">
            <div class="stat-number"><?php echo $stats['total_students'] ?? 0; ?></div>
            <div class="stat-label">📚 Students</div>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="add-task-section">
        <button class="btn-add" onclick="openModal()">➕ ADD NEW TASK</button>
        <?php if($role == 'admin' || $role == 'manager'): ?>
        <a href="assign_task.php" style="background:#27ae60; color:white; padding:12px 25px; border-radius:8px; text-decoration:none;">📤 Assign Task</a>
        <?php endif; ?>
    </div>
    
    <div class="tasks-container">
        <h3>📝 MY TASKS</h3>
        
        <?php if(mysqli_num_rows($tasks_result) > 0): ?>
        <table class="tasks-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Task Title</th>
                    <th>Assigned To</th>
                    <th>Role</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $counter = 1;
                while($task = mysqli_fetch_assoc($tasks_result)): 
                ?>
                <tr>
                    <td><?php echo $counter++; ?></td>
                    <td><strong><?php echo htmlspecialchars($task['title']); ?></strong></td>
                    <td><?php echo $task['user_name']; ?></td>
                    <td><span class="role-badge <?php echo $task['role_name'] ?? 'student'; ?>"><?php echo ucfirst($task['role_name'] ?? 'student'); ?></span></td>
                    <td><?php echo $task['due_date'] ?? 'Not set'; ?></td>
                    <td>
                        <span class="status-badge <?php echo $task['status'] == 'completed' ? 'status-completed' : 'status-pending'; ?>">
                            <?php echo $task['status'] == 'completed' ? '✅ Completed' : '⏳ Pending'; ?>
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; gap:5px; flex-wrap:wrap;">
                            <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn-edit">✏️ Edit</a>
                            <a href="delete_task.php?id=<?php echo $task['id']; ?>" class="btn-delete" onclick="return confirm('Delete this task?')">🗑️ Delete</a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div style="text-align:center; padding:40px; color:#999;">
            <p>📭 No tasks yet.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add Task Modal -->
<div id="taskModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>✚ ADD NEW TASK</h3>
            <button class="close-modal" onclick="closeModal()">&times;</button>
        </div>
        <form action="add_task.php" method="POST">
            <div class="form-group">
                <label>Task Title *</label>
                <input type="text" name="title" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Due Date</label>
                <input type="date" name="due_date">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="pending">⏳ Pending</option>
                    <option value="completed">✅ Completed</option>
                </select>
            </div>
            <button type="submit" class="btn-submit">💾 SAVE TASK</button>
        </form>
    </div>
</div>

<script>
    function openModal() { document.getElementById('taskModal').style.display = 'flex'; }
    function closeModal() { document.getElementById('taskModal').style.display = 'none'; }
    window.onclick = function(event) {
        let modal = document.getElementById('taskModal');
        if (event.target == modal) modal.style.display = 'none';
    }
</script>

</body>
</html>

<?php mysqli_close($conn); ?>