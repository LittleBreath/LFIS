<?php
/**
 * System Check and Installation Verification
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LFIS - Installation Checker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .check-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }
        .check-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .check-header h1 {
            color: #667eea;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .check-item {
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            border-left: 5px solid #ccc;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .check-item.pass {
            background: #d4edda;
            border-left-color: #28a745;
        }
        .check-item.fail {
            background: #f8d7da;
            border-left-color: #dc3545;
        }
        .check-item.warning {
            background: #fff3cd;
            border-left-color: #ffc107;
        }
        .check-icon {
            font-size: 1.5rem;
            min-width: 30px;
            text-align: center;
        }
        .check-text h6 {
            margin: 0;
            font-weight: 600;
        }
        .check-text small {
            display: block;
            margin-top: 5px;
        }
        .next-steps {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        .btn-proceed {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 40px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            transition: all 0.3s;
        }
        .btn-proceed:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="check-container">
        <div class="check-header">
            <h1><i class="bi bi-search-heart"></i> LFIS</h1>
            <p class="text-muted">System Requirements Check</p>
        </div>

        <?php
        $checks = array(
            'php_version' => array(
                'name' => 'PHP Version',
                'check' => version_compare(PHP_VERSION, '7.4.0', '>='),
                'required' => 'PHP 7.4+',
                'current' => PHP_VERSION
            ),
            'mysqli' => array(
                'name' => 'MySQLi Extension',
                'check' => extension_loaded('mysqli'),
                'required' => 'Required for database connection',
                'current' => extension_loaded('mysqli') ? 'Installed' : 'Not installed'
            ),
            'gd' => array(
                'name' => 'GD Library',
                'check' => extension_loaded('gd'),
                'required' => 'Required for image processing',
                'current' => extension_loaded('gd') ? 'Installed' : 'Not installed'
            ),
            'file_upload' => array(
                'name' => 'File Upload Support',
                'check' => ini_get('file_uploads'),
                'required' => 'Required to upload photos',
                'current' => ini_get('file_uploads') ? 'Enabled' : 'Disabled'
            ),
            'uploads_dir' => array(
                'name' => 'Uploads Directory',
                'check' => is_dir('uploads') && is_writable('uploads'),
                'required' => 'Directory must exist and be writable',
                'current' => is_dir('uploads') ? (is_writable('uploads') ? 'Exists & writable' : 'Exists but not writable') : 'Does not exist'
            ),
            'config_file' => array(
                'name' => 'Configuration File',
                'check' => file_exists('admin/config.php'),
                'required' => 'admin/config.php must exist',
                'current' => file_exists('admin/config.php') ? 'Found' : 'Not found'
            ),
            'functions_file' => array(
                'name' => 'Functions File',
                'check' => file_exists('admin/functions.php'),
                'required' => 'admin/functions.php must exist',
                'current' => file_exists('admin/functions.php') ? 'Found' : 'Not found'
            ),
        );

        $all_pass = true;
        $critical_fail = false;

        foreach ($checks as $key => $check) {
            if (!$check['check']) {
                $all_pass = false;
                if (in_array($key, array('mysqli', 'file_upload', 'config_file', 'functions_file', 'uploads_dir'))) {
                    $critical_fail = true;
                }
            }
        }
        ?>

        <div class="checks">
            <?php foreach ($checks as $key => $check): ?>
                <div class="check-item <?php echo $check['check'] ? 'pass' : (in_array($key, array('mysqli', 'file_upload', 'config_file', 'functions_file', 'uploads_dir')) ? 'fail' : 'warning'); ?>">
                    <div class="check-icon">
                        <?php if ($check['check']): ?>
                            <i class="bi bi-check-circle text-success"></i>
                        <?php else: ?>
                            <i class="bi bi-x-circle text-danger"></i>
                        <?php endif; ?>
                    </div>
                    <div class="check-text">
                        <h6><?php echo htmlspecialchars($check['name']); ?></h6>
                        <small class="text-muted"><?php echo htmlspecialchars($check['required']); ?></small>
                        <small><strong><?php echo htmlspecialchars($check['current']); ?></strong></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="next-steps">
            <?php if ($critical_fail): ?>
                <h6 class="text-danger">
                    <i class="bi bi-exclamation-circle"></i> Critical Issues Found
                </h6>
                <p class="text-muted mb-0">Please fix the critical issues above before proceeding with installation.</p>
            <?php elseif ($all_pass): ?>
                <h6 class="text-success">
                    <i class="bi bi-check-circle"></i> All Systems Ready!
                </h6>
                <p class="text-muted">Your system meets all requirements. Next steps:</p>
                <ol class="text-muted">
                    <li>Create the MySQL database using <code>admin/database.sql</code></li>
                    <li>Update database credentials in <code>admin/config.php</code></li>
                    <li>Verify the <code>uploads/</code> directory has write permissions</li>
                    <li>Visit the home page or admin panel to start using LFIS</li>
                </ol>
                <a href="index.php" class="btn-proceed">
                    <i class="bi bi-arrow-right"></i> Go to Home Page
                </a>
            <?php else: ?>
                <h6 class="text-warning">
                    <i class="bi bi-exclamation-triangle"></i> Some Issues Found
                </h6>
                <p class="text-muted mb-0">Please review and fix the issues above.</p>
            <?php endif; ?>
        </div>

        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
            <small class="text-muted">&copy; 2026 BintDjango. LFIS v1.0</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
