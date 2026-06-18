<?php
session_start();
include 'check_login.php';

// Only admin and manager can access this page
if($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'manager') {
    header("Location: dashboard.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "week6_taskmanager");

$page_title = "User Management";
include 'header.php';
?>

<div style="background:white; padding:20px; border-radius:15px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <h2>👥 User Management</h2>
    <p>Role: <strong><?php echo ucfirst($_SESSION['role']); ?></strong></p>
    
    <?php if($_SESSION['role'] == 'admin'): ?>
    <div style="margin: 20px 0;">
        <a href="add_user.php" style="background:#27ae60; color:white; padding:10px 20px; border-radius:8px; text-decoration:none;">➕ Add New User</a>
    </div>
    <?php endif; ?>
    
    <?php
    // Get all users with their roles
    $users_query = "SELECT u.*, r.role_name, 
                    (SELECT COUNT(*) FROM tasks WHERE user_id = u.id) as task_count
                    FROM users u 
                    JOIN roles r ON u.role_id = r.id 
                    ORDER BY u.id";
    $users_result = mysqli_query($conn, $users_query);
    ?>
    
    <table class="tasks-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Tasks</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($user = mysqli_fetch_assoc($users_result)): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><span class="role-badge <?php echo $user['role_name']; ?>"><?php echo ucfirst($user['role_name']); ?></span></td>
                <td><?php echo $user['task_count']; ?></td>
                <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                <td>
                    <?php if($_SESSION['role'] == 'admin'): ?>
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn-edit">✏️ Edit</a>
                        <?php if($user['id'] != $_SESSION['user_id']): ?>
                            <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn-delete" onclick="return confirm('Delete this user?')">🗑️ Delete</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <span style="color:#999;">Read Only</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>