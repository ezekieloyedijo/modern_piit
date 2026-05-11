<?php
/**
 * Certificate Management System - Configuration File
 * This file contains database connection details and configuration settings
 */

// Database Configuration
define('DB_HOST', 'localhost');      // cPanel default host
define('DB_USER', 'piitcom_piitcom1');           // Change this to your cPanel database user
define('DB_PASS', 'Prime_Plot@12');               // Change this to your cPanel database password
define('DB_NAME', 'piitcom_db'); // Database name (create this in cPanel)

// File Upload Configuration
define('UPLOAD_DIR', __DIR__ . '/certificates/');  // Directory for storing certificates
define('MAX_FILE_SIZE', 512 * 1024);         // 512KB max file size
define('ALLOWED_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'bmp']);

// Admin Configuration
define('ADMIN_USERNAME', 'admin');               // Change this to your admin username
define('ADMIN_PASSWORD', 'PIIT@2024Secure');     // Change this to a strong password, hash it in production
define('SESSION_TIMEOUT', 3600);                 // Session timeout in seconds (1 hour)

// Error Reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();
