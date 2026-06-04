<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

requireLogin();

// Sample matches data
$matches = array(
    array('id' => 1, 'lost_item' => 'Black Backpack', 'found_item' => 'Dark Bag', 'match_score' => 95, 'status' => 'pending_verification', 'created_at' => '2026-05-28'),
    array('id' => 2, 'lost_item' => 'Silver Watch', 'found_item' => 'Silver Wristwatch', 'match_score' => 92, 'status' => 'approved', 'created_at' => '2026-05-27'),
    array('id' => 3, 'lost_item' => 'Blue Umbrella', 'found_item' => 'Blue Umbrella with Handle', 'match_score' => 88, 'status' => 'pending_verification', 'created_at' => '2026-05-26'),
    array('id' => 4, 'lost_item' => 'Red Wallet', 'found_item' => 'Red Leather Wallet', 'match_score' => 85, 'status' => 'rejected', 'created_at' => '2026-05-25'),
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Matches - LFIS Admin</title>
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
        .match-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-left: 5px solid #667eea;
            transition: all 0.3s;
        }
        .match-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            transform: translateY(-3px);
        }
        .match-card.pending {
            border-left-color: #ffc107;
        }
        .match-card.approved {
            border-left-color: #28a745;
        }
        .match-card.rejected {
            border-left-color: #dc3545;
        }
        .match-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }
        .match-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .match-items {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 15px;
        }
        .match-item {
            flex: 1;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .match-item h6 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }
        .match-item p {
            color: #333;
            font-weight: 500;
            margin: 0;
        }
        .match-arrow {
            font-size: 1.5rem;
            color: #667eea;
        }
        .match-score {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .match-score.high {
            background: #28a745;
        }
        .match-score.medium {
            background: #ffc107;
        }
        .match-score.low {
            background: #dc3545;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
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
        .match-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
        }
        .btn-approve {
            background: #28a745;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-approve:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        .btn-reject {
            background: #dc3545;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-reject:hover {
            background: #c82333;
            transform: translateY(-2px);
        }
        .btn-details {
            background: #667eea;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-details:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
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
            <li><a href="manage-matches.php" class="active"><i class="bi bi-link-45deg"></i> Manage Matches</a></li>
            <li><a href="manage-users.php"><i class="bi bi-people"></i> Manage Users</a></li>
            <li><a href="settings.php"><i class="bi bi-gear"></i> Settings</a></li>
            <li><a href="logout.php"><i class="bi bi-box-arrow-left"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="navbar-top">
            <h2><i class="bi bi-link-45deg"></i> Manage Matches</h2>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Filter by Status</label>
                    <select class="form-select" id="filterStatus">
                        <option value="">All Status</option>
                        <option value="pending_verification">Pending Verification</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sort by Score</label>
                    <select class="form-select" id="sortScore">
                        <option value="highest">Highest Score</option>
                        <option value="lowest">Lowest Score</option>
                        <option value="newest">Newest First</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" id="searchBox" placeholder="Item name...">
                </div>
            </div>
        </div>

        <!-- Matches List -->
        <div id="matchesContainer">
            <?php foreach ($matches as $match): ?>
            <div class="match-card <?php echo $match['status']; ?>">
                <div class="match-header">
                    <div class="match-title">
                        <i class="bi bi-link-45deg"></i>
                        Match #<?php echo $match['id']; ?>
                    </div>
                    <div>
                        <span class="match-score <?php echo ($match['match_score'] >= 90) ? 'high' : (($match['match_score'] >= 85) ? 'medium' : 'low'); ?>">
                            <?php echo $match['match_score']; ?>% Match
                        </span>
                        <span class="status-badge status-<?php echo $match['status']; ?>" style="margin-left: 10px;">
                            <?php echo ucfirst(str_replace('_', ' ', $match['status'])); ?>
                        </span>
                    </div>
                </div>

                <div class="match-items">
                    <div class="match-item">
                        <h6><i class="bi bi-exclamation-circle"></i> Lost Item</h6>
                        <p><?php echo htmlspecialchars($match['lost_item']); ?></p>
                    </div>
                    <div class="match-arrow">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                    <div class="match-item">
                        <h6><i class="bi bi-hand-thumbs-up"></i> Found Item</h6>
                        <p><?php echo htmlspecialchars($match['found_item']); ?></p>
                    </div>
                </div>

                <small class="text-muted">
                    <i class="bi bi-calendar"></i> Found: <?php echo date('M d, Y', strtotime($match['created_at'])); ?>
                </small>

                <?php if ($match['status'] === 'pending_verification'): ?>
                <div class="match-actions">
                    <button class="btn-approve" onclick="approveMatch(<?php echo $match['id']; ?>)">
                        <i class="bi bi-check-circle"></i> Approve Match
                    </button>
                    <button class="btn-reject" onclick="rejectMatch(<?php echo $match['id']; ?>)">
                        <i class="bi bi-x-circle"></i> Reject Match
                    </button>
                    <button class="btn-details" data-bs-toggle="modal" data-bs-target="#detailsModal">
                        <i class="bi bi-info-circle"></i> View Details
                    </button>
                </div>
                <?php else: ?>
                <div class="match-actions">
                    <button class="btn-details" data-bs-toggle="modal" data-bs-target="#detailsModal">
                        <i class="bi bi-info-circle"></i> View Details
                    </button>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- No Matches Message -->
        <div id="noMatches" class="text-center py-5" style="display: none;">
            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc; margin-bottom: 20px;"></i>
            <h4 class="text-muted">No matches found</h4>
            <p class="text-muted">Try adjusting your filters</p>
        </div>
    </div>

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Match Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Lost Item Details</h6>
                            <p><strong>Item Name:</strong> Black Backpack</p>
                            <p><strong>Category:</strong> Bags</p>
                            <p><strong>Description:</strong> A black canvas backpack with multiple pockets.</p>
                            <p><strong>Date Lost:</strong> May 28, 2026</p>
                            <p><strong>Location:</strong> Main Campus - Library</p>
                            <p><strong>Reported By:</strong> John Doe</p>
                            <p><strong>Contact:</strong> john@example.com</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">Found Item Details</h6>
                            <p><strong>Item Name:</strong> Dark Bag</p>
                            <p><strong>Category:</strong> Bags</p>
                            <p><strong>Description:</strong> A dark bag found near the library with similar features.</p>
                            <p><strong>Date Found:</strong> May 28, 2026</p>
                            <p><strong>Location:</strong> Main Campus - Library Near Study Area</p>
                            <p><strong>Reported By:</strong> Jane Smith</p>
                            <p><strong>Contact:</strong> jane@example.com</p>
                        </div>
                    </div>
                    <hr>
                    <h6>Match Analysis</h6>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 95%" aria-label="95% match">95% Match</div>
                    </div>
                    <p class="text-muted mt-2">
                        <small>This match has a high confidence score based on item category, description similarity, and location proximity.</small>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function approveMatch(matchId) {
            if (confirm('Are you sure you want to approve this match? The owners will be notified.')) {
                alert('Match approved successfully! Both parties have been notified.');
                location.reload();
            }
        }

        function rejectMatch(matchId) {
            if (confirm('Are you sure you want to reject this match?')) {
                alert('Match rejected successfully!');
                location.reload();
            }
        }

        // Filter functionality
        document.getElementById('filterStatus').addEventListener('change', filterMatches);
        document.getElementById('sortScore').addEventListener('change', filterMatches);
        document.getElementById('searchBox').addEventListener('keyup', filterMatches);

        function filterMatches() {
            console.log('Filters applied');
            // In a real application, this would filter the matches
        }
    </script>
</body>
</html>
