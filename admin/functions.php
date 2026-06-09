<?php
/**
 * Database Helper Functions
 * LFIS - Lost and Found Items System
 */

require_once 'config.php';

// ===== AUTHENTICATION FUNCTIONS =====

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_email']);
}

/**
 * Verify admin login credentials
 */
function verifyAdminLogin($username, $password) {
    if ($username === ADMIN_EMAIL && $password === ADMIN_PASSWORD) {
        return true;
    }
    return false;
}

/**
 * Redirect to login if not authenticated
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

/**
 * Logout user
 */
function logoutAdmin() {
    session_destroy();
    header("Location: login.php");
    exit();
}

// ===== LOST ITEMS FUNCTIONS =====

/**
 * Get all lost reports
 */
function getLostReports($conn) {
    $sql = "SELECT * FROM lost_reports ORDER BY created_at DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get lost report by ID
 */
function getLostReportById($conn, $id) {
    $sql = "SELECT * FROM lost_reports WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

/**
 * Add lost report
 */
function addLostReport($conn, $data) {
    $sql = "INSERT INTO lost_reports (item_name, category, description, date_lost, location, building, full_name, email, phone, institution, reward, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssd", 
        $data['itemName'],
        $data['category'],
        $data['description'],
        $data['dateLost'],
        $data['lostLocation'],
        $data['building'],
        $data['fullName'],
        $data['email'],
        $data['phone'],
        $data['institution'],
        $data['reward']
    );
    
    return $stmt->execute();
}

/**
 * Update lost report status
 */
function updateLostReportStatus($conn, $id, $status) {
    $sql = "UPDATE lost_reports SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);
    return $stmt->execute();
}

// ===== FOUND ITEMS FUNCTIONS =====

/**
 * Get all found reports
 */
function getFoundReports($conn) {
    $sql = "SELECT *, item_condition AS condition FROM found_reports ORDER BY created_at DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get found report by ID
 */
function getFoundReportById($conn, $id) {
    $sql = "SELECT *, item_condition AS condition FROM found_reports WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

/**
 * Add found report
 */
function addFoundReport($conn, $data) {
    if (!empty($data['itemStorage'])) {
        $data['additionalNotes'] = trim($data['additionalNotes'] . '\nStorage location: ' . $data['itemStorage']);
    }

    $sql = "INSERT INTO found_reports (item_name, category, description, date_found, location, building, full_name, email, phone, institution, item_condition, photo, notes, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssss", 
        $data['itemName'],
        $data['category'],
        $data['description'],
        $data['dateFound'],
        $data['foundLocation'],
        $data['building'],
        $data['fullName'],
        $data['email'],
        $data['phone'],
        $data['institution'],
        $data['itemCondition'],
        $data['photo'],
        $data['additionalNotes']
    );
    
    return $stmt->execute();
}

/**
 * Update found report status
 */
function updateFoundReportStatus($conn, $id, $status) {
    $sql = "UPDATE found_reports SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);
    return $stmt->execute();
}

// ===== MATCHES FUNCTIONS =====

/**
 * Create a match between lost and found items
 */
function createMatch($conn, $lost_id, $found_id, $match_score) {
    $sql = "INSERT INTO matches (lost_report_id, found_report_id, match_score, status, created_at) 
            VALUES (?, ?, ?, 'pending_verification', NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $lost_id, $found_id, $match_score);
    return $stmt->execute();
}

/**
 * Get all matches
 */
function getMatches($conn) {
    $sql = "SELECT m.*, lr.item_name as lost_item, fr.item_name as found_item 
            FROM matches m 
            JOIN lost_reports lr ON m.lost_report_id = lr.id 
            JOIN found_reports fr ON m.found_report_id = fr.id 
            ORDER BY m.created_at DESC";
    
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get match by ID
 */
function getMatchById($conn, $id) {
    $sql = "SELECT m.*, lr.*, fr.* 
            FROM matches m 
            JOIN lost_reports lr ON m.lost_report_id = lr.id 
            JOIN found_reports fr ON m.found_report_id = fr.id 
            WHERE m.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

/**
 * Approve a match
 */
function approveMatch($conn, $id) {
    $sql = "UPDATE matches SET status = 'approved' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

/**
 * Reject a match
 */
function rejectMatch($conn, $id) {
    $sql = "UPDATE matches SET status = 'rejected' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// ===== STATISTICS FUNCTIONS =====

/**
 * Get dashboard statistics
 */
function getDashboardStats($conn) {
    $stats = array();
    
    // Total lost reports
    $result = $conn->query("SELECT COUNT(*) as count FROM lost_reports");
    $stats['total_lost'] = $result->fetch_assoc()['count'];
    
    // Total found reports
    $result = $conn->query("SELECT COUNT(*) as count FROM found_reports");
    $stats['total_found'] = $result->fetch_assoc()['count'];
    
    // Pending approvals
    $result = $conn->query("SELECT COUNT(*) as count FROM found_reports WHERE status = 'pending'");
    $stats['pending_approvals'] = $result->fetch_assoc()['count'];
    
    // Active matches
    $result = $conn->query("SELECT COUNT(*) as count FROM matches WHERE status = 'pending_verification'");
    $stats['active_matches'] = $result->fetch_assoc()['count'];
    
    // Recovered items
    $result = $conn->query("SELECT COUNT(*) as count FROM matches WHERE status = 'approved'");
    $stats['recovered_items'] = $result->fetch_assoc()['count'];
    
    // Pending lost reports
    $result = $conn->query("SELECT COUNT(*) as count FROM lost_reports WHERE status = 'pending'");
    $stats['pending_lost'] = $result->fetch_assoc()['count'];
    
    return $stats;
}

/**
 * Get reports by category
 */
function getReportsByCategory($conn) {
    $sql = "SELECT category, COUNT(*) as count FROM lost_reports GROUP BY category";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// ===== FILE UPLOAD FUNCTIONS =====

/**
 * Handle file upload
 */
function uploadFile($file) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    // Check file size
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        return false;
    }
    
    // Create uploads directory if not exists
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }
    
    // Generate unique filename
    $filename = uniqid() . '_' . basename($file['name']);
    $filepath = UPLOAD_DIR . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return UPLOAD_URL . $filename;
    }
    
    return false;
}

// ===== USER MANAGEMENT FUNCTIONS =====

/**
 * Get all reports by a user
 */
function getUserReports($conn, $email) {
    $sql = "SELECT * FROM lost_reports WHERE email = ? UNION SELECT * FROM found_reports WHERE email = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get all unique users
 */
function getAllUsers($conn) {
    $sql = "SELECT DISTINCT email, full_name FROM lost_reports 
            UNION 
            SELECT DISTINCT email, full_name FROM found_reports 
            ORDER BY full_name";
    
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// ===== UTILITY FUNCTIONS =====

/**
 * Format date
 */
function formatDate($date) {
    return date('M d, Y', strtotime($date));
}

/**
 * Format datetime
 */
function formatDateTime($datetime) {
    return date('M d, Y \a\t h:i A', strtotime($datetime));
}

/**
 * Get status badge color
 */
function getStatusBadgeColor($status) {
    $colors = array(
        'pending' => 'warning',
        'approved' => 'success',
        'rejected' => 'danger',
        'recovered' => 'info',
        'pending_verification' => 'warning'
    );
    return isset($colors[$status]) ? $colors[$status] : 'secondary';
}

/**
 * Get category icon
 */
function getCategoryIcon($category) {
    $icons = array(
        'Electronics' => 'bi-phone',
        'Accessories' => 'bi-gem',
        'Documents' => 'bi-file-text',
        'Clothing' => 'bi-bag',
        'Bags' => 'bi-backpack',
        'Keys' => 'bi-key',
        'Jewelry' => 'bi-gem',
        'Books' => 'bi-book'
    );
    return isset($icons[$category]) ? $icons[$category] : 'bi-box';
}

?>
