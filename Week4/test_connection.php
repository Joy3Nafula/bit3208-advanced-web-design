<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "week3_taskmanager";

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("<div style='background:red; color:white; padding:20px; font-family:Arial;'>
          ❌ Connection Failed: " . mysqli_connect_error() . "
          </div>");
}

echo "<div style='background:green; color:white; padding:20px; font-family:Arial; border-radius:10px;'>
        <h2>✅ Database Connected Successfully!</h2>
        <p><strong>Server:</strong> " . mysqli_get_server_info($conn) . "</p>
        <p><strong>Database:</strong> " . $database . "</p>
        <p><strong>Host:</strong> " . $host . "</p>
      </div>";

// Test query to check users table
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM users");
if($result) {
    $row = mysqli_fetch_assoc($result);
    echo "<div style='background:#e8f5e9; padding:15px; margin-top:10px; border-radius:10px;'>
            <p>✅ Users table exists with " . $row['count'] . " registered users.</p>
          </div>";
} else {
    echo "<div style='background:#fff3e0; padding:15px; margin-top:10px; border-radius:10px;'>
            <p>⚠️ Users table not found yet. Registration will create users.</p>
          </div>";
}

// Close connection
mysqli_close($conn);
?>