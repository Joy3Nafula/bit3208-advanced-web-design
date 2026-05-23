<?php
// Start session for future use
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "week3_taskmanager");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get form data
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];

// Validation
$errors = [];

if(empty($fullname)) {
    $errors[] = "Full name is required";
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Valid email is required";
}

if(strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters";
}

if($password !== $confirmPassword) {
    $errors[] = "Passwords do not match";
}

// Check if email already exists
$checkEmail = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
if(mysqli_num_rows($checkEmail) > 0) {
    $errors[] = "Email already registered. Please login.";
}

// If errors, show them
if(count($errors) > 0) {
    echo "<div style='background:#fee; padding:20px; font-family:Arial; max-width:500px; margin:50px auto; border-radius:10px;'>
            <h2 style='color:#c0392b;'>❌ Registration Failed</h2>
            <ul>";
    foreach($errors as $error) {
        echo "<li style='color:#c0392b;'>$error</li>";
    }
    echo "</ul>
            <a href='register.html' style='display:inline-block; margin-top:10px; background:#667eea; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>← Back to Registration</a>
          </div>";
    exit;
}

// Hash password for security
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert user into database
$query = "INSERT INTO users (fullname, email, password) VALUES ('$fullname', '$email', '$hashedPassword')";

if(mysqli_query($conn, $query)) {
    echo "<div style='background:#e8f5e9; padding:20px; font-family:Arial; max-width:500px; margin:50px auto; border-radius:10px; text-align:center;'>
            <h2 style='color:#27ae60;'>✅ Registration Successful!</h2>
            <p>Welcome, $fullname!</p>
            <p>You can now <a href='login.html' style='color:#667eea;'>login here</a></p>
            <a href='login.html' style='display:inline-block; margin-top:20px; background:#667eea; color:white; padding:10px 30px; text-decoration:none; border-radius:5px;'>Go to Login →</a>
          </div>";
} else {
    echo "<div style='background:#fee; padding:20px; font-family:Arial;'>
            Error: " . mysqli_error($conn) . "
          </div>";
}

mysqli_close($conn);
?>