<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get user info
$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];

// Database connection
$conn = mysqli_connect("localhost", "root", "", "week3_taskmanager");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get task statistics
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending
FROM tasks WHERE user_id = $user_id";

$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// Get all tasks for this user
$tasks_query = "SELECT * FROM tasks WHERE user_id = $user_id ORDER BY created_at DESC";
$tasks_result = mysqli_query($conn, $tasks_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Task Manager</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Dashboard specific styles */
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Header */
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo h2 {
            margin: 0;
            font-size: 24px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 20px;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s;
        }
        
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        
        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card.total {
            border-bottom: 4px solid #667eea;
        }
        
        .stat-card.completed {
            border-bottom: 4px solid #27ae60;
        }
        
        .stat-card.pending {
            border-bottom: 4px solid #f39c12;
        }
        
        .stat-number {
            font-size: 48px;
            font-weight: bold;
            color: #333;
        }
        
        .stat-label {
            color: #666;
            margin-top: 10px;
            font-size: 16px;
        }
        
        /* Add Task Button */
        .add-task-section {
            margin-bottom: 30px;
            text-align: right;
        }
        
        .btn-add {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .btn-add:hover {
            transform: translateY(-2px);
        }
        
        /* Tasks Table */
        .tasks-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .tasks-container h3 {
            margin-bottom: 20px;
            color: #333;
        }
        
        .tasks-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .tasks-table th,
        .tasks-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .tasks-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #555;
        }
        
        .tasks-table tr:hover {
            background: #f8f9fa;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn-edit {
            background: #667eea;
            color: white;
            padding: 5px 12px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 12px;
        }
        
        .btn-delete {
            background: #e74c3c;
            color: white;
            padding: 5px 12px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 12px;
        }
        
        .btn-edit:hover {
            background: #5a67d8;
        }
        
        .btn-delete:hover {
            background: #c0392b;
        }
        
        .no-tasks {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        
        /* Modal for Add Task */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .modal-content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #999;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        
        .btn-submit {
            background: #27ae60;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            width: 100%;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .tasks-table {
                display: block;
                overflow-x: auto;
            }
            
            .dashboard-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="logo">
                <h2>📋 TASK MANAGER</h2>
            </div>
            <div class="user-info">
                <span>👤 <?php echo $user_name; ?></span>
                <a href="logout.php" class="logout-btn">🚪 Logout</a>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card total">
                <div class="stat-number"><?php echo $stats['total'] ?? 0; ?></div>
                <div class="stat-label">Total Tasks</div>
            </div>
            <div class="stat-card completed">
                <div class="stat-number"><?php echo $stats['completed'] ?? 0; ?></div>
                <div class="stat-label">✅ Completed</div>
            </div>
            <div class="stat-card pending">
                <div class="stat-number"><?php echo $stats['pending'] ?? 0; ?></div>
                <div class="stat-label">⏳ Pending</div>
            </div>
        </div>
        
        <!-- Add Task Button -->
        <div class="add-task-section">
            <button class="btn-add" onclick="openModal()">➕ ADD NEW TASK</button>
        </div>
        
        <!-- Tasks Table -->
        <div class="tasks-container">
            <h3>📝 MY TASKS</h3>
            
            <?php if(mysqli_num_rows($tasks_result) > 0): ?>
            <table class="tasks-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Task Title</th>
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
                        <td>
                            <strong><?php echo htmlspecialchars($task['title']); ?></strong><br>
                            <small style="color:#666;"><?php echo htmlspecialchars(substr($task['description'], 0, 50)); ?></small>
                        </td>
                        <td><?php echo $task['due_date'] ?? 'Not set'; ?></td>
                        <td>
                            <span class="status-badge <?php echo $task['status'] == 'completed' ? 'status-completed' : 'status-pending'; ?>">
                                <?php echo $task['status'] == 'completed' ? '✅ Completed' : '⏳ Pending'; ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn-edit">✏️ Edit</a>
                                <a href="delete_task.php?id=<?php echo $task['id']; ?>" class="btn-delete" onclick="return confirm('Delete this task?')">🗑️ Delete</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="no-tasks">
                <p>📭 No tasks yet. Click "Add New Task" to create your first task!</p>
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
                    <textarea name="description" rows="3" placeholder="Enter task details..."></textarea>
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

    <div style="margin-top: 20px; text-align: center;">
    <a href="welcome.php" style="background:#667eea; color:white; padding:10px 20px; text-decoration:none; border-radius:8px;">🎓 Server-Side Demo</a>
    <a href="simple_form.html" style="background:#27ae60; color:white; padding:10px 20px; text-decoration:none; border-radius:8px;">📝 Form Submission Demo</a>
</div>
    
    <script>
        function openModal() {
            document.getElementById('taskModal').style.display = 'flex';
        }
        
        function closeModal() {
            document.getElementById('taskModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            let modal = document.getElementById('taskModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>

<?php mysqli_close($conn); ?>