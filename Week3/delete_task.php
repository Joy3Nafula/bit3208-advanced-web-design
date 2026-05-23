<?php
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$task_id = $_GET['id'];

$conn = mysqli_connect("localhost", "root", "", "week3_taskmanager");

// Delete task (only if it belongs to this user)
$query = "DELETE FROM tasks WHERE id = $task_id AND user_id = $user_id";
mysqli_query($conn, $query);

header("Location: dashboard.php?success=Task deleted");
mysqli_close($conn);
?>