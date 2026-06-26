<?php
session_start();
include 'includes/check_login.php';
require_once 'config/database.php';

$property_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$query = "SELECT * FROM properties WHERE id = $property_id";
if($role != 'admin') {
    $query .= " AND agent_id = $user_id";
}
$result = mysqli_query($conn, $query);
$property = mysqli_fetch_assoc($result);

if(!$property) {
    header("Location: dashboard.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];
    $type = $_POST['type'];
    $property_status = $_POST['property_status'];
    $image_url = $_POST['image_url'];

    $update = "UPDATE properties SET title='$title', description='$description', price='$price', location='$location', 
               bedrooms='$bedrooms', bathrooms='$bathrooms', type='$type', property_status='$property_status', image_url='$image_url' 
               WHERE id=$property_id";

    if(mysqli_query($conn, $update)) {
        header("Location: dashboard.php?success=Property updated");
        exit;
    }
}

$page_title = "Edit Property";
include 'includes/header.php';
?>

<div style="max-width:600px; margin:0 auto;">
    <div class="card">
        <h2>✏️ Edit Property</h2>
        <form method="POST">
            <div class="form-group">
                <label>Title *</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($property['title']); ?>" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4"><?php echo htmlspecialchars($property['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Price (KSh) *</label>
                <input type="number" name="price" value="<?php echo $property['price']; ?>" required>
            </div>
            <div class="form-group">
                <label>Location *</label>
                <input type="text" name="location" value="<?php echo htmlspecialchars($property['location']); ?>" required>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">
                <div class="form-group">
                    <label>Bedrooms</label>
                    <input type="number" name="bedrooms" value="<?php echo $property['bedrooms']; ?>">
                </div>
                <div class="form-group">
                    <label>Bathrooms</label>
                    <input type="number" name="bathrooms" value="<?php echo $property['bathrooms']; ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Type</label>
                <select name="type">
                    <option value="sale" <?php echo $property['type']=='sale'?'selected':''; ?>>For Sale</option>
                    <option value="rent" <?php echo $property['type']=='rent'?'selected':''; ?>>For Rent</option>
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="property_status">
                    <option value="available" <?php echo $property['property_status']=='available'?'selected':''; ?>>Available</option>
                    <option value="sold" <?php echo $property['property_status']=='sold'?'selected':''; ?>>Sold</option>
                    <option value="rented" <?php echo $property['property_status']=='rented'?'selected':''; ?>>Rented</option>
                </select>
            </div>
            <div class="form-group">
                <label>Image URL</label>
                <input type="text" name="image_url" value="<?php echo $property['image_url']; ?>">
            </div>
            <button type="submit" class="btn-primary" style="width:100%;">Update Property</button>
        </form>
        <div style="margin-top:20px;">
            <a href="dashboard.php" style="color:var(--burnt-orange); text-decoration:none;">← Back to Dashboard</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; mysqli_close($conn); ?>