<?php
session_start();
include 'check_login.php';

$conn = mysqli_connect("localhost", "root", "", "week6_taskmanager");

$user_id = $_SESSION['user_id'];
$title = $_POST['title'];
$description = $_POST['description'];
$due_date = $_POST['due_date'];
$status = $_POST['status'];

$query = "INSERT INTO tasks (user_id, title, description, due_date, status) 
          VALUES ('$user_id', '$title', '$description', '$due_date', '$status')";

if(mysqli_query($conn, $query)) {
    header("Location: dashboard.php?success=Task added");
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>