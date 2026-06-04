<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Check if already logged in
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        if (verifyAdminLogin($username, $password)) {
            $_SESSION['admin_id'] = 1;
            $_SESSION['admin_username'] = ADMIN_USERNAME;
            $_SESSION['login_time'] = time();
            header("Location: dashboard.php");
            exit();
        } else {
            $error = 'Invalid username or password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - LFIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            padding: 50px;
            max-width: 400px;
            width: 100%;
        }
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .login-header h1 {
            color: #667eea;
            font-weight: bold;
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .login-header p {
            color: #6c757d;
            margin: 0;
        }
        .login-icon {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 15px;
        }
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: border-color 0.3s;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 10px;
            transition: transform 0.3s, box-shadow 0.3s;
            color: white;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .btn-login:active {
            transform: translateY(0);
        }
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 25px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .remember-me {
            font-size: 0.9rem;
        }
        .login-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 0.85rem;
        }
        .demo-credentials {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 0.85rem;
        }
        .demo-credentials strong {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="login-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h1>LFIS Admin</h1>
            <p>Lost and Found Items System</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username" class="form-label">
                    <i class="bi bi-person"></i> Username
                </label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required autofocus>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="bi bi-key"></i> Password
                </label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
            </div>

            <div class="form-check remember-me mb-4">
                <input class="form-check-input" type="checkbox" id="rememberMe" name="rememberMe">
                <label class="form-check-label" for="rememberMe">
                    Remember me
                </label>
            </div>

            <button type="submit" class="btn btn-login w-100">
                <i class="bi bi-box-arrow-in-right me-2"></i> Login
            </button>
        </form>

        <div class="login-footer">
            <p class="mb-0">&copy; 2026 BintDjango. All Rights Reserved.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
