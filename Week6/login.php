<?php
session_start();
// If already logged in, go to dashboard
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Manager</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container { max-width: 450px; margin: 50px auto; }
        .error-msg {
            background: #f8d7da;
            color: #721c24;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: #111827;
        }
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 14px;
            border: 2px solid #cbd5e1;
            border-radius: 10px;
            font-size: 16px;
            color: #111827;
            background: #ffffff;
        }
        .form-group input:focus {
            outline: none;
            border-color: #1d4ed8;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .form-group.checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-group.checkbox input {
            width: auto;
        }
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-login:hover { transform: translateY(-2px); }
        .link { text-align: center; margin-top: 20px; }
        .link a {
            color: white;
            background: #1d4ed8;
            padding: 10px 18px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
        }
        .link a:hover { background: #0e3aa7; }
    </style>
</head>
<body>
    <div class="login-container">
        <div style="background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <h2 style="text-align:center; margin-bottom:25px;">Welcome Back</h2>
            
            <?php if(isset($_GET['error'])): ?>
                <div class="error-msg">
                    <?php 
                    $error = $_GET['error'];
                    if($error == 'empty') echo '❌ Email and password are required';
                    elseif($error == 'invalid') echo '❌ Invalid email or password';
                    elseif($error == 'notfound') echo '❌ Email not found. <a href="register.html" style="color:#721c24;">Register here</a>';
                    ?>
                </div>
            <?php endif; ?>
            
            <form action="process_login.php" method="POST">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                
                <div class="form-group checkbox">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember" style="margin:0;">Remember me for 7 days</label>
                </div>
                
                <button type="submit" class="btn-login">Login</button>
            </form>
            
            <div class="link">
                Don't have an account? <a href="register.html">Register here</a>
            </div>
        </div>
    </div>
</body>
</html>