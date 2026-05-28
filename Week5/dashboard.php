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
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 0 30px;
        }

        .dashboard-header {
            background: #ffffff;
            border: 1px solid rgba(46, 61, 78, 0.08);
            border-radius: 18px;
            padding: 28px 32px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 24px;
            box-shadow: 0 20px 50px rgba(46, 61, 78, 0.08);
        }

        .brand-title {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .brand-title h1 {
            margin: 0;
            font-size: 32px;
            letter-spacing: -0.02em;
            color: #1f2937;
        }

        .brand-title p {
            margin: 0;
            color: #5f6e85;
            font-size: 15px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .user-card {
            background: #eef2ff;
            color: #2e3a59;
            border-radius: 12px;
            padding: 12px 18px;
            font-size: 14px;
        }

        .logout-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #3b82f6;
            color: white;
            padding: 12px 22px;
            text-decoration: none;
            border-radius: 10px;
            transition: background 0.25s ease, transform 0.2s ease;
        }

        .logout-btn:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        .page-intro {
            margin-bottom: 24px;
        }

        .intro-card {
            background: #ffffff;
            border: 1px solid rgba(46, 61, 78, 0.08);
            border-radius: 18px;
            padding: 28px 32px;
            box-shadow: 0 18px 40px rgba(46, 61, 78, 0.06);
        }

        .intro-card h2 {
            margin: 0 0 10px;
            font-size: 24px;
            color: #1f2a44;
        }

        .intro-card p {
            margin: 0;
            color: #5f6e85;
            line-height: 1.7;
        }

        .top-panel {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 20px;
            align-items: end;
            margin-bottom: 30px;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 24px 26px;
            box-shadow: 0 14px 30px rgba(46, 61, 78, 0.05);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 40px rgba(46, 61, 78, 0.08);
        }

        .stat-card.total {
            border-left: 4px solid #3b82f6;
        }

        .stat-card.completed {
            border-left: 4px solid #16a34a;
        }

        .stat-card.pending {
            border-left: 4px solid #f59e0b;
        }

        .stat-number {
            font-size: 44px;
            font-weight: 700;
            color: #1f2a44;
        }

        .stat-label {
            color: #5f6e85;
            margin-top: 10px;
            font-size: 15px;
        }

        .dashboard-actions {
            display: flex;
            justify-content: flex-end;
        }

        .btn-add {
            background: #3b82f6;
            color: white;
            padding: 14px 26px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.25s ease, transform 0.2s ease;
        }

        .btn-add:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        .tasks-section {
            margin-bottom: 24px;
        }

        .task-card {
            background: #ffffff;
            border: 1px solid rgba(46, 61, 78, 0.08);
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 16px 40px rgba(46, 61, 78, 0.06);
        }

        .task-card h3 {
            margin: 0 0 20px;
            font-size: 22px;
            color: #1f2a44;
        }

        .tasks-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 680px;
        }

        .tasks-table th,
        .tasks-table td {
            padding: 16px 14px;
            text-align: left;
            border-bottom: 1px solid #e7eaf0;
            vertical-align: middle;
            color: #1f2937;
        }

        .tasks-table th {
            background: #f8fafc;
            font-weight: 700;
            color: #334155;
            letter-spacing: 0.02em;
        }

        .tasks-table tr:hover {
            background: #f3f6fb;
        }

        .task-title {
            display: block;
            color: #111827;
            font-weight: 600;
        }

        .task-description {
            display: block;
            margin-top: 6px;
            color: #6b7280;
            font-size: 13px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-completed {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-edit,
        .btn-delete,
        .footer-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-edit {
            background: #2563eb;
            color: #ffffff;
        }

        .btn-delete {
            background: #ef4444;
            color: #ffffff;
        }

        .btn-edit:hover {
            background: #1d4ed8;
        }

        .btn-delete:hover {
            background: #dc2626;
        }

        .no-tasks {
            text-align: center;
            padding: 40px;
            color: #6b7280;
            font-size: 15px;
        }

        .dashboard-footer {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 18px;
        }

        .footer-link {
            background: #1f2937;
            color: white;
            min-width: 180px;
            text-align: center;
            transition: background 0.25s ease;
        }

        .footer-link:hover {
            background: #111827;
        }

        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            justify-content: center;
            align-items: center;
            z-index: 1000;
            padding: 20px;
        }

        .modal-content {
            background: #ffffff;
            border-radius: 18px;
            padding: 28px;
            width: 100%;
            max-width: 520px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 22px;
        }

        .close-modal {
            background: transparent;
            border: none;
            font-size: 26px;
            cursor: pointer;
            color: #6b7280;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #374151;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            font-size: 15px;
            color: #111827;
        }

        .btn-submit {
            background: #16a34a;
            color: white;
            padding: 14px 16px;
            border: none;
            border-radius: 12px;
            width: 100%;
            cursor: pointer;
            font-size: 15px;
            margin-top: 8px;
        }

        @media (max-width: 960px) {
            .top-panel {
                grid-template-columns: 1fr;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .dashboard-header {
                flex-direction: column;
                align-items: stretch;
                text-align: left;
            }

            .dashboard-actions {
                justify-content: flex-start;
            }

            .tasks-table {
                min-width: 0;
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <div class="brand-title">
                <p>Control Center</p>
                <h1>Task Manager Dashboard</h1>
            </div>
            <div class="header-actions">
                <div class="user-card">
                    <span>Signed in as</span><br>
                    <strong><?php echo htmlspecialchars($user_name); ?></strong>
                </div>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </header>

        <section class="page-intro">
            <div class="intro-card">
                <h2>Welcome back, <?php echo htmlspecialchars($user_name); ?>.</h2>
                <p>Review your task progress, add new items, and keep your workload organized from one central dashboard.</p>
            </div>
        </section>

        <section class="top-panel">
            <div class="stats-container">
                <div class="stat-card total">
                    <div class="stat-number"><?php echo $stats['total'] ?? 0; ?></div>
                    <div class="stat-label">Total Tasks</div>
                </div>
                <div class="stat-card completed">
                    <div class="stat-number"><?php echo $stats['completed'] ?? 0; ?></div>
                    <div class="stat-label">Completed Tasks</div>
                </div>
                <div class="stat-card pending">
                    <div class="stat-number"><?php echo $stats['pending'] ?? 0; ?></div>
                    <div class="stat-label">Pending Tasks</div>
                </div>
            </div>
            <div class="dashboard-actions">
                <button type="button" class="btn-add" onclick="openModal()">Add New Task</button>
            </div>
        </section>

        <section class="tasks-section">
            <div class="task-card">
                <h3>My Tasks</h3>
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
                                <span class="task-title"><?php echo htmlspecialchars($task['title']); ?></span>
                                <span class="task-description"><?php echo htmlspecialchars(substr($task['description'], 0, 60)); ?></span>
                            </td>
                            <td><?php echo $task['due_date'] ? htmlspecialchars($task['due_date']) : 'Not set'; ?></td>
                            <td>
                                <span class="status-badge <?php echo $task['status'] == 'completed' ? 'status-completed' : 'status-pending'; ?>">
                                    <?php echo $task['status'] == 'completed' ? 'Completed' : 'Pending'; ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit_task.php?id=<?php echo urlencode($task['id']); ?>" class="btn-edit">Edit</a>
                                    <a href="delete_task.php?id=<?php echo urlencode($task['id']); ?>" class="btn-delete" onclick="return confirm('Delete this task?')">Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="no-tasks">
                    <p>No tasks yet. Click "Add New Task" to create your first task.</p>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <footer class="dashboard-footer">
            <a href="welcome.php" class="footer-link">Server-Side Demo</a>
            <a href="simple_form.html" class="footer-link">Form Submission Demo</a>
        </footer>
    </div>
    
    <!-- Add Task Modal -->
    <div id="taskModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Task</h3>
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
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-submit">Save Task</button>
            </form>
        </div>
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