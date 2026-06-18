<?php
session_start();
include 'check_login.php';

$conn = mysqli_connect("localhost", "root", "", "week6_taskmanager");

$user_id = $_SESSION['user_id'];
$task_id = $_GET['id'];

// Delete task (only if belongs to user or assigned by user)
$query = "DELETE FROM tasks WHERE id = $task_id AND (user_id = $user_id OR assigned_by = $user_id)";
mysqli_query($conn, $query);

header("Location: dashboard.php?success=Task deleted");
mysqli_close($conn);
?>