<?php
session_start();
require_once 'config/database.php';

$page_title = "Home";
include 'includes/header.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : '';
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : '';

$query = "SELECT p.*, u.fullname as agent_name FROM properties p 
          JOIN users u ON p.agent_id = u.id WHERE p.property_status = 'available'";

if($search) {
    $query .= " AND (p.title LIKE '%$search%' OR p.location LIKE '%$search%')";
}
if($type) {
    $query .= " AND p.type = '$type'";
}
if($min_price) {
    $query .= " AND p.price >= $min_price";
}
if($max_price) {
    $query .= " AND p.price <= $max_price";
}
$query .= " ORDER BY p.created_at DESC";

$result = mysqli_query($conn, $query);
?>

<!-- BANNER -->
<div class="banner" style="border-radius:15px; margin-bottom:30px;">
    <h1>🏠 Find Your Dream Home</h1>
    <p>Discover the best properties for sale and rent in Kenya</p>
</div>

<!-- SEARCH -->
<div class="search-section">
    <form method="GET">
        <div class="search-grid">
            <input type="text" name="search" placeholder="🔍 Search by location or title..." value="<?php echo $search; ?>">
            <select name="type">
                <option value="">All Types</option>
                <option value="sale" <?php echo $type=='sale'?'selected':''; ?>>For Sale</option>
                <option value="rent" <?php echo $type=='rent'?'selected':''; ?>>For Rent</option>
            </select>
            <input type="number" name="min_price" placeholder="Min Price" value="<?php echo $min_price; ?>">
            <input type="number" name="max_price" placeholder="Max Price" value="<?php echo $max_price; ?>">
            <button type="submit" class="btn-primary">Search</button>
        </div>
    </form>
</div>

<!-- RESULTS -->
<div class="property-grid">
    <?php if(mysqli_num_rows($result) > 0): ?>
        <?php while($property = mysqli_fetch_assoc($result)): ?>
        <div class="property-card">
            <!-- IMAGE -->
            <img src="<?php echo $property['image_url'] ?? 'https://via.placeholder.com/600x400/E07A2E/FFFFFF?text=No+Image'; ?>" 
                 alt="<?php echo $property['title']; ?>" 
                 class="property-image">
            
            <div class="property-details">
                <div class="property-title"><?php echo htmlspecialchars($property['title']); ?></div>
                <div class="property-location">📍 <?php echo $property['location']; ?></div>
                <div class="property-price">KSh <?php echo number_format($property['price']); ?></div>
                <div class="property-meta">
                    <span>🛏️ <?php echo $property['bedrooms']; ?> beds</span>
                    <span>🛁 <?php echo $property['bathrooms']; ?> baths</span>
                    <span>📋 <?php echo ucfirst($property['type']); ?></span>
                </div>
                <div class="property-actions">
                    <a href="view_property.php?id=<?php echo $property['id']; ?>" class="btn-secondary" style="padding:8px 20px; font-size:13px;">👁️ View</a>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="bookmark.php?id=<?php echo $property['id']; ?>" class="btn-outline" style="padding:8px 20px; font-size:13px;">⭐</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div style="grid-column:1/-1; text-align:center; padding:40px; color:#8D6E63;">
            <p>No properties found matching your search.</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; mysqli_close($conn); ?>