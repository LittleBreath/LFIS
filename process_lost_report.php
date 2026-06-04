<?php
/**
 * Process Lost Item Report
 */

session_start();
require_once 'admin/config.php';
require_once 'admin/functions.php';

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        $required_fields = array('itemName', 'category', 'description', 'dateLost', 'lostLocation', 'fullName', 'email', 'phone');
        
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

        // Prepare data array
        $data = array(
            'itemName' => htmlspecialchars($_POST['itemName']),
            'category' => htmlspecialchars($_POST['category']),
            'description' => htmlspecialchars($_POST['description']),
            'dateLost' => $_POST['dateLost'],
            'timeLost' => $_POST['timeLost'] ?? NULL,
            'lostLocation' => htmlspecialchars($_POST['lostLocation']),
            'building' => htmlspecialchars($_POST['building'] ?? ''),
            'fullName' => htmlspecialchars($_POST['fullName']),
            'email' => htmlspecialchars($_POST['email']),
            'phone' => $phone,
            'institution' => htmlspecialchars($_POST['institution'] ?? ''),
            'reward' => floatval($_POST['reward'] ?? 0)
        );

        // Handle file upload
        $photo_url = NULL;
        if (isset($_FILES['itemPhoto']) && $_FILES['itemPhoto']['error'] === UPLOAD_ERR_OK) {
            $photo_url = uploadFile($_FILES['itemPhoto']);
            if (!$photo_url) {
                throw new Exception('Failed to upload image. Please check file size and format.');
            }
        }

        // Insert into database
        $sql = "INSERT INTO lost_reports (item_name, category, description, date_lost, time_lost, location, building, full_name, email, phone, institution, reward, photo_url, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Database error: ' . $conn->error);
        }

        $stmt->bind_param("sssssssssssds", 
            $data['itemName'],
            $data['category'],
            $data['description'],
            $data['dateLost'],
            $data['timeLost'],
            $data['lostLocation'],
            $data['building'],
            $data['fullName'],
            $data['email'],
            $data['phone'],
            $data['institution'],
            $data['reward'],
            $photo_url
        );

        if (!$stmt->execute()) {
            throw new Exception('Failed to submit report: ' . $stmt->error);
        }

        $report_id = $conn->insert_id;

        // Log the action
        $admin_log_sql = "INSERT INTO admin_logs (action, description, ip_address, created_at) 
                         VALUES (?, ?, ?, NOW())";
        $log_stmt = $conn->prepare($admin_log_sql);
        $action = 'New Lost Report';
        $description = 'Lost report submitted: ' . $data['itemName'] . ' by ' . $data['fullName'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $log_stmt->bind_param("sss", $action, $description, $ip);
        $log_stmt->execute();

        $response['success'] = true;
        $response['message'] = 'Lost item report submitted successfully! Report ID: #' . $report_id . '. Our admin team will review your report shortly.';
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
