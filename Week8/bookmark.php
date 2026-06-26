<?php
session_start();
include 'includes/check_login.php';
require_once 'config/database.php';

$property_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$check = "SELECT * FROM bookmarks WHERE user_id = $user_id AND property_id = $property_id";
$result = mysqli_query($conn, $check);

if(mysqli_num_rows($result) > 0) {
    mysqli_query($conn, "DELETE FROM bookmarks WHERE user_id = $user_id AND property_id = $property_id");
} else {
    mysqli_query($conn, "INSERT INTO bookmarks (user_id, property_id) VALUES ($user_id, $property_id)");
}

header("Location: view_property.php?id=$property_id");
mysqli_close($conn);
exit;
?>