<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "week3_taskmanager");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$email = $_POST['email'];
$password = $_POST['password'];

// Validation
if(empty($email) || empty($password)) {
    header("Location: login.php?error=empty");
    exit;
}

// Get user from database
$query = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
    
    // Verify password
    if(password_verify($password, $user['password'])) {
        // Login successful - create session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['fullname'];
        $_SESSION['user_email'] = $user['email'];
        
        // Redirect to dashboard
        header("Location: dashboard.php");
        exit;
    } else {
        header("Location: login.php?error=invalid");
        exit;
    }
} else {
    header("Location: login.php?error=notfound");
    exit;
}

mysqli_close($conn);
?>