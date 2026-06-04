<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

requireLogin();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle settings update
    $message = 'Settings updated successfully!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - LFIS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            overflow-y: auto;
            color: white;
        }
        .sidebar .logo {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }
        .sidebar .logo h3 {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }
        .sidebar .nav-menu {
            list-style: none;
        }
        .sidebar .nav-menu li {
            margin-bottom: 10px;
        }
        .sidebar .nav-menu a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }
        .sidebar .nav-menu a:hover,
        .sidebar .nav-menu a.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }
        .sidebar .nav-menu i {
            margin-right: 12px;
        }
        .main-content {
            margin-left: 280px;
            padding: 30px;
        }
        .navbar-top {
            background: white;
            padding: 15px 30px;
            border-bottom: 1px solid #e9ecef;
            margin: -30px -30px 30px -30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar-top h2 {
            color: #333;
            font-weight: 600;
            margin: 0;
        }
        .settings-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        .settings-section h4 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 10px 15px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-save {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-save:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
            transform: translateY(-2px);
        }
        .alert-success {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <h3><i class="bi bi-search-heart"></i> LFIS</h3>
            <p>Admin Panel</p>
        </div>

        <ul class="nav-menu">
            <li><a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li><a href="manage-reports.php"><i class="bi bi-file-earmark-text"></i> Manage Reports</a></li>
            <li><a href="manage-matches.php"><i class="bi bi-link-45deg"></i> Manage Matches</a></li>
            <li><a href="manage-users.php"><i class="bi bi-people"></i> Manage Users</a></li>
            <li><a href="settings.php" class="active"><i class="bi bi-gear"></i> Settings</a></li>
            <li><a href="logout.php"><i class="bi bi-box-arrow-left"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="navbar-top">
            <h2><i class="bi bi-gear"></i> Settings</h2>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- General Settings -->
        <div class="settings-section">
            <h4><i class="bi bi-gear"></i> General Settings</h4>
            <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="siteName" class="form-label">Site Name</label>
                            <input type="text" class="form-control" id="siteName" name="siteName" value="LFIS - Lost and Found Items System">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="siteEmail" class="form-label">Site Email</label>
                            <input type="email" class="form-control" id="siteEmail" name="siteEmail" value="admin@lfis.com">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="maxUploadSize" class="form-label">Max Upload Size (MB)</label>
                            <input type="number" class="form-control" id="maxUploadSize" name="maxUploadSize" value="5" min="1">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="itemsPerPage" class="form-label">Items Per Page</label>
                            <input type="number" class="form-control" id="itemsPerPage" name="itemsPerPage" value="10" min="5">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-save">
                    <i class="bi bi-check"></i> Save General Settings
                </button>
            </form>
        </div>

        <!-- Email Settings -->
        <div class="settings-section">
            <h4><i class="bi bi-envelope"></i> Email Settings</h4>
            <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="smtpHost" class="form-label">SMTP Host</label>
                            <input type="text" class="form-control" id="smtpHost" name="smtpHost" value="smtp.gmail.com" placeholder="e.g., smtp.gmail.com">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="smtpPort" class="form-label">SMTP Port</label>
                            <input type="number" class="form-control" id="smtpPort" name="smtpPort" value="587">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="smtpEmail" class="form-label">SMTP Email</label>
                            <input type="email" class="form-control" id="smtpEmail" name="smtpEmail" placeholder="your-email@gmail.com">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="smtpPassword" class="form-label">SMTP Password</label>
                            <input type="password" class="form-control" id="smtpPassword" name="smtpPassword">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="enableEmails" name="enableEmails" checked>
                        <label class="form-check-label" for="enableEmails">
                            Enable email notifications
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-save">
                    <i class="bi bi-check"></i> Save Email Settings
                </button>
            </form>
        </div>

        <!-- System Settings -->
        <div class="settings-section">
            <h4><i class="bi bi-sliders"></i> System Settings</h4>
            <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sessionTimeout" class="form-label">Session Timeout (minutes)</label>
                            <input type="number" class="form-control" id="sessionTimeout" name="sessionTimeout" value="30" min="5">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="matchThreshold" class="form-label">Match Confidence Threshold (%)</label>
                            <input type="number" class="form-control" id="matchThreshold" name="matchThreshold" value="85" min="0" max="100">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Features</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="enableReports" name="enableReports" checked>
                        <label class="form-check-label" for="enableReports">
                            Enable user reports
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="enableMatching" name="enableMatching" checked>
                        <label class="form-check-label" for="enableMatching">
                            Enable automatic matching
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="enableNotifications" name="enableNotifications" checked>
                        <label class="form-check-label" for="enableNotifications">
                            Enable push notifications
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-save">
                    <i class="bi bi-check"></i> Save System Settings
                </button>
            </form>
        </div>

        <!-- Security Settings -->
        <div class="settings-section">
            <h4><i class="bi bi-shield-lock"></i> Security Settings</h4>
            <form method="POST">
                <div class="form-group">
                    <label for="newPassword" class="form-label">Change Admin Password</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Leave empty to keep current password">
                </div>

                <div class="form-group">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password">
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    <strong>Security Reminder:</strong> Change your password regularly and use a strong password.
                </div>

                <button type="submit" class="btn btn-save">
                    <i class="bi bi-check"></i> Update Security Settings
                </button>
            </form>
        </div>

        <!-- Backup & Maintenance -->
        <div class="settings-section">
            <h4><i class="bi bi-cloud-download"></i> Backup & Maintenance</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Database Backup</label>
                        <p class="text-muted">Last backup: May 28, 2026 at 10:30 AM</p>
                        <button type="button" class="btn btn-save">
                            <i class="bi bi-download"></i> Create Backup Now
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">System Logs</label>
                        <p class="text-muted">View system activity and error logs</p>
                        <button type="button" class="btn btn-save">
                            <i class="bi bi-file-earmark-text"></i> View Logs
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
