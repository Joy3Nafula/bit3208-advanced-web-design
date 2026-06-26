<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
$page_title = "Login";
include 'includes/header.php';
?>
<div style="max-width:450px; margin:40px auto;">
    <div class="card-with-banner">
        <div class="banner"><h1>🔑 Welcome Back</h1><p>Login to your PropertyPro account</p></div>
        <div class="card-body">
            <?php if(isset($_GET['error'])): ?>
                <div class="alert-error">
                    <?php 
                    $error = $_GET['error'];
                    if($error == 'empty') echo '❌ Email and password are required';
                    elseif($error == 'invalid') echo '❌ Invalid email or password';
                    elseif($error == 'notfound') echo '❌ Email not found. <a href="register.php" style="color:#721c24;">Register here</a>';
                    ?>
                </div>
            <?php endif; ?>
            <form action="process_login.php" method="POST">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="you@example.com" required>
                </div>
                <div class="form-group" style="position: relative;">
                    <label>Password</label>
                    <input type="password" id="loginPassword" name="password" placeholder="Enter your password" required>
                    <span onclick="togglePassword('loginPassword', this)" style="position:absolute; right:15px; top:45px; cursor:pointer; color:#8D6E63; font-size:14px;">👁️</span>
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:10px;">
                    <input type="checkbox" name="remember" id="remember" style="width:auto;">
                    <label for="remember" style="margin:0; font-weight:400;">Remember me for 7 days</label>
                </div>
                <button type="submit" class="btn-primary" style="width:100%;">Login</button>
            </form>
            <div class="link" style="text-align:center; margin-top:20px;">
                Don't have an account? <a href="register.php" style="color:var(--burnt-orange); text-decoration:none; font-weight:600;">Register here</a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId, eyeIcon) {
    const field = document.getElementById(fieldId);
    if (field.type === "password") {
        field.type = "text";
        eyeIcon.textContent = "🙈";
    } else {
        field.type = "password";
        eyeIcon.textContent = "👁️";
    }
}
</script>
<?php include 'includes/footer.php'; ?>