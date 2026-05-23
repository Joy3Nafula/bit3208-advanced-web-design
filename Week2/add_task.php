<?php
session_start();

// Check login
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Database connection
$conn = mysqli_connect("localhost", "root", "", "week3_taskmanager");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get form data
$title = $_POST['title'];
$description = $_POST['description'];
$due_date = $_POST['due_date'];
$status = $_POST['status'];

// Insert task
$query = "INSERT INTO tasks (user_id, title, description, due_date, status) 
          VALUES ('$user_id', '$title', '$description', '$due_date', '$status')";

if(mysqli_query($conn, $query)) {
    header("Location: dashboard.php?success=Task added");
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>