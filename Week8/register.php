<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
$page_title = "Register";
include 'includes/header.php';
?>
<div style="max-width:520px; margin:40px auto;">
    <div class="card-with-banner">
        <div class="banner"><h1>📝 Create Account</h1><p>Join PropertyPro today</p></div>
        <div class="card-body">
            <form id="registerForm" action="process_registration.php" method="POST">
                <div class="form-group">
                    <label>Full Name <span class="required">*</span></label>
                    <input type="text" id="fullname" name="fullname" placeholder="Enter your full name" required>
                    <div class="error-message" id="nameError">Please enter your full name</div>
                </div>
                <div class="form-group">
                    <label>Email Address <span class="required">*</span></label>
                    <input type="email" id="email" name="email" placeholder="you@example.com" required>
                    <div class="error-message" id="emailError">Please enter a valid email address</div>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" placeholder="0712345678">
                </div>
                <div class="form-group" style="position: relative;">
                     <label>Password <span class="required">*</span></label>
                     <input type="password" id="password" name="password" placeholder="Min 6 characters" required>
                     <span onclick="togglePassword('password', this)" style="position:absolute; right:15px; top:45px; cursor:pointer; color:#8D6E63; font-size:14px;">👁️</span>
                     <div class="password-strength" id="strengthBar"></div>
                     <div class="strength-text" id="strengthText"></div>
                     <div class="error-message" id="passwordError">Password must be at least 6 characters</div>
                </div>
                <div class="form-group" style="position: relative;">
                    <label>Confirm Password <span class="required">*</span></label>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Re-enter password" required>
                    <span onclick="togglePassword('confirmPassword', this)" style="position:absolute; right:15px; top:45px; cursor:pointer; color:#8D6E63; font-size:14px;">👁️</span>
                    <div class="error-message" id="confirmError">Passwords do not match</div>
                </div>
                <div class="form-group">
                    <label>Select Role</label>
                    <select name="role_id">
                        <option value="3">Tenant/Buyer</option>
                        <option value="2">Agent</option>
                        <option value="1">Admin</option>
                    </select>
                    <small style="color:#8D6E63; display:block; margin-top:5px;">Choose your role carefully</small>
                </div>
                <button type="submit" class="btn-primary" style="width:100%;">Register</button>
            </form>
            <div class="link" style="text-align:center; margin-top:20px;">
                Already have an account? <a href="login.php" style="color:var(--burnt-orange); text-decoration:none; font-weight:600;">Login here</a>
            </div>
        </div>
    </div>
</div>
<script>
const form = document.getElementById('registerForm');
const fullname = document.getElementById('fullname');
const email = document.getElementById('email');
const password = document.getElementById('password');
const confirmPassword = document.getElementById('confirmPassword');

fullname.addEventListener('blur', function() {
    document.getElementById('nameError').style.display = this.value.trim() === '' ? 'block' : 'none';
});
email.addEventListener('blur', function() {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    document.getElementById('emailError').style.display = !emailRegex.test(this.value) ? 'block' : 'none';
});
password.addEventListener('keyup', function() {
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    const value = this.value;
    if(value.length === 0) {
        strengthBar.className = 'password-strength';
        strengthText.textContent = '';
        strengthText.className = 'strength-text';
        return;
    }
    let strength = 'weak';
    let message = '❌ Weak - need at least 6 characters';
    let className = 'weak';
    if(value.length >= 8 && /[A-Z]/.test(value) && /[0-9]/.test(value)) {
        strength = 'strong';
        message = '✓ Strong password!';
        className = 'strong';
    } else if(value.length >= 6) {
        strength = 'medium';
        message = '⚠️ Medium - add uppercase letters and numbers';
        className = 'medium';
    }
    strengthBar.className = `password-strength strength-${strength}`;
    strengthText.textContent = message;
    strengthText.className = `strength-text ${className}`;
});
confirmPassword.addEventListener('keyup', function() {
    const error = document.getElementById('confirmError');
    if(this.value.length > 0 && this.value !== password.value) {
        error.style.display = 'block';
    } else {
        error.style.display = 'none';
    }
});
form.addEventListener('submit', function(e) {
    let isValid = true;
    if(fullname.value.trim() === '') { document.getElementById('nameError').style.display = 'block'; isValid = false; }
    if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) { document.getElementById('emailError').style.display = 'block'; isValid = false; }
    if(password.value.length < 6) { document.getElementById('passwordError').style.display = 'block'; isValid = false; }
    if(confirmPassword.value !== password.value) { document.getElementById('confirmError').style.display = 'block'; isValid = false; }
    if(!isValid) e.preventDefault();
});
</script>

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

// Existing validation code...
</script>

<?php include 'includes/footer.php'; ?>