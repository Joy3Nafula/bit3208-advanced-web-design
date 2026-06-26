<?php
session_start();
include 'includes/check_login.php';
require_once 'config/database.php';

$property_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$query = "DELETE FROM properties WHERE id = $property_id";
if($role != 'admin') {
    $query .= " AND agent_id = $user_id";
}

if(mysqli_query($conn, $query)) {
    header("Location: dashboard.php?success=Property deleted");
} else {
    header("Location: dashboard.php?error=Delete failed");
}
mysqli_close($conn);
?>