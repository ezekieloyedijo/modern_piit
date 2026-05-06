<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Certificate Management</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        .admin-dashboard-wrapper {
            min-height: 100vh;
            background: #f5f7fa;
            padding: 20px;
        }
        
        .admin-header {
            background: linear-gradient(135deg, #687eff 0%, #5566dd 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(104, 126, 255, 0.3);
        }
        
        .admin-header h1 {
            color: white;
            margin: 0;
            font-size: 28px;
        }
        
        .logout-btn {
            background: white;
            color: #687eff;
            padding: 10px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .admin-container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .card-section {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }
        
        .card-section h2 {
            color: #052143;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: 700;
            border-bottom: 3px solid #687eff;
            padding-bottom: 15px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #052143;
            font-weight: 600;
            font-size: 14px;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #d1e3fb;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            font-family: var(--fistudy-font);
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #687eff;
            box-shadow: 0 0 0 3px rgba(104, 126, 255, 0.1);
        }
        
        .file-upload-area {
            border: 2px dashed #d1e3fb;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f9fbff;
        }
        
        .file-upload-area:hover {
            border-color: #687eff;
            background: #f0f5ff;
        }
        
        .file-upload-area i {
            font-size: 48px;
            color: #687eff;
            margin-bottom: 15px;
        }
        
        .file-upload-area p {
            color: #6b778b;
            margin: 0;
            font-size: 16px;
        }
        
        .file-upload-area .small {
            color: #999;
            font-size: 12px;
            display: block;
            margin-top: 10px;
        }
        
        .file-input {
            display: none;
        }
        
        .btn-upload {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #687eff 0%, #5566dd 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        
        .btn-upload:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(104, 126, 255, 0.4);
        }
        
        .alert-box {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-danger {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }
        
        .alert-success {
            background: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }
        
        .alert-info {
            background: #eef;
            color: #338;
            border: 1px solid #ccf;
        }
        
        .upload-history {
            margin-top: 40px;
        }
        
        .upload-history h3 {
            color: #052143;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: 700;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        
        .table thead {
            background: #f5f7fa;
        }
        
        .table th {
            padding: 15px;
            text-align: left;
            color: #052143;
            font-weight: 700;
            border-bottom: 2px solid #d1e3fb;
        }
        
        .table td {
            padding: 15px;
            border-bottom: 1px solid #e8eef5;
        }
        
        .table tr:hover {
            background: #f9fbff;
        }
        
        .delete-btn {
            background: #f87a53;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }
        
        .delete-btn:hover {
            background: #e65a33;
        }
        
        .view-btn {
            background: #687eff;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .view-btn:hover {
            background: #5566dd;
        }
        
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }
        
        .spinner {
            border: 3px solid #d1e3fb;
            border-top: 3px solid #687eff;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            display: inline-block;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <?php
    require_once 'config.php';
    require_once 'db_connect.php';
    
    // Check if admin is logged in
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: admin-login.php");
        exit();
    }
    
    // Check session timeout
    if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > SESSION_TIMEOUT) {
        session_destroy();
        echo '<script>alert("Session expired. Please login again."); window.location.href = "admin-login.php";</script>';
        exit();
    }
    
    // Update last activity time
    $_SESSION['login_time'] = time();
    
    // Handle certificate deletion
    if ($_POST && isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['cert_id'])) {
        $cert_id = (int)$_POST['cert_id'];
        
        // Get file path
        $result = $conn->query("SELECT certificate_file FROM certificates WHERE id = $cert_id");
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $file_path = UPLOAD_DIR . $row['certificate_file'];
            
            // Delete file from server
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            
            // Delete from database
            $conn->query("DELETE FROM certificates WHERE id = $cert_id");
            $delete_message = "Certificate deleted successfully!";
        }
    }
    
    // Fetch all certificates
    $certificates = [];
    $result = $conn->query("SELECT * FROM certificates ORDER BY upload_date DESC LIMIT 50");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $certificates[] = $row;
        }
    }
    ?>
    
    <div class="admin-dashboard-wrapper">
        <div class="admin-container">
            <div class="admin-header">
                <div>
                    <h1>📚 Certificate Management System</h1>
                    <p style="margin: 0; opacity: 0.9;">Welcome, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong></p>
                </div>
                <a href="admin-logout.php" class="logout-btn">🚪 Logout</a>
            </div>
            
            <!-- Upload Section -->
            <div class="card-section">
                <h2>📤 Upload Student Certificate</h2>
                
                <?php 
                if (isset($delete_message)) {
                    echo '<div class="alert-box alert-success">✓ ' . $delete_message . '</div>';
                }
                ?>
                
                <form id="uploadForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="student_name">Student Name *</label>
                        <input type="text" id="student_name" name="student_name" placeholder="Enter full student name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="certificate_file">Certificate File (Image or PDF) *</label>
                        <div class="file-upload-area" onclick="document.getElementById('certificate_file').click();">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click to upload or drag and drop</p>
                            <span class="small">Supported: JPG, PNG, GIF, PDF, BMP (Max 50MB)</span>
                        </div>
                        <input type="file" id="certificate_file" name="certificate_file" accept=".jpg,.jpeg,.png,.gif,.pdf,.bmp" required>
                    </div>
                    
                    <button type="submit" class="btn-upload">
                        <span id="btn-text">📤 Upload Certificate</span>
                        <span id="btn-loading" class="loading" style="display: none;">
                            <div class="spinner"></div> Uploading...
                        </span>
                    </button>
                </form>
                
                <div id="uploadMessage"></div>
            </div>
            
            <!-- Recent Uploads Section -->
            <div class="card-section">
                <h2>📋 Recent Certificates (Last 50)</h2>
                
                <?php if (count($certificates) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>File Type</th>
                                <th>Upload Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($certificates as $cert): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($cert['student_name']); ?></td>
                                    <td>
                                        <?php 
                                        $ext = strtoupper(pathinfo($cert['certificate_file'], PATHINFO_EXTENSION));
                                        if ($ext === 'PDF') {
                                            echo '<span style="background: #f87a53; color: white; padding: 3px 8px; border-radius: 4px; font-weight: 600;">PDF</span>';
                                        } else {
                                            echo '<span style="background: #687eff; color: white; padding: 3px 8px; border-radius: 4px; font-weight: 600;">IMAGE</span>';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo date('M d, Y H:i', strtotime($cert['upload_date'])); ?></td>
                                    <td>
                                        <a href="certificates/<?php echo htmlspecialchars($cert['certificate_file']); ?>" target="_blank" class="view-btn">👁 View</a>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this certificate?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="cert_id" value="<?php echo $cert['id']; ?>">
                                            <button type="submit" class="delete-btn">🗑 Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert-box alert-info">
                        ℹ️ No certificates uploaded yet. Start by uploading your first certificate above!
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        // Handle form submission
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const studentName = document.getElementById('student_name').value;
            const certificateFile = document.getElementById('certificate_file').files[0];
            
            if (!studentName || !certificateFile) {
                alert('Please fill all fields');
                return;
            }
            
            // Show loading state
            document.getElementById('btn-text').style.display = 'none';
            document.getElementById('btn-loading').style.display = 'inline-block';
            
            // Create FormData
            const formData = new FormData();
            formData.append('student_name', studentName);
            formData.append('certificate_file', certificateFile);
            
            // Submit via AJAX
            fetch('api-upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('btn-text').style.display = 'inline';
                document.getElementById('btn-loading').style.display = 'none';
                
                const messageDiv = document.getElementById('uploadMessage');
                if (data.success) {
                    messageDiv.innerHTML = '<div class="alert-box alert-success">✓ ' + data.message + '</div>';
                    document.getElementById('uploadForm').reset();
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    messageDiv.innerHTML = '<div class="alert-box alert-danger">✗ ' + data.message + '</div>';
                }
            })
            .catch(error => {
                document.getElementById('btn-text').style.display = 'inline';
                document.getElementById('btn-loading').style.display = 'none';
                document.getElementById('uploadMessage').innerHTML = '<div class="alert-box alert-danger">✗ Error: ' + error + '</div>';
            });
        });
        
        // Drag and drop functionality
        const dropArea = document.querySelector('.file-upload-area');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => {
                dropArea.style.borderColor = '#687eff';
                dropArea.style.background = '#f0f5ff';
            });
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => {
                dropArea.style.borderColor = '#d1e3fb';
                dropArea.style.background = '#f9fbff';
            });
        });
        
        dropArea.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            document.getElementById('certificate_file').files = files;
        });
    </script>
</body>
</html>
