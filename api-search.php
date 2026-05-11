<?php
header('Content-Type: application/json');

error_reporting(0);
ini_set('display_errors', 0);

try {
    require_once 'config.php';
    require_once 'db_connect.php';

    if (!isset($conn) || $conn->connect_error) {
        throw new Exception("Database connection failed");
    }

    // =======================
    // GET SEARCH INPUT
    // =======================
    $search = isset($_GET['q']) ? trim($_GET['q']) : '';

    if (strlen($search) < 2) {
        echo json_encode([
            'success' => false,
            'results' => [],
            'message' => 'Please enter at least 2 characters'
        ]);
        exit;
    }

    $search_term = "%" . $search . "%";

    // =======================
    // SAFE QUERY
    // =======================
    $stmt = $conn->prepare("
        SELECT id, student_name, certificate_file, upload_date
        FROM certificates
        WHERE student_name LIKE ?
        ORDER BY upload_date DESC
    ");

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $search_term);

    if (!$stmt->execute()) {
        throw new Exception("Query execution failed");
    }

    $result = $stmt->get_result();

    if (!$result) {
        throw new Exception("get_result() not supported on this server. Enable mysqlnd.");
    }

    $certificates = [];

    while ($row = $result->fetch_assoc()) {

        $file = $row['certificate_file'];
        $file_path = "certificates/" . $file;

        if (file_exists($file_path)) {

            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

            $certificates[] = [
                'id' => $row['id'],
                'student_name' => $row['student_name'],
                'file_type' => ($ext === 'pdf') ? 'PDF' : 'IMAGE',
                'file_path' => $file_path,
                'upload_date' => date('F d, Y', strtotime($row['upload_date']))
            ];
        }
    }

    echo json_encode([
        'success' => count($certificates) > 0,
        'count' => count($certificates),
        'results' => $certificates,
        'message' => count($certificates) ? "Found results" : "No certificates found"
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'results' => [],
        'message' => 'Server error occurred',
        'debug' => $e->getMessage() // remove in production later
    ]);
}

exit;
?>