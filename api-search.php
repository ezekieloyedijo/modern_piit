<?php
/**
 * Certificate Search API
 * Handles search queries from the public search page
 */

header('Content-Type: application/json');

require_once 'config.php';
require_once 'db_connect.php';

// Get search query
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

if (empty($search) || strlen($search) < 2) {
    echo json_encode(['success' => false, 'results' => [], 'message' => 'Please enter at least 2 characters']);
    exit();
}

// Prevent SQL injection
$search_term = '%' . $conn->real_escape_string($search) . '%';

// Search for certificates
$sql = "SELECT id, student_name, certificate_file, file_type, upload_date 
        FROM certificates 
        WHERE student_name LIKE '$search_term' 
        ORDER BY student_name ASC, upload_date DESC";

$result = $conn->query($sql);
$certificates = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Check if file exists
        $file_path = 'certificates/' . $row['certificate_file'];
        if (file_exists($file_path)) {
            $certificates[] = [
                'id' => $row['id'],
                'student_name' => htmlspecialchars($row['student_name']),
                'file_type' => strtoupper($row['file_type']),
                'file_path' => $file_path,
                'upload_date' => date('F d, Y', strtotime($row['upload_date']))
            ];
        }
    }
}

if (count($certificates) > 0) {
    echo json_encode([
        'success' => true,
        'results' => $certificates,
        'count' => count($certificates)
    ]);
} else {
    echo json_encode([
        'success' => false,
        'results' => [],
        'message' => 'No certificates found for "' . htmlspecialchars($search) . '"'
    ]);
}

$conn->close();
?>
