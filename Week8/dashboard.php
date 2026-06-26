<?php
session_start();
include 'includes/check_login.php';
require_once 'config/database.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$fullname = $_SESSION['fullname'];

// Stats
if($role == 'admin') {
    $stats_query = $stats_query = "SELECT COUNT(*) as total, 
                   SUM(CASE WHEN property_status = 'available' THEN 1 ELSE 0 END) as available,
                   SUM(CASE WHEN property_status = 'sold' OR property_status = 'rented' THEN 1 ELSE 0 END) as sold_rented,
                  (SELECT COUNT(*) FROM users) as total_users 
                  FROM properties";
    $props_query = "SELECT p.*, u.fullname as agent_name FROM properties p 
                    JOIN users u ON p.agent_id = u.id ORDER BY p.created_at DESC";
} elseif($role == 'agent') {
    $stats_query =$stats_query = "SELECT COUNT(*) as total, 
                  SUM(CASE WHEN property_status = 'available' THEN 1 ELSE 0 END) as available,
                  SUM(CASE WHEN property_status = 'sold' OR property_status = 'rented' THEN 1 ELSE 0 END) as sold_rented 
                  FROM properties WHERE agent_id = $user_id"; 
    $props_query = "SELECT * FROM properties WHERE agent_id = $user_id ORDER BY created_at DESC";
} else {
    $stats_query = "SELECT COUNT(*) as total FROM properties WHERE property_status = 'available'";
    $props_query = "SELECT p.*, u.fullname as agent_name FROM properties p 
                    JOIN users u ON p.agent_id = u.id 
                    WHERE p.property_status = 'available' 
                    ORDER BY p.created_at DESC LIMIT 10";
}

$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);
$props_result = mysqli_query($conn, $props_query);

$page_title = "Dashboard";
include 'includes/header.php';
?>

<h2>Welcome, <?php echo $fullname; ?>!</h2>
<p style="margin-bottom:20px;">Role: <strong><?php echo ucfirst($role); ?></strong></p>

<div class="stats-container">
    <div class="stat-card border-orange">
        <div class="stat-number"><?php echo $stats['total'] ?? 0; ?></div>
        <div class="stat-label">Total Properties</div>
    </div>
    <?php if($role != 'tenant'): ?>
    <div class="stat-card border-green">
        <div class="stat-number"><?php echo $stats['available'] ?? 0; ?></div>
        <div class="stat-label">Available</div>
    </div>
    <div class="stat-card border-red">
        <div class="stat-number"><?php echo $stats['sold_rented'] ?? 0; ?></div>
        <div class="stat-label">Sold/Rented</div>
    </div>
    <?php endif; ?>
    <?php if($role == 'admin'): ?>
    <div class="stat-card border-brown">
        <div class="stat-number"><?php echo $stats['total_users'] ?? 0; ?></div>
        <div class="stat-label">👥 Total Users</div>
    </div>
    <?php endif; ?>
</div>

<div class="table-container">
    <h3>📋 <?php echo ($role == 'tenant') ? 'Available Properties' : 'My Properties'; ?></h3>
    <?php if(mysqli_num_rows($props_result) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Location</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=1; while($prop = mysqli_fetch_assoc($props_result)): ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><strong><?php echo htmlspecialchars($prop['title']); ?></strong></td>
                <td><?php echo $prop['location']; ?></td>
                <td>KSh <?php echo number_format($prop['price']); ?></td>
                <td><span class="role-badge <?php echo $prop['property_status']; ?>"><?php echo ucfirst($prop['property_status']); ?></span></td>
                <td>
                    <a href="view_property.php?id=<?php echo $prop['id']; ?>" class="btn-edit">👁️ View</a>
                    <?php if($role == 'admin' || ($role == 'agent' && $prop['agent_id'] == $user_id)): ?>
                        <a href="edit_property.php?id=<?php echo $prop['id']; ?>" class="btn-edit">✏️</a>
                        <a href="delete_property.php?id=<?php echo $prop['id']; ?>" class="btn-delete" onclick="return confirm('Delete this property?')">🗑️</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p style="text-align:center; padding:30px; color:#8D6E63;">No properties found.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; mysqli_close($conn); ?>