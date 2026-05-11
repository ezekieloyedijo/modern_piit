<?php
require_once 'config.php';

// =======================
// HANDLE LOGIN (BEFORE HTML)
// =======================
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {

        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        $_SESSION['login_time'] = time();

        header("Location: admin-dashboard.php");
        exit();

    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Certificate Management</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .admin-login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #687eff 0%, #f87a53 100%);
            padding: 20px;
        }
        
        .admin-login-container {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 50px;
            max-width: 450px;
            width: 100%;
        }
        
        .admin-login-container h1 {
            color: #052143;
            margin-bottom: 10px;
            font-size: 28px;
            font-weight: 700;
        }
        
        .admin-login-container p {
            color: #6b778b;
            margin-bottom: 30px;
            font-size: 14px;
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
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #d1e3fb;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            font-family: var(--fistudy-font);
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #687eff;
            box-shadow: 0 0 0 3px rgba(104, 126, 255, 0.1);
        }
        
        .btn-login {
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
            margin-top: 10px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(104, 126, 255, 0.4);
        }
        
        .alert-box {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
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
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: #687eff;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .back-link a:hover {
            color: #f87a53;
        }
        
        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo-section img {
            height: 60px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="admin-login-wrapper">
        <div class="admin-login-container">
            <div class="logo-section">
                <img src="./Images/pimg/PIITBrochure.png" alt="PIIT Logo">
                <h3 style="color: #052143; margin: 0;">PIIT Admin</h3>
            </div>
            
            <h1>Admin Login</h1>
            <p>Access the certificate management system</p>
            
            <?php
           
            
            if ($_POST && isset($_POST['username']) && isset($_POST['password'])) {
                require_once 'config.php';
                
                $username = htmlspecialchars($_POST['username']);
                $password = htmlspecialchars($_POST['password']);
                
                // In production, hash passwords properly using password_hash() and password_verify()
                if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_username'] = $username;
                    $_SESSION['login_time'] = time();
                    header("Location: admin-dashboard.php");
                    exit();
                } else {
                    echo '<div class="alert-box alert-danger">❌ Invalid username or password</div>';
                }
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter password" required>
                </div>
                
                <button type="submit" class="btn-login">Login</button>
            </form>
            
            <div class="back-link">
                <a href="index.html">← Back to Website</a>
            </div>
        </div>
    </div>
</body>
</html>
