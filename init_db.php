<?php
/**
 * Database Initialization Script
 * Run this once to set up the database structure
 */

require_once 'config.php';

// Create connection to MySQL server (without database)
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists.<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db(DB_NAME);

// Create certificates table
$sql = "CREATE TABLE IF NOT EXISTS certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(255) NOT NULL,
    certificate_file VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    uploaded_by VARCHAR(255),
    INDEX idx_student_name (student_name),
    INDEX idx_upload_date (upload_date)
)";

if ($conn->query($sql) === TRUE) {
    echo "Certificates table created successfully or already exists.<br>";
} else {
    die("Error creating table: " . $conn->error);
}

// Create admin logs table (optional, for tracking admin activities)
$sql = "CREATE TABLE IF NOT EXISTS admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    action VARCHAR(255) NOT NULL,
    admin_user VARCHAR(255),
    description TEXT,
    action_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_date (action_date)
)";

if ($conn->query($sql) === TRUE) {
    echo "Admin logs table created successfully or already exists.<br>";
} else {
    die("Error creating admin logs table: " . $conn->error);
}

echo "<br><strong>Database setup completed successfully!</strong><br>";
echo "You can now use the certificate system.";

$conn->close();
?>
