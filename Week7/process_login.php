<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "week6_taskmanager");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$email = $_POST['email'];
$password = $_POST['password'];
$remember = isset($_POST['remember']) ? true : false;

// Query with role information
$query = "SELECT u.*, r.role_name FROM users u 
          JOIN roles r ON u.role_id = r.id 
          WHERE u.email = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
    
    if(password_verify($password, $user['password'])) {
        // Set session with role
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['fullname'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['role'] = $user['role_name'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['login_time'] = time();
        
        // Remember Me (7 days)
        if($remember) {
            $token = bin2hex(random_bytes(32));
            setcookie('remember_token', $token, time() + (86400 * 7), "/");
            
            $update_token = "UPDATE users SET remember_token = '$token' WHERE id = {$user['id']}";
            mysqli_query($conn, $update_token);
        }
        
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