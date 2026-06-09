<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

requireLogin();

// Sample users data
$users = array(
    array('id' => 1, 'name' => 'John Doe', 'email' => 'john@gmail.com', 'phone' => '+255 675 435 234', 'lost_reports' => 3, 'found_reports' => 0, 'joined' => '2026-01-15', 'status' => 'active'),
    array('id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@gmail.com', 'phone' => '+255 666 443 334', 'lost_reports' => 1, 'found_reports' => 4, 'joined' => '2026-02-20', 'status' => 'active'),
    array('id' => 3, 'name' => 'Mike Johnson', 'email' => 'mike@gmail.com', 'phone' => '+255 644 556 665', 'lost_reports' => 2, 'found_reports' => 2, 'joined' => '2026-03-10', 'status' => 'active'),
    array('id' => 4, 'name' => 'Sarah Wilson', 'email' => 'sarah@gmail.com', 'phone' => '+255 699 887 667', 'lost_reports' => 0, 'found_reports' => 3, 'joined' => '2026-04-05', 'status' => 'inactive'),
    array('id' => 5, 'name' => 'Alex Chen', 'email' => 'alex@gmail.com', 'phone' => '+255 766 887 443', 'lost_reports' => 4, 'found_reports' => 1, 'joined' => '2026-05-01', 'status' => 'active'),
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - LFIS Admin</title>
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
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        .table {
            margin: 0;
        }
        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 10px;
        }
        .user-info {
            display: flex;
            align-items: center;
        }
        .user-details h6 {
            margin: 0;
            font-weight: 600;
        }
        .user-details small {
            color: #6c757d;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        .btn-small {
            padding: 6px 12px;
            font-size: 0.85rem;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-view {
            background: #667eea;
            color: white;
        }
        .btn-view:hover {
            background: #5568d3;
        }
        .btn-email {
            background: #17a2b8;
            color: white;
        }
        .btn-email:hover {
            background: #138496;
        }
        .btn-deactivate {
            background: #dc3545;
            color: white;
        }
        .btn-deactivate:hover {
            background: #c82333;
        }
        .btn-activate {
            background: #28a745;
            color: white;
        }
        .btn-activate:hover {
            background: #218838;
        }
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        .stats-mini {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .stats-mini-item {
            flex: 1;
            background: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        .stats-mini-item .number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
        }
        .stats-mini-item .label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 5px;
        }
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
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
            <li><a href="manage-users.php" class="active"><i class="bi bi-people"></i> Manage Users</a></li>
            <li><a href="settings.php"><i class="bi bi-gear"></i> Settings</a></li>
            <li><a href="logout.php"><i class="bi bi-box-arrow-left"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="navbar-top">
            <h2><i class="bi bi-people"></i> Manage Users</h2>
        </div>

        <!-- Stats -->
        <div class="stats-mini">
            <div class="stats-mini-item">
                <div class="number">5</div>
                <div class="label">Total Users</div>
            </div>
            <div class="stats-mini-item">
                <div class="number">4</div>
                <div class="label">Active Users</div>
            </div>
            <div class="stats-mini-item">
                <div class="number">1</div>
                <div class="label">Inactive Users</div>
            </div>
            <div class="stats-mini-item">
                <div class="number">10</div>
                <div class="label">Total Reports</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Filter by Status</label>
                    <select class="form-select" id="filterStatus">
                        <option value="">All Users</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sort by</label>
                    <select class="form-select" id="sortBy">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="name">Name (A-Z)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" id="searchBox" placeholder="Name or email...">
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Lost Reports</th>
                        <th>Found Reports</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar"><?php echo substr($user['name'], 0, 1); ?></div>
                                <div class="user-details">
                                    <h6><?php echo htmlspecialchars($user['name']); ?></h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="mailto:<?php echo $user['email']; ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($user['email']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td>
                            <span class="badge bg-danger"><?php echo $user['lost_reports']; ?></span>
                        </td>
                        <td>
                            <span class="badge bg-success"><?php echo $user['found_reports']; ?></span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($user['joined'])); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $user['status']; ?>">
                                <?php echo ucfirst($user['status']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-small btn-view" data-bs-toggle="modal" data-bs-target="#viewModal" onclick="viewUser(<?php echo $user['id']; ?>)">
                                    <i class="bi bi-eye"></i> View
                                </button>
                                <button class="btn-small btn-email">
                                    <i class="bi bi-envelope"></i> Email
                                </button>
                                <?php if ($user['status'] === 'active'): ?>
                                    <button class="btn-small btn-deactivate" onclick="deactivateUser(<?php echo $user['id']; ?>)">
                                        <i class="bi bi-lock"></i> Deactivate
                                    </button>
                                <?php else: ?>
                                    <button class="btn-small btn-activate" onclick="activateUser(<?php echo $user['id']; ?>)">
                                        <i class="bi bi-unlock"></i> Activate
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation" style="margin-top: 20px;">
            <ul class="pagination justify-content-center">
                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </div>

    <!-- View User Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="userDetails">
                        <p class="text-muted">Loading user details...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function viewUser(userId) {
            // In a real application, this would fetch user details from the backend
            document.getElementById('userDetails').innerHTML = `
                <div class="row">
                    <div class="col-12">
                        <h6 class="mb-3">Contact Information</h6>
                        <p><strong>Name:</strong> John Doe</p>
                        <p><strong>Email:</strong> john@example.com</p>
                        <p><strong>Phone:</strong> +1-555-0123</p>
                        <p><strong>Joined:</strong> January 15, 2026</p>
                    </div>
                </div>
                <hr>
                <h6>Activity Summary</h6>
                <p><strong>Lost Reports:</strong> 3</p>
                <p><strong>Found Reports:</strong> 0</p>
                <p><strong>Recovered Items:</strong> 2</p>
                <p><strong>Last Activity:</strong> May 28, 2026</p>
            `;
        }

        function deactivateUser(userId) {
            if (confirm('Are you sure you want to deactivate this user?')) {
                alert('User deactivated successfully!');
                location.reload();
            }
        }

        function activateUser(userId) {
            if (confirm('Are you sure you want to activate this user?')) {
                alert('User activated successfully!');
                location.reload();
            }
        }

        // Filter functionality
        document.getElementById('filterStatus').addEventListener('change', filterUsers);
        document.getElementById('sortBy').addEventListener('change', filterUsers);
        document.getElementById('searchBox').addEventListener('keyup', filterUsers);

        function filterUsers() {
            console.log('Filters applied');
        }
    </script>
</body>
</html>
