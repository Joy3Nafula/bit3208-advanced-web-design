<?php
session_start();
include 'includes/check_login.php';

if($_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit;
}

require_once 'config/database.php';

$users_query = "SELECT u.*, r.role_name FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.id";
$users_result = mysqli_query($conn, $users_query);

$page_title = "Manage Users";
include 'includes/header.php';
?>

<div class="table-container">
    <h2>👥 Manage Users</h2>
    <div style="margin:15px 0;">
        <a href="register.php" class="btn-success">➕ Add New User</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Role</th>
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
                <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                <td>
                    <a href="profile.php" class="btn-edit">✏️</a>
                    <?php if($user['id'] != $_SESSION['user_id']): ?>
                        <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn-delete" onclick="return confirm('Delete this user?')">🗑️</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; mysqli_close($conn); ?>