<?php
session_start();

// Clear remember me cookie
setcookie('remember_token', '', time() - 3600, "/");

// Destroy session
session_destroy();

header("Location: login.php");
exit;
?>