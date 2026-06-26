<?php
session_start();
include 'includes/check_login.php';

if($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'agent') {
    header("Location: dashboard.php");
    exit;
}

require_once 'config/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $agent_id = $_SESSION['user_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];
    $type = $_POST['type'];
    $image_url = $_POST['image_url'] ?? 'house1.jpg';

    $query = "INSERT INTO properties (agent_id, title, description, price, location, bedrooms, bathrooms, type, image_url) 
              VALUES ('$agent_id', '$title', '$description', '$price', '$location', '$bedrooms', '$bathrooms', '$type', '$image_url')";

    if(mysqli_query($conn, $query)) {
        header("Location: dashboard.php?success=Property added");
        exit;
    } else {
        $error = mysqli_error($conn);
    }
}

$page_title = "Add Property";
include 'includes/header.php';
?>

<div style="max-width:600px; margin:0 auto;">
    <div class="card">
        <h2>🏠 Add New Property</h2>
        <?php if(isset($error)): ?>
            <div class="alert-error">❌ <?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Title *</label>
                <input type="text" name="title" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label>Price (KSh) *</label>
                <input type="number" name="price" required>
            </div>
            <div class="form-group">
                <label>Location *</label>
                <input type="text" name="location" required>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">
                <div class="form-group">
                    <label>Bedrooms</label>
                    <input type="number" name="bedrooms" value="0">
                </div>
                <div class="form-group">
                    <label>Bathrooms</label>
                    <input type="number" name="bathrooms" value="0">
                </div>
            </div>
            <div class="form-group">
                <label>Type</label>
                <select name="type">
                    <option value="sale">For Sale</option>
                    <option value="rent">For Rent</option>
                </select>
            </div>
            <div class="form-group">
                <label>Image URL</label>
                <input type="text" name="image_url" placeholder="house1.jpg (or leave blank for default)">
            </div>
            <button type="submit" class="btn-primary" style="width:100%;">Add Property</button>
        </form>
        <div style="margin-top:20px;">
            <a href="dashboard.php" style="color:var(--burnt-orange); text-decoration:none;">← Back to Dashboard</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; mysqli_close($conn); ?>