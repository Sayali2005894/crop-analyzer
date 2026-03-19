<?php
session_start();

$host = "127.0.0.1";
$user = "root";
$pass = "";
$db = "cropdbb";

// Use 127.0.0.1 to force TCP instead of socket
$conn = @mysqli_connect($host, $user, $pass, $db);

// Fallback to default connection if socket fails
if (!$conn) {
    $conn = @mysqli_connect($host, $user, $pass, $db);
}

// Demo Mode Bypass: Add ?demo=true to any URL to bypass DB check
if (isset($_GET['demo']) || (isset($_SESSION['demo_mode']) && $_SESSION['demo_mode'] === true)) {
    $_SESSION['demo_mode'] = true;
    $_SESSION['user_id'] = 1;
    $_SESSION['name'] = "Demo User";
    return; // Stop processing the rest of config.php
}

if (!$conn) {
    // Show a more helpful error page instead of just "Database connection failed"
    echo "<div style='font-family: sans-serif; padding: 50px; text-align: center;'>";
    echo "<h2 style='color: #ef4444;'>⚠️ Database Connection Offline</h2>";
    echo "<p>The MySQL server (XAMPP) is not running or the database <b>$db</b> has not been created.</p>";
    echo "<p style='font-size: 14px; color: #666;'>Error Details: " . mysqli_connect_error() . "</p>";
    echo "<hr style='width: 300px; margin: 30px auto; border: none; border-top: 1px solid #eee;'>";
    echo "<p><b>How to fix:</b> Start MySQL in your XAMPP Control Panel and click the button below to initialize.</p>";
    echo "<a href='setup_db.php' style='display: inline-block; padding: 12px 24px; background: #10b981; color: white; text-decoration: none; border-radius: 8px;'>Try to Setup Database</a>";
    echo "</div>";
    exit();
}
?>