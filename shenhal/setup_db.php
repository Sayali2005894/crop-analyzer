<?php
// Custom config for setup to handle missing DB
session_start();
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db = "cropdbb";

// Connect to MySQL server (without selecting DB)
$conn = @mysqli_connect($host, $user, $pass);
if (!$conn) $conn = @mysqli_connect($host, $user, $pass);

if (!$conn) {
    die("<div style='font-family:sans-serif; text-align:center; padding:50px;'>
        <h2 style='color:#ef4444;'>Cannot reach MySQL Server</h2>
        <p>Please make sure XAMPP/MySQL is started.</p>
        <p style='color:#666;'>Error: " . mysqli_connect_error() . "</p>
    </div>");
}

// Create Database
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $db");
mysqli_select_db($conn, $db);

// Users Table
$sql1 = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Analyses Table
$sql2 = "CREATE TABLE IF NOT EXISTS analyses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    crop VARCHAR(50),
    location VARCHAR(100),
    soil_type VARCHAR(50),
    season VARCHAR(50),
    notes TEXT,
    photo_path VARCHAR(255),
    humidity INT,
    rainfall INT,
    temp INT,
    risk_level VARCHAR(20),
    suitability VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

mysqli_query($conn, $sql1);
mysqli_query($conn, $sql2);

header("Location: register.php?msg=Database initialized successfully!");
exit();
?>
