<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

requireLogin();

// Get statistics
$stats = array(
    'total_lost' => 24,
    'total_found' => 18,
    'pending_approvals' => 5,
    'active_matches' => 3,
    'recovered_items' => 8,
    'pending_lost' => 4
);

// Sample data for recent activities
$recentActivities = array(
    array('type' => 'lost', 'item' => 'Black Backpack', 'user' => 'John Doe', 'time' => '2 hours ago'),
    array('type' => 'found', 'item' => 'Silver Watch', 'user' => 'Jane Smith', 'time' => '4 hours ago'),
    array('type' => 'match', 'item' => 'Red Wallet', 'user' => 'System', 'time' => '6 hours ago'),
    array('type' => 'recovered', 'item' => 'Blue Umbrella', 'user' => 'Admin', 'time' => '1 day ago'),
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - LFIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
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
        .sidebar .logo p {
            font-size: 0.85rem;
            opacity: 0.9;
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
            font-size: 1.1rem;
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
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .navbar-top h2 {
            color: #333;
            font-weight: 600;
            margin: 0;
        }
        .navbar-top .admin-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .navbar-top .admin-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        .navbar-top .dropdown-menu {
            min-width: 200px;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            border-left: 5px solid #667eea;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }
        .stat-card.lost {
            border-left-color: #dc3545;
        }
        .stat-card.found {
            border-left-color: #28a745;
        }
        .stat-card.pending {
            border-left-color: #ffc107;
        }
        .stat-card.match {
            border-left-color: #17a2b8;
        }
        .stat-card.recovered {
            border-left-color: #667eea;
        }
        .stat-card-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        .stat-card-number {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .stat-card-label {
            color: #6c757d;
            font-size: 0.95rem;
        }
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .activity-item {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .activity-item:last-child {
            border-bottom: none;
        }
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
        }
        .activity-icon.lost {
            background: #dc3545;
        }
        .activity-icon.found {
            background: #28a745;
        }
        .activity-icon.match {
            background: #17a2b8;
        }
        .activity-icon.recovered {
            background: #667eea;
        }
        .activity-content {
            flex: 1;
        }
        .activity-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 3px;
        }
        .activity-time {
            color: #6c757d;
            font-size: 0.85rem;
        }
        .btn-action {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s;
        }
        .btn-action:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                min-height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
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
            <li>
                <a href="dashboard.php" class="active">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="manage-reports.php">
                    <i class="bi bi-file-earmark-text"></i> Manage Reports
                </a>
            </li>
            <li>
                <a href="manage-matches.php">
                    <i class="bi bi-link-45deg"></i> Manage Matches
                </a>
            </li>
            <li>
                <a href="manage-users.php">
                    <i class="bi bi-people"></i> Manage Users
                </a>
            </li>
            <li>
                <a href="settings.php">
                    <i class="bi bi-gear"></i> Settings
                </a>
            </li>
            <li>
                <a href="logout.php" onclick="return confirm('Are you sure you want to logout?')">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="navbar-top">
            <h2>
                <i class="bi bi-speedometer2"></i> Dashboard
            </h2>
            <div class="admin-info">
                <div>
                    <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong>
                    <br>
                    <small class="text-muted">Administrator</small>
                </div>
                <div class="admin-avatar">
                    A
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-4">
                <div class="stat-card lost">
                    <div class="stat-card-icon text-danger">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                    <div class="stat-card-number"><?php echo $stats['total_lost']; ?></div>
                    <div class="stat-card-label">Total Lost Reports</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card found">
                    <div class="stat-card-icon text-success">
                        <i class="bi bi-hand-thumbs-up"></i>
                    </div>
                    <div class="stat-card-number"><?php echo $stats['total_found']; ?></div>
                    <div class="stat-card-label">Total Found Reports</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card pending">
                    <div class="stat-card-icon text-warning">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="stat-card-number"><?php echo $stats['pending_approvals']; ?></div>
                    <div class="stat-card-label">Pending Approvals</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card match">
                    <div class="stat-card-icon text-info">
                        <i class="bi bi-link-45deg"></i>
                    </div>
                    <div class="stat-card-number"><?php echo $stats['active_matches']; ?></div>
                    <div class="stat-card-label">Active Matches</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card recovered">
                    <div class="stat-card-icon text-primary">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-card-number"><?php echo $stats['recovered_items']; ?></div>
                    <div class="stat-card-label">Recovered Items</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-card-icon text-secondary">
                        <i class="bi bi-hourglass-top"></i>
                    </div>
                    <div class="stat-card-number"><?php echo $stats['pending_lost']; ?></div>
                    <div class="stat-card-label">Pending Lost Items</div>
                </div>
            </div>
        </div>

        <!-- Charts and Activities -->
        <div class="row">
            <div class="col-lg-8">
                <div class="chart-container">
                    <h5 class="section-title mb-4">
                        <i class="bi bi-graph-up"></i> Reports Overview (Last 7 Days)
                    </h5>
                    <canvas id="reportsChart"></canvas>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="chart-container">
                    <h5 class="section-title mb-4">
                        <i class="bi bi-pie-chart"></i> Reports by Category
                    </h5>
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="row">
            <div class="col-lg-12">
                <div class="chart-container">
                    <h5 class="section-title">
                        <i class="bi bi-activity"></i> Recent Activities
                    </h5>

                    <div style="background: white;">
                        <?php foreach ($recentActivities as $activity): ?>
                            <div class="activity-item">
                                <div class="activity-icon <?php echo $activity['type']; ?>">
                                    <?php
                                    $icons = array(
                                        'lost' => 'bi-exclamation-circle',
                                        'found' => 'bi-hand-thumbs-up',
                                        'match' => 'bi-link-45deg',
                                        'recovered' => 'bi-check-circle'
                                    );
                                    ?>
                                    <i class="bi <?php echo $icons[$activity['type']]; ?>"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">
                                        <?php
                                        $titles = array(
                                            'lost' => 'New Lost Report',
                                            'found' => 'New Found Report',
                                            'match' => 'New Match Found',
                                            'recovered' => 'Item Recovered'
                                        );
                                        echo $titles[$activity['type']] . ' - ' . $activity['item'];
                                        ?>
                                    </div>
                                    <div class="activity-time">
                                        by <?php echo $activity['user']; ?> · <?php echo $activity['time']; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Reports Chart
        const reportsCtx = document.getElementById('reportsChart').getContext('2d');
        new Chart(reportsCtx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [
                    {
                        label: 'Lost Reports',
                        data: [4, 6, 5, 8, 7, 6, 5],
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointBackgroundColor: '#dc3545'
                    },
                    {
                        label: 'Found Reports',
                        data: [3, 4, 5, 6, 4, 5, 3],
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointBackgroundColor: '#28a745'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                }
            }
        });

        // Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Electronics', 'Accessories', 'Documents', 'Clothing', 'Bags', 'Others'],
                datasets: [{
                    data: [5, 8, 3, 7, 6, 5],
                    backgroundColor: [
                        '#667eea',
                        '#764ba2',
                        '#f093fb',
                        '#4facfe',
                        '#00f2fe',
                        '#ffc107'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>
