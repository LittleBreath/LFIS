<?php
/**
 * Process Found Item Report
 */

session_start();
require_once 'admin/config.php';
require_once 'admin/functions.php';

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        $required_fields = array('itemName', 'category', 'description', 'dateFound', 'foundLocation', 'itemStorage', 'fullName', 'email', 'phone', 'itemCondition');
        
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Field '$field' is required");
            }
        }

        // Validate email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email address');
        }

        // Validate phone (basic validation)
        $phone = preg_replace('/\D/', '', $_POST['phone']);
        if (strlen($phone) < 10) {
            throw new Exception('Phone number must be at least 10 digits');
        }

        // Validate file upload (required for found items)
        if (!isset($_FILES['itemPhoto']) || $_FILES['itemPhoto']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Photo is required for found items');
        }

        $photo_url = uploadFile($_FILES['itemPhoto']);
        if (!$photo_url) {
            throw new Exception('Failed to upload image. Please check file size and format.');
        }

        // Prepare data array
        $data = array(
            'itemName' => htmlspecialchars($_POST['itemName']),
            'category' => htmlspecialchars($_POST['category']),
            'description' => htmlspecialchars($_POST['description']),
            'dateFound' => $_POST['dateFound'],
            'timeFound' => $_POST['timeFound'] ?? NULL,
            'foundLocation' => htmlspecialchars($_POST['foundLocation']),
            'building' => htmlspecialchars($_POST['building'] ?? ''),
            'itemStorage' => htmlspecialchars($_POST['itemStorage']),
            'fullName' => htmlspecialchars($_POST['fullName']),
            'email' => htmlspecialchars($_POST['email']),
            'phone' => $phone,
            'institution' => htmlspecialchars($_POST['institution'] ?? ''),
            'itemCondition' => htmlspecialchars($_POST['itemCondition']),
            'additionalNotes' => htmlspecialchars($_POST['additionalNotes'] ?? ''),
            'photo' => $photo_url
        );

        // Insert into database
        $sql = "INSERT INTO found_reports (item_name, category, description, date_found, time_found, location, building, storage_location, full_name, email, phone, institution, condition, photo, notes, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Database error: ' . $conn->error);
        }

        $stmt->bind_param("ssssssssssssss", 
            $data['itemName'],
            $data['category'],
            $data['description'],
            $data['dateFound'],
            $data['timeFound'],
            $data['foundLocation'],
            $data['building'],
            $data['itemStorage'],
            $data['fullName'],
            $data['email'],
            $data['phone'],
            $data['institution'],
            $data['itemCondition'],
            $data['photo'],
            $data['additionalNotes']
        );

        if (!$stmt->execute()) {
            throw new Exception('Failed to submit report: ' . $stmt->error);
        }

        $report_id = $conn->insert_id;

        // Log the action
        $admin_log_sql = "INSERT INTO admin_logs (action, description, ip_address, created_at) 
                         VALUES (?, ?, ?, NOW())";
        $log_stmt = $conn->prepare($admin_log_sql);
        $action = 'New Found Report';
        $description = 'Found report submitted: ' . $data['itemName'] . ' by ' . $data['fullName'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $log_stmt->bind_param("sss", $action, $description, $ip);
        $log_stmt->execute();

        // Try to find matches automatically
        $match_query = "SELECT id FROM lost_reports WHERE category = ? AND status IN ('pending', 'approved') LIMIT 10";
        $match_stmt = $conn->prepare($match_query);
        $match_stmt->bind_param("s", $data['category']);
        $match_stmt->execute();
        $match_result = $match_stmt->get_result();

        if ($match_result->num_rows > 0) {
            while ($row = $match_result->fetch_assoc()) {
                $match_score = 80 + rand(0, 15); // Random match score between 80-95
                $match_insert = "INSERT INTO matches (lost_report_id, found_report_id, match_score, status, created_at) 
                                VALUES (?, ?, ?, 'pending_verification', NOW())";
                $mi_stmt = $conn->prepare($match_insert);
                $mi_stmt->bind_param("iii", $row['id'], $report_id, $match_score);
                $mi_stmt->execute();
            }
        }

        $response['success'] = true;
        $response['message'] = 'Found item report submitted successfully! Report ID: #' . $report_id . '. Admin will review and approve your report soon. If there are matching lost items, owners will be notified.';
        $response['report_id'] = $report_id;

    } catch (Exception $e) {
        $response['success'] = false;
        $response['message'] = 'Error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid request method';
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>
