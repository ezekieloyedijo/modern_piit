<?php
require_once 'config.php';
require_once 'db_connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-login.php");
    exit();
}

if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > SESSION_TIMEOUT) {
    session_destroy();
    header("Location: admin-login.php?expired=1");
    exit();
}

$_SESSION['login_time'] = time();


// DELETE CERTIFICATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {

    if (isset($_POST['cert_id'])) {

        $cert_id = (int) $_POST['cert_id'];

        $stmt = $conn->prepare("SELECT certificate_file FROM certificates WHERE id = ?");
        $stmt->bind_param("i", $cert_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {

            $row = $result->fetch_assoc();

            $file_path = UPLOAD_DIR . $row['certificate_file'];

            if (file_exists($file_path)) {
                unlink($file_path);
            }

            $stmt = $conn->prepare("DELETE FROM certificates WHERE id = ?");
            $stmt->bind_param("i", $cert_id);
            $stmt->execute();

            header("Location: admin-dashboard.php?deleted=1");
            exit();
        }
    }
}


// SEARCH
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if (!empty($search)) {

    $searchTerm = "%" . $search . "%";

    $stmt = $conn->prepare("
        SELECT * FROM certificates
        WHERE student_name LIKE ?
        ORDER BY upload_date DESC
    ");

    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();

    $result = $stmt->get_result();

} else {

    $result = $conn->query("
        SELECT * FROM certificates
        ORDER BY upload_date DESC
        LIMIT 50
    ");
}

$certificates = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $certificates[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Admin Dashboard</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            background:#f5f7fa;
            font-family:Arial, sans-serif;
            color:#222;
        }

        .dashboard-container{
            max-width:1200px;
            margin:auto;
            padding:30px 20px;
        }

        .top-bar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:30px;
            flex-wrap:wrap;
            gap:15px;
        }

        .top-bar h2{
            font-size:28px;
            color:#1e293b;
        }

        .top-bar p{
            color:#6b778b;
            margin-top:5px;
            font-size:14px;
        }

        .logout-btn{
            background:#ef4444;
            color:white;
            padding:10px 18px;
            border-radius:8px;
            text-decoration:none;
            font-weight:bold;
        }

        .card-box{
            background:white;
            border-radius:16px;
            padding:25px;
            margin-bottom:25px;
            box-shadow:0 5px 20px rgba(0,0,0,0.05);
        }

        .card-title{
            margin-bottom:20px;
            font-size:22px;
            color:#1e293b;
        }

        .sub-text{
            color:#6b778b;
            margin-bottom:25px;
            font-size:14px;
        }

        .form-control{
            width:100%;
            padding:14px;
            border:1px solid #dbe1ea;
            border-radius:10px;
            margin-bottom:15px;
            font-size:15px;
        }

        .form-control:focus{
            outline:none;
            border-color:#687eff;
        }

        .btn-primary{
            background:#687eff;
            color:white;
            border:none;
            padding:14px 20px;
            border-radius:10px;
            cursor:pointer;
            font-weight:bold;
            width:100%;
        }

        .btn-primary:hover{
            opacity:.9;
        }

        .btn-primary:disabled{
            background:#9ca3af;
            cursor:not-allowed;
        }

        .message-box{
            margin-top:15px;
            padding:12px;
            border-radius:10px;
            font-size:14px;
        }

        .success{
            background:#dcfce7;
            color:#166534;
        }

        .error{
            background:#fee2e2;
            color:#991b1b;
        }

        .search-box{
            margin-bottom:20px;
        }

        .search-form{
            display:flex;
            gap:10px;
            flex-wrap:wrap;
        }

        .search-btn{
            background:#111827;
            color:white;
            border:none;
            padding:14px 20px;
            border-radius:10px;
            cursor:pointer;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table th{
            background:#f8fafc;
            padding:14px;
            text-align:left;
            color:#475569;
            font-size:14px;
        }

        table td{
            padding:16px 14px;
            border-top:1px solid #eef2f7;
        }

        .badge{
            padding:6px 12px;
            border-radius:30px;
            font-size:12px;
            font-weight:bold;
        }

        .badge-pdf{
            background:#fee2e2;
            color:#991b1b;
        }

        .badge-image{
            background:#dbeafe;
            color:#1d4ed8;
        }

        .action-buttons{
            display:flex;
            gap:10px;
            flex-wrap:wrap;
        }

        .btn-view{
            background:#687eff;
            color:white;
            padding:8px 14px;
            border-radius:8px;
            text-decoration:none;
            font-size:14px;
        }

        .btn-delete{
            background:#ef4444;
            color:white;
            border:none;
            padding:8px 14px;
            border-radius:8px;
            cursor:pointer;
            font-size:14px;
        }

        .spinner{
            width:18px;
            height:18px;
            border:3px solid rgba(255,255,255,0.3);
            border-top:3px solid white;
            border-radius:50%;
            display:inline-block;
            animation:spin 1s linear infinite;
            margin-right:8px;
            vertical-align:middle;
        }
        
        .clear-btn{
            background:#e2e8f0;
            color:#334155;
            padding:14px 20px;
            border-radius:10px;
            text-decoration:none;
            font-weight:600;
            transition:0.3s;
        }
        
        .clear-btn:hover{
            background:#cbd5e1;
        }

        @keyframes spin{
            100%{
                transform:rotate(360deg);
            }
        }

        @media(max-width:768px){

            .top-bar{
                flex-direction:column;
                align-items:flex-start;
            }

            table{
                display:block;
                overflow-x:auto;
            }
        }

    </style>
</head>

<body>

<div class="dashboard-container">

    <div class="top-bar">

        <div>
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></h2>
            <p>Manage student certificates seamlessly</p>
        </div>

        <a href="admin-logout.php" class="logout-btn">Logout</a>

    </div>


    <?php if (isset($_GET['deleted'])): ?>
        <div class="message-box success">
            Certificate deleted successfully
        </div>
    <?php endif; ?>


    <!-- UPLOAD -->
    <div class="card-box">

        <h3 class="card-title">Upload Certificate</h3>

        <p class="sub-text">
            Upload student certificate securely into the system.
        </p>

        <form id="uploadForm" enctype="multipart/form-data">

            <input
                type="text"
                name="student_name"
                id="studentName"
                class="form-control"
                placeholder="Enter Student Name"
            >

            <input
                type="file"
                name="certificate_file"
                id="certificateFile"
                class="form-control"
            >

            <button type="submit" class="btn-primary" id="uploadBtn">
                Upload Certificate
            </button>

        </form>

        <div id="uploadMessage"></div>

    </div>


    <!-- CERTIFICATES -->
    <div class="card-box">

        <h3 class="card-title">Certificates</h3>

        <p class="sub-text">
            Search and manage uploaded certificates.
        </p>

        <!-- SEARCH -->
       <div class="search-box">

        <form method="GET" class="search-form">

          <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Search student name..."
            value="<?php echo htmlspecialchars($search); ?>"
          >

          <button type="submit" class="search-btn">
            Search
          </button>

          <?php if(!empty($search)): ?>

            <a href="admin-dashboard.php" class="clear-btn">
                Clear
            </a>

          <?php endif; ?>

        </form>

    </div>


        <?php if(count($certificates) > 0): ?>

            <table>

                <tr>
                    <th>Student Name</th>
                    <th>File Type</th>
                    <th>Upload Date</th>
                    <th>Actions</th>
                </tr>

                <?php foreach($certificates as $cert): ?>

                    <?php
                        $ext = strtoupper(pathinfo($cert['certificate_file'], PATHINFO_EXTENSION));
                        $type = ($ext === 'PDF') ? 'PDF' : 'IMAGE';
                    ?>

                    <tr>

                        <td>
                            <?php echo htmlspecialchars($cert['student_name']); ?>
                        </td>

                        <td>

                            <?php if($type === 'PDF'): ?>

                                <span class="badge badge-pdf">PDF</span>

                            <?php else: ?>

                                <span class="badge badge-image">IMAGE</span>

                            <?php endif; ?>

                        </td>

                        <td>
                            <?php echo date('F d, Y', strtotime($cert['upload_date'])); ?>
                        </td>

                        <td>

                            <div class="action-buttons">

                                <a
                                    href="certificates/<?php echo htmlspecialchars($cert['certificate_file']); ?>"
                                    target="_blank"
                                    class="btn-view"
                                >
                                    View
                                </a>

                                <form method="POST">

                                    <input type="hidden" name="action" value="delete">

                                    <input
                                        type="hidden"
                                        name="cert_id"
                                        value="<?php echo $cert['id']; ?>"
                                    >

                                    <button class="btn-delete">
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </table>

        <?php else: ?>

            <p>No certificates found</p>

        <?php endif; ?>

    </div>

</div>



<script>

const uploadForm = document.getElementById('uploadForm');
const uploadBtn = document.getElementById('uploadBtn');
const uploadMessage = document.getElementById('uploadMessage');

uploadForm.addEventListener('submit', function(e){

    e.preventDefault();

    const studentName = document.getElementById('studentName').value.trim();
    const fileInput = document.getElementById('certificateFile');

    // VALIDATION
    if(studentName.length < 6){

        uploadMessage.innerHTML = `
            <div class="message-box error">
                Student name must be more than 6 characters
            </div>
        `;

        return;
    }

    if(fileInput.files.length === 0){

        uploadMessage.innerHTML = `
            <div class="message-box error">
                Please select a certificate file
            </div>
        `;

        return;
    }

    // PREVENT DOUBLE CLICK
    uploadBtn.disabled = true;

    uploadBtn.innerHTML = `
        <span class="spinner"></span>
        Uploading...
    `;

    const formData = new FormData(uploadForm);

    fetch('api-upload.php', {
        method:'POST',
        body:formData
    })

    .then(res => res.json())

    .then(data => {

        if(data.success){

            uploadMessage.innerHTML = `
                <div class="message-box success">
                    Certificate uploaded successfully
                </div>
            `;

            setTimeout(() => {
                location.reload();
            }, 1500);

        } else {

            uploadMessage.innerHTML = `
                <div class="message-box error">
                    ${data.message}
                </div>
            `;

            uploadBtn.disabled = false;

            uploadBtn.innerHTML = 'Upload Certificate';
        }
    })

    .catch(err => {

        uploadMessage.innerHTML = `
            <div class="message-box error">
                Error uploading file
            </div>
        `;

        uploadBtn.disabled = false;

        uploadBtn.innerHTML = 'Upload Certificate';
    });

});

</script>

</body>
</html>