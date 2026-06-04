<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

requireLogin();

// Sample data for reports
$reports = array(
    array('id' => 1, 'type' => 'lost', 'item_name' => 'Black Backpack', 'category' => 'Bags', 'user_name' => 'John Doe', 'date' => '2026-05-28', 'status' => 'pending', 'location' => 'Main Campus'),
    array('id' => 2, 'type' => 'found', 'item_name' => 'Silver Watch', 'category' => 'Accessories', 'user_name' => 'Jane Smith', 'date' => '2026-05-27', 'status' => 'approved', 'location' => 'Library'),
    array('id' => 3, 'type' => 'lost', 'item_name' => 'Blue Umbrella', 'category' => 'Accessories', 'user_name' => 'Mike Johnson', 'date' => '2026-05-26', 'status' => 'pending', 'location' => 'Cafeteria'),
    array('id' => 4, 'type' => 'found', 'item_name' => 'Red Wallet', 'category' => 'Accessories', 'user_name' => 'Sarah Wilson', 'date' => '2026-05-25', 'status' => 'rejected', 'location' => 'Parking Lot'),
    array('id' => 5, 'type' => 'lost', 'item_name' => 'White Smartphone', 'category' => 'Electronics', 'user_name' => 'Alex Chen', 'date' => '2026-05-24', 'status' => 'pending', 'location' => 'Classroom A101'),
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reports - LFIS Admin</title>
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
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
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
        .badge-lost {
            background-color: #dc3545;
        }
        .badge-found {
            background-color: #28a745;
        }
        .type-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .type-lost {
            background: #ffe5e5;
            color: #dc3545;
        }
        .type-found {
            background: #e5f5e5;
            color: #28a745;
        }
        .status-badge {
            font-size: 0.85rem;
            padding: 5px 12px;
            border-radius: 20px;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-approved {
            background: #d4edda;
            color: #155724;
        }
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        .btn-small {
            padding: 5px 10px;
            font-size: 0.85rem;
            border-radius: 5px;
        }
        .btn-approve {
            background: #28a745;
            border: none;
            color: white;
            cursor: pointer;
        }
        .btn-approve:hover {
            background: #218838;
        }
        .btn-reject {
            background: #dc3545;
            border: none;
            color: white;
            cursor: pointer;
        }
        .btn-reject:hover {
            background: #c82333;
        }
        .btn-view {
            background: #667eea;
            border: none;
            color: white;
            cursor: pointer;
        }
        .btn-view:hover {
            background: #5568d3;
        }
        .pagination-custom {
            margin-top: 20px;
            justify-content: center;
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
            <li><a href="manage-reports.php" class="active"><i class="bi bi-file-earmark-text"></i> Manage Reports</a></li>
            <li><a href="manage-matches.php"><i class="bi bi-link-45deg"></i> Manage Matches</a></li>
            <li><a href="manage-users.php"><i class="bi bi-people"></i> Manage Users</a></li>
            <li><a href="settings.php"><i class="bi bi-gear"></i> Settings</a></li>
            <li><a href="logout.php"><i class="bi bi-box-arrow-left"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="navbar-top">
            <h2><i class="bi bi-file-earmark-text"></i> Manage Reports</h2>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Report Type</label>
                    <select class="form-select" id="filterType">
                        <option value="">All Types</option>
                        <option value="lost">Lost Items</option>
                        <option value="found">Found Items</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="filterStatus">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select class="form-select" id="filterCategory">
                        <option value="">All Categories</option>
                        <option value="Electronics">Electronics</option>
                        <option value="Accessories">Accessories</option>
                        <option value="Documents">Documents</option>
                        <option value="Bags">Bags</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" id="searchBox" placeholder="Item name or user...">
                </div>
            </div>
        </div>

        <!-- Reports Table -->
        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Reported By</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $report): ?>
                    <tr>
                        <td>
                            <span class="type-badge type-<?php echo $report['type']; ?>">
                                <i class="bi bi-<?php echo ($report['type'] === 'lost') ? 'exclamation-circle' : 'hand-thumbs-up'; ?>"></i>
                                <?php echo ucfirst($report['type']); ?>
                            </span>
                        </td>
                        <td><strong><?php echo htmlspecialchars($report['item_name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($report['category']); ?></td>
                        <td><?php echo htmlspecialchars($report['user_name']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($report['date'])); ?></td>
                        <td><?php echo htmlspecialchars($report['location']); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $report['status']; ?>">
                                <?php echo ucfirst($report['status']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-small btn-view" data-bs-toggle="modal" data-bs-target="#viewModal" onclick="viewReport(<?php echo $report['id']; ?>)">
                                    <i class="bi bi-eye"></i> View
                                </button>
                                <?php if ($report['status'] === 'pending'): ?>
                                    <button class="btn btn-small btn-approve" onclick="approveReport(<?php echo $report['id']; ?>)">
                                        <i class="bi bi-check"></i> Approve
                                    </button>
                                    <button class="btn btn-small btn-reject" onclick="rejectReport(<?php echo $report['id']; ?>)">
                                        <i class="bi bi-x"></i> Reject
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
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-custom">
                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </div>

    <!-- View Report Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Report Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="reportDetails">
                        <!-- Details will be loaded here -->
                        <p class="text-muted">Loading report details...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function viewReport(reportId) {
            // In a real application, this would fetch report details from the backend
            document.getElementById('reportDetails').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Item Information</h6>
                        <p><strong>Name:</strong> Black Backpack</p>
                        <p><strong>Category:</strong> Bags</p>
                        <p><strong>Description:</strong> A black canvas backpack with multiple pockets and padded straps.</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Report Information</h6>
                        <p><strong>Date:</strong> May 28, 2026</p>
                        <p><strong>Location:</strong> Main Campus - Library</p>
                        <p><strong>Status:</strong> <span class="badge bg-warning">Pending</span></p>
                    </div>
                </div>
                <hr>
                <h6>Contact Information</h6>
                <p><strong>Reported By:</strong> John Doe</p>
                <p><strong>Email:</strong> john@example.com</p>
                <p><strong>Phone:</strong> +1-555-0123</p>
                <p><strong>Institution:</strong> State University</p>
            `;
        }

        function approveReport(reportId) {
            if (confirm('Are you sure you want to approve this report?')) {
                alert('Report approved successfully!');
                location.reload();
            }
        }

        function rejectReport(reportId) {
            if (confirm('Are you sure you want to reject this report?')) {
                alert('Report rejected successfully!');
                location.reload();
            }
        }

        // Filter functionality
        document.getElementById('filterType').addEventListener('change', filterReports);
        document.getElementById('filterStatus').addEventListener('change', filterReports);
        document.getElementById('filterCategory').addEventListener('change', filterReports);
        document.getElementById('searchBox').addEventListener('keyup', filterReports);

        function filterReports() {
            // In a real application, this would filter the table rows
            console.log('Filters applied');
        }
    </script>
</body>
</html>
