<?php
/**
 * Certificate Management System - Configuration File
 * This file contains database connection details and configuration settings
 */

// Database Configuration
define('DB_HOST', 'localhost');      // cPanel default host
define('DB_USER', 'root');           // Change this to your cPanel database user
define('DB_PASS', '');               // Change this to your cPanel database password
define('DB_NAME', 'piit_certificates'); // Database name (create this in cPanel)

// File Upload Configuration
define('UPLOAD_DIR', __DIR__ . '/certificates/');  // Directory for storing certificates
define('MAX_FILE_SIZE', 50 * 1024 * 1024);         // 50MB max file size
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
