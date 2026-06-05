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

        // Validate required fields (itemStorage RETURNED)
        $required_fields = array(
            'itemName',
            'category',
            'description',
            'dateFound',
            'foundLocation',
            'itemStorage',
            'fullName',
            'email',
            'phone',
            'itemCondition'
        );

        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Field '$field' is required");
            }
        }

        // Validate email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email address');
        }

        // Validate phone
        $phone = preg_replace('/\D/', '', $_POST['phone']);
        if (strlen($phone) < 10) {
            throw new Exception('Phone number must be at least 10 digits');
        }

        // Photo upload (still required)
        if (!isset($_FILES['itemPhoto']) || $_FILES['itemPhoto']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Photo is required for found items');
        }

        $photo_url = uploadFile($_FILES['itemPhoto']);
        if (!$photo_url) {
            throw new Exception('Failed to upload image. Please check file size and format.');
        }

        // Data array (itemStorage RESTORED)
        $data = array(
            'itemName' => htmlspecialchars($_POST['itemName']),
            'category' => htmlspecialchars($_POST['category']),
            'description' => htmlspecialchars($_POST['description']),
            'dateFound' => $_POST['dateFound'],
            'timeFound' => $_POST['timeFound'] ?? NULL,
            'foundLocation' => htmlspecialchars($_POST['foundLocation']),
            'itemStorage' => htmlspecialchars($_POST['itemStorage']),
            'building' => htmlspecialchars($_POST['building'] ?? ''),
            'fullName' => htmlspecialchars($_POST['fullName']),
            'email' => htmlspecialchars($_POST['email']),
            'phone' => $phone,
            'institution' => htmlspecialchars($_POST['institution'] ?? ''),
            'itemCondition' => htmlspecialchars($_POST['itemCondition']),
            'additionalNotes' => htmlspecialchars($_POST['additionalNotes'] ?? ''),
            'photo' => $photo_url
        );

        // Insert into database (storage_location RESTORED)
        $sql = "INSERT INTO found_reports (
                    item_name,
                    category,
                    description,
                    date_found,
                    time_found,
                    location,
                    building,
                    storage_location,
                    full_name,
                    email,
                    phone,
                    institution,
                    item_condition,
                    photo,
                    notes,
                    status,
                    created_at
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception('Database error: ' . $conn->error);
        }

        $stmt->bind_param(
            "sssssssssssssss",
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

        $response['success'] = true;
        $response['message'] = 'Found item report submitted successfully! Report ID: #' . $report_id;

    } catch (Exception $e) {
        $response['success'] = false;
        $response['message'] = 'Error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid request method';
}

header('Content-Type: application/json');
echo json_encode($response);
exit;
?>