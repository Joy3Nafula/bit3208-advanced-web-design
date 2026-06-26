<?php
session_start();
require_once 'config/database.php';

$property_id = $_GET['id'];
$query = "SELECT p.*, u.fullname as agent_name, u.email as agent_email, u.phone as agent_phone 
          FROM properties p JOIN users u ON p.agent_id = u.id WHERE p.id = $property_id";
$result = mysqli_query($conn, $query);
$property = mysqli_fetch_assoc($result);

if(!$property) {
    header("Location: index.php");
    exit;
}

$page_title = $property['title'];
include 'includes/header.php';
?>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:30px;">
    <div class="card">
      <img src="<?php echo $property['image_url'] ?? 'https://via.placeholder.com/600x400/E07A2E/FFFFFF?text=No+Image'; ?>" 
     alt="<?php echo $property['title']; ?>" 
     style="width:100%; border-radius:10px; max-height:400px; object-fit:cover;">
    </div>
    <div class="card">
        <h2><?php echo htmlspecialchars($property['title']); ?></h2>
        <p style="color:var(--burnt-orange); font-size:24px; font-weight:700;">KSh <?php echo number_format($property['price']); ?></p>
        <p><strong>Location:</strong> <?php echo $property['location']; ?></p>
        <p><strong>Type:</strong> <?php echo ucfirst($property['type']); ?></p>
        <p><strong>Status:</strong> <span class="role-badge <?php echo $property['property_status']; ?>"><?php echo ucfirst($property['property_status']); ?></span></p>
        <p><strong>Bedrooms:</strong> <?php echo $property['bedrooms']; ?></p>
        <p><strong>Bathrooms:</strong> <?php echo $property['bathrooms']; ?></p>
        <hr style="margin:15px 0;">
        <p><strong>Agent:</strong> <?php echo $property['agent_name']; ?></p>
        <p><strong>Contact:</strong> <?php echo $property['agent_email']; ?> | <?php echo $property['agent_phone']; ?></p>
        <div style="margin-top:15px;">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="bookmark.php?id=<?php echo $property['id']; ?>" class="btn-outline">⭐ Bookmark</a>
                <a href="mailto:<?php echo $property['agent_email']; ?>" class="btn-primary">📧 Contact Agent</a>
            <?php else: ?>
                <a href="login.php" class="btn-primary">Login to Contact</a>
            <?php endif; ?>
        </div>
        <div style="margin-top:20px;">
            <a href="index.php" style="color:var(--burnt-orange); text-decoration:none;">← Back to Listings</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; mysqli_close($conn); ?>