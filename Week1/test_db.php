<?php
// C:\xampp\htdocs\laptop_store\test_db.php

$host = "localhost";
$user = "root"; 
$password = ""; // Default XAMPP setup has no password

// Check the connection using the required mysqli_connect function
$connection = mysqli_connect($host, $user, $password);

if ($connection) {
    echo "<h2>Database Connection Status: SUCCESS</h2>";
    echo "<p>PHP has successfully established a handshake with MySQL using mysqli_connect().</p>";
} else {
    echo "<h2>Database Connection Status: FAILED</h2>";
    echo "<p>Error details: " . mysqli_connect_error() . "</p>";
}

// Close the connection safely
mysqli_close($connection);
?>