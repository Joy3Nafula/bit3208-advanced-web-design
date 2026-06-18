<?php
session_start();
include 'check_login.php';

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
$role = $_SESSION['role'];

$conn = mysqli_connect("localhost", "root", "", "week6_taskmanager");

$update_success = "";
$update_error = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['update_profile'])) {
        $new_name = mysqli_real_escape_string($conn, $_POST['fullname']);
        $update_query = "UPDATE users SET fullname = '$new_name', profile_updated_at = NOW() WHERE id = $user_id";
        if(mysqli_query($conn, $update_query)) {
            $_SESSION['user_name'] = $new_name;
            $user_name = $new_name;
            $update_success = "Profile updated successfully!";
        }
    }
    
    if(isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        $pass_query = "SELECT password FROM users WHERE id = $user_id";
        $pass_result = mysqli_query($conn, $pass_query);
        $user_data = mysqli_fetch_assoc($pass_result);
        
        if(password_verify($current_password, $user_data['password'])) {
            if($new_password == $confirm_password) {
                if(strlen($new_password) >= 6) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_pass = "UPDATE users SET password = '$hashed_password' WHERE id = $user_id";
                    if(mysqli_query($conn, $update_pass)) {
                        $update_success = "Password changed successfully!";
                    }
                } else {
                    $update_error = "New password must be at least 6 characters";
                }
            } else {
                $update_error = "New passwords do not match";
            }
        } else {
            $update_error = "Current password is incorrect";
        }
    }
}

$page_title = "Profile";
include 'header.php';
?>

<div class="page-card compact">
    <h2>👤 My Profile</h2>
    
    <?php if($update_success): ?>
        <div class="alert-card success">✅ <?php echo $update_success; ?></div>
    <?php endif; ?>
    
    <?php if($update_error): ?>
        <div class="alert-card error">❌ <?php echo $update_error; ?></div>
    <?php endif; ?>
    
    <div class="muted-panel">
        <p><strong>Email:</strong> <?php echo $user_email; ?></p>
        <p><strong>Role:</strong> <span class="role-badge <?php echo $role; ?>"><?php echo ucfirst($role); ?></span></p>
        <p><strong>User ID:</strong> <?php echo $user_id; ?></p>
    </div>
    
    <h3>✏️ Update Profile</h3>
    <form method="POST">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="fullname" value="<?php echo htmlspecialchars($user_name); ?>">
        </div>
        <button type="submit" name="update_profile" class="btn-success">💾 Update Profile</button>
    </form>
    
    <hr class="section-divider">
    
    <h3>🔐 Change Password</h3>
    <form method="POST">
        <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="current_password">
        </div>
        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password">
        </div>
        <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password">
        </div>
        <button type="submit" name="change_password" class="btn-danger">🔄 Change Password</button>
    </form>
    
    <div class="section-spacing">
        <a href="dashboard.php" class="btn-link">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>
