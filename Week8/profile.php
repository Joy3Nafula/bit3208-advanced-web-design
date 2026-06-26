<?php
session_start();
include 'includes/check_login.php';
require_once 'config/database.php';

$user_id = $_SESSION['user_id'];
$fullname = $_SESSION['fullname'];
$email = $_SESSION['email'];
$phone = $_SESSION['phone'] ?? '';

$success = '';
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['update_profile'])) {
        $new_name = mysqli_real_escape_string($conn, $_POST['fullname']);
        $new_phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $query = "UPDATE users SET fullname = '$new_name', phone = '$new_phone' WHERE id = $user_id";
        if(mysqli_query($conn, $query)) {
            $_SESSION['fullname'] = $new_name;
            $_SESSION['phone'] = $new_phone;
            $fullname = $new_name;
            $phone = $new_phone;
            $success = "Profile updated successfully!";
        }
    }
    if(isset($_POST['change_password'])) {
        $current = $_POST['current_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];
        $pass_query = "SELECT password FROM users WHERE id = $user_id";
        $pass_result = mysqli_query($conn, $pass_query);
        $user_data = mysqli_fetch_assoc($pass_result);
        if(password_verify($current, $user_data['password'])) {
            if($new == $confirm && strlen($new) >= 6) {
                $hashed = password_hash($new, PASSWORD_DEFAULT);
                mysqli_query($conn, "UPDATE users SET password = '$hashed' WHERE id = $user_id");
                $success = "Password changed successfully!";
            } else {
                $error = "New password must be 6+ characters and match";
            }
        } else {
            $error = "Current password is incorrect";
        }
    }
}

$page_title = "Profile";
include 'includes/header.php';
?>

<div style="max-width:600px; margin:0 auto;">
    <div class="card">
        <h2>👤 My Profile</h2>
        <?php if($success): ?>
            <div class="alert-success">✅ <?php echo $success; ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert-error">❌ <?php echo $error; ?></div>
        <?php endif; ?>
        <div style="padding:15px; background:#f5f0eb; border-radius:8px; margin-bottom:20px;">
            <p><strong>Email:</strong> <?php echo $email; ?></p>
            <p><strong>Role:</strong> <span class="role-badge <?php echo $_SESSION['role']; ?>"><?php echo ucfirst($_SESSION['role']); ?></span></p>
        </div>
        <h3>✏️ Update Profile</h3>
        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
            </div>
            <button type="submit" name="update_profile" class="btn-success">💾 Update</button>
        </form>
        <hr style="margin:30px 0; border:none; border-top:1px solid #eee;">
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
        <div style="margin-top:20px;">
            <a href="dashboard.php" style="color:var(--burnt-orange); text-decoration:none;">← Back to Dashboard</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; mysqli_close($conn); ?>