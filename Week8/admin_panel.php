<?php
session_start();
include 'includes/check_login.php';

if($_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit;
}

require_once 'config/database.php';

$page_title = "Admin Panel";
include 'includes/header.php';
?>

<h2>⚙️ Admin Panel</h2>

<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px,1fr)); gap:20px; margin-top:20px;">
    <a href="manage_users.php" class="card" style="text-decoration:none; color:inherit; transition:all 0.3s;">
        <h3>👥 Manage Users</h3>
        <p style="color:#8D6E63;">View, add, edit, delete users</p>
    </a>
    <a href="add_property.php" class="card" style="text-decoration:none; color:inherit; transition:all 0.3s;">
        <h3>🏠 Add Property</h3>
        <p style="color:#8D6E63;">Create new property listing</p>
    </a>
    <a href="index.php" class="card" style="text-decoration:none; color:inherit; transition:all 0.3s;">
        <h3>📋 All Listings</h3>
        <p style="color:#8D6E63;">View all properties</p>
    </a>
    <a href="dashboard.php" class="card" style="text-decoration:none; color:inherit; transition:all 0.3s;">
        <h3>📊 Dashboard</h3>
        <p style="color:#8D6E63;">Overview stats</p>
    </a>
</div>

<?php include 'includes/footer.php'; mysqli_close($conn); ?>