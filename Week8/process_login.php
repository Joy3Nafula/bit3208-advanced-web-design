<?php
session_start();
require_once 'config/database.php';

$email = $_POST['email'];
$password = $_POST['password'];
$remember = isset($_POST['remember']) ? true : false;

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
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['phone'] = $user['phone'];
        $_SESSION['role'] = $user['role_name'];
        $_SESSION['role_id'] = $user['role_id'];
        
        if($remember) {
            $token = bin2hex(random_bytes(32));
            setcookie('remember_token', $token, time() + (86400 * 7), "/");
            $update = "UPDATE users SET remember_token = '$token' WHERE id = {$user['id']}";
            mysqli_query($conn, $update);
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