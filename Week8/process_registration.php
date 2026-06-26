<?php
session_start();
require_once 'config/database.php';

$fullname = $_POST['fullname'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];
$role_id = $_POST['role_id'] ?? 3;
$phone = $_POST['phone'] ?? '';

$errors = [];
if(empty($fullname)) $errors[] = "Full name is required";
if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
if(strlen($password) < 6) $errors[] = "Password must be at least 6 characters";
if($password !== $confirmPassword) $errors[] = "Passwords do not match";

$checkEmail = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
if(mysqli_num_rows($checkEmail) > 0) $errors[] = "Email already registered";

if(count($errors) > 0) {
    echo "<div style='background:#f8d7da; padding:30px; max-width:500px; margin:50px auto; border-radius:15px;'>";
    echo "<h2 style='color:#721c24;'>❌ Registration Failed</h2><ul>";
    foreach($errors as $error) echo "<li style='color:#721c24;'>$error</li>";
    echo "</ul><a href='register.php' class='btn-primary'>← Back</a></div>";
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$query = "INSERT INTO users (fullname, email, password, phone, role_id) VALUES ('$fullname', '$email', '$hashedPassword', '$phone', '$role_id')";

if(mysqli_query($conn, $query)) {
    echo "<div style='background:#d4edda; padding:30px; max-width:500px; margin:50px auto; border-radius:15px; text-align:center;'>";
    echo "<h2 style='color:#155724;'>✅ Registration Successful!</h2>";
    echo "<p>Welcome, $fullname!</p>";
    $role_names = [1=>'Admin', 2=>'Agent', 3=>'Tenant'];
    echo "<p>Role: " . ($role_names[$role_id] ?? 'Tenant') . "</p>";
    echo "<a href='login.php' class='btn-primary'>Go to Login →</a>";
    echo "</div>";
} else {
    echo "Error: " . mysqli_error($conn);
}
mysqli_close($conn);
?>