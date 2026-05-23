<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Welcome Back</h2>
        
        <?php
        // Check for error messages from URL
        if(isset($_GET['error'])) {
            $error = $_GET['error'];
            if($error == 'empty') {
                echo '<div style="background:#fee; color:#c0392b; padding:10px; border-radius:5px; margin-bottom:20px;">❌ Email and password are required</div>';
            } elseif($error == 'invalid') {
                echo '<div style="background:#fee; color:#c0392b; padding:10px; border-radius:5px; margin-bottom:20px;">❌ Invalid email or password</div>';
            } elseif($error == 'notfound') {
                echo '<div style="background:#fee; color:#c0392b; padding:10px; border-radius:5px; margin-bottom:20px;">❌ Email not found. <a href="register.html" style="color:#c0392b;">Register here</a></div>';
            }
        }
        ?>
        
        <form id="loginForm" action="process_login.php" method="POST">
            <div class="form-group">
                <label>Email *</label>
                <input type="email" id="email" name="email" required>
                <div class="error-message" id="emailError">Please enter your email</div>
            </div>

            <div class="form-group">
                <label>Password *</label>
                <input type="password" id="password" name="password" required>
                <div class="error-message" id="passwordError">Password cannot be empty</div>
            </div>

            <button type="submit">Login</button>
        </form>

        <div class="link">
            Don't have an account? <a href="register.html">Register here</a>
        </div>
    </div>

    <script>
        const form = document.getElementById('loginForm');
        const email = document.getElementById('email');
        const password = document.getElementById('password');

        email.addEventListener('blur', function() {
            const error = document.getElementById('emailError');
            if(this.value.trim() === '') {
                error.style.display = 'block';
            } else {
                error.style.display = 'none';
            }
        });

        password.addEventListener('blur', function() {
            const error = document.getElementById('passwordError');
            if(this.value.trim() === '') {
                error.style.display = 'block';
            } else {
                error.style.display = 'none';
            }
        });

        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            if(email.value.trim() === '') {
                document.getElementById('emailError').style.display = 'block';
                isValid = false;
            }
            
            if(password.value.trim() === '') {
                document.getElementById('passwordError').style.display = 'block';
                isValid = false;
            }
            
            if(!isValid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>