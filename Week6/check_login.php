<?php
// check_login.php - Auto-login with role detection

if(!isset($_SESSION['user_id'])) {
    // Check for remember me cookie
    if(isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];
        
        $conn = mysqli_connect("localhost", "root", "", "week6_taskmanager");
        
        if($conn) {
            // Get user with role
            $query = "SELECT u.*, r.role_name FROM users u 
                      JOIN roles r ON u.role_id = r.id 
                      WHERE u.remember_token = '$token'";
            $result = mysqli_query($conn, $query);
            
            if(mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['fullname'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['role'] = $user['role_name'];
                $_SESSION['role_id'] = $user['role_id'];
                $_SESSION['login_time'] = time();
            }
            mysqli_close($conn);
        }
    }
    
    // If still not logged in, redirect
    if(!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}
?>