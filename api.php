<?php
/**
 * API Endpoints for Dashboard Data
 */

header('Content-Type: application/json');
require_once 'admin/config.php';
require_once 'admin/functions.php';

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'stats':
            // Get dashboard statistics
            $stats = getDashboardStats($conn);
            echo json_encode(['success' => true, 'data' => $stats]);
            break;

        case 'recent_reports':
            // Get recent reports
            $limit = intval($_GET['limit'] ?? 10);
            $sql = "SELECT 'lost' as type, item_name, full_name, created_at FROM lost_reports 
                    UNION 
                    SELECT 'found' as type, item_name, full_name, created_at FROM found_reports 
                    ORDER BY created_at DESC LIMIT ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            $reports = $result->fetch_all(MYSQLI_ASSOC);
            
            echo json_encode(['success' => true, 'data' => $reports]);
            break;

        case 'lost_reports':
            // Get all lost reports
            $reports = getLostReports($conn);
            echo json_encode(['success' => true, 'data' => $reports]);
            break;

        case 'found_reports':
            // Get all found reports
            $reports = getFoundReports($conn);
            echo json_encode(['success' => true, 'data' => $reports]);
            break;

        case 'item_matches':
            // Get all matches
            $matches = getMatches($conn);
            echo json_encode(['success' => true, 'data' => $matches]);
            break;

        case 'users':
            // Get all users
            $users = getAllUsers($conn);
            echo json_encode(['success' => true, 'data' => $users]);
            break;

        case 'lost_by_category':
            // Get lost reports by category
            $sql = "SELECT category, COUNT(*) as count FROM lost_reports GROUP BY category";
            $result = $conn->query($sql);
            $data = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode(['success' => true, 'data' => $data]);
            break;

        case 'found_by_condition':
            // Get found reports by condition
            $sql = "SELECT condition, COUNT(*) as count FROM found_reports GROUP BY condition";
            $result = $conn->query($sql);
            $data = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode(['success' => true, 'data' => $data]);
            break;

        case 'search':
            // Search reports
            $keyword = '%' . htmlspecialchars($_GET['q'] ?? '') . '%';
            $type = htmlspecialchars($_GET['type'] ?? 'all');
            
            if ($type === 'lost' || $type === 'all') {
                $sql = "SELECT 'lost' as type, * FROM lost_reports WHERE item_name LIKE ? OR description LIKE ? OR full_name LIKE ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $keyword, $keyword, $keyword);
                $stmt->execute();
                $lost = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            }
            
            if ($type === 'found' || $type === 'all') {
                $sql = "SELECT 'found' as type, * FROM found_reports WHERE item_name LIKE ? OR description LIKE ? OR full_name LIKE ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $keyword, $keyword, $keyword);
                $stmt->execute();
                $found = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            }
            
            $results = array_merge($lost ?? [], $found ?? []);
            echo json_encode(['success' => true, 'data' => $results]);
            break;

        case 'report_detail':
            // Get report details
            $id = intval($_GET['id'] ?? 0);
            $type = htmlspecialchars($_GET['type'] ?? 'lost');
            
            if ($type === 'lost') {
                $report = getLostReportById($conn, $id);
            } else {
                $report = getFoundReportById($conn, $id);
            }
            
            if ($report) {
                echo json_encode(['success' => true, 'data' => $report]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Report not found']);
            }
            break;

        case 'match_detail':
            // Get match details
            $id = intval($_GET['id'] ?? 0);
            $match = getMatchById($conn, $id);
            
            if ($match) {
                echo json_encode(['success' => true, 'data' => $match]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Match not found']);
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
exit;
?>
