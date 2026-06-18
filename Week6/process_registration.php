<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "week6_taskmanager");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$fullname = $_POST['fullname'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];
$role_id = $_POST['role_id'];

$errors = [];

if(empty($fullname)) $errors[] = "Full name is required";
if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
if(strlen($password) < 6) $errors[] = "Password must be at least 6 characters";
if($password !== $confirmPassword) $errors[] = "Passwords do not match";

$checkEmail = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
if(mysqli_num_rows($checkEmail) > 0) $errors[] = "Email already registered";

if(count($errors) > 0) {
    echo "<div style='background:#fee; padding:20px; font-family:Arial; max-width:500px; margin:50px auto; border-radius:10px;'>
            <h2 style='color:#c0392b;'>❌ Registration Failed</h2><ul>";
    foreach($errors as $error) echo "<li style='color:#c0392b;'>$error</li>";
    echo "</ul><a href='register.html' style='display:inline-block; margin-top:10px; background:#667eea; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>← Back</a></div>";
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$query = "INSERT INTO users (fullname, email, password, role_id) VALUES ('$fullname', '$email', '$hashedPassword', '$role_id')";

if(mysqli_query($conn, $query)) {
    echo "<div class='page-card compact'>
            <div class='alert-card success'>
                <h2>✅ Registration Successful!</h2>
                <p>Welcome, $fullname!</p>
                <p>Role: " . ($role_id == 1 ? 'Admin' : ($role_id == 2 ? 'Manager' : 'Student')) . "</p>
            </div>
            <div class='section-spacing'>
                <a href='login.php' class='btn-primary'>Go to Login →</a>
            </div>
          </div>";
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>