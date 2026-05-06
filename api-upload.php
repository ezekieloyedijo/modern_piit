<?php
/**
 * Certificate Upload API
 * Handles file uploads from admin dashboard
 */

header('Content-Type: application/json');

require_once 'config.php';
require_once 'db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access. Please login first.']);
    exit();
}

// Check if form data is present
if (!isset($_POST['student_name']) || !isset($_FILES['certificate_file'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit();
}

$student_name = trim($_POST['student_name']);
$file = $_FILES['certificate_file'];

// Validate student name
if (empty($student_name) || strlen($student_name) < 2 || strlen($student_name) > 255) {
    echo json_encode(['success' => false, 'message' => 'Invalid student name. Must be 2-255 characters.']);
    exit();
}

// Check file upload errors
if ($file['error'] !== UPLOAD_ERR_OK) {
    $error_messages = [
        UPLOAD_ERR_INI_SIZE => 'File too large (exceeds server limit)',
        UPLOAD_ERR_FORM_SIZE => 'File too large (exceeds form limit)',
        UPLOAD_ERR_PARTIAL => 'File upload incomplete',
        UPLOAD_ERR_NO_FILE => 'No file selected',
        UPLOAD_ERR_NO_TMP_DIR => 'Temporary folder missing',
        UPLOAD_ERR_CANT_WRITE => 'Cannot write to disk',
    ];
    
    $error_msg = $error_messages[$file['error']] ?? 'Unknown upload error';
    echo json_encode(['success' => false, 'message' => $error_msg]);
    exit();
}

// Check file size
if ($file['size'] > MAX_FILE_SIZE) {
    echo json_encode(['success' => false, 'message' => 'File too large. Maximum 50MB allowed.']);
    exit();
}

// Get file extension
$file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

// Validate file type
if (!in_array($file_ext, ALLOWED_TYPES)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Allowed: ' . implode(', ', ALLOWED_TYPES)]);
    exit();
}

// Create unique filename to prevent conflicts
$unique_filename = time() . '_' . uniqid() . '.' . $file_ext;
$upload_path = UPLOAD_DIR . $unique_filename;

// Create upload directory if it doesn't exist
if (!is_dir(UPLOAD_DIR)) {
    if (!mkdir(UPLOAD_DIR, 0755, true)) {
        echo json_encode(['success' => false, 'message' => 'Cannot create upload directory']);
        exit();
    }
}

// Move uploaded file
if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
    echo json_encode(['success' => false, 'message' => 'Failed to save file. Please try again.']);
    exit();
}

// Save to database
$student_name_escaped = $conn->real_escape_string($student_name);
$admin_user = $_SESSION['admin_username'];

$sql = "INSERT INTO certificates (student_name, certificate_file, file_type, uploaded_by) 
        VALUES ('$student_name_escaped', '$unique_filename', '$file_ext', '" . $conn->real_escape_string($admin_user) . "')";

if ($conn->query($sql) === TRUE) {
    // Log the action
    $log_sql = "INSERT INTO admin_logs (action, admin_user, description) 
                VALUES ('upload', '" . $conn->real_escape_string($admin_user) . "', 'Uploaded certificate for $student_name_escaped')";
    $conn->query($log_sql);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Certificate uploaded successfully for ' . htmlspecialchars($student_name)
    ]);
} else {
    // Delete the uploaded file if database insertion fails
    unlink($upload_path);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}

$conn->close();
?>
