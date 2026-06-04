<?php
/**
 * Process Contact Message
 */

session_start();
require_once 'admin/config.php';
require_once 'admin/functions.php';

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        if (empty($_POST['contactName']) || empty($_POST['contactEmail']) || empty($_POST['contactPhone']) || empty($_POST['contactMessage'])) {
            throw new Exception('All fields are required');
        }

        // Validate email
        if (!filter_var($_POST['contactEmail'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email address');
        }

        // Prepare data
        $name = htmlspecialchars($_POST['contactName']);
        $email = htmlspecialchars($_POST['contactEmail']);
        $phone = htmlspecialchars($_POST['contactPhone']);
        $message = htmlspecialchars($_POST['contactMessage']);
        $match_id = isset($_POST['matchId']) ? intval($_POST['matchId']) : NULL;
        $receiver_email = 'admin@lfis.com'; // Default admin email

        // If match_id is provided, get the receiver's email from the found report
        if ($match_id) {
            $match_query = "SELECT found_report_id FROM matches WHERE id = ?";
            $match_stmt = $conn->prepare($match_query);
            $match_stmt->bind_param("i", $match_id);
            $match_stmt->execute();
            $match_result = $match_stmt->get_result();
            
            if ($match_row = $match_result->fetch_assoc()) {
                $found_query = "SELECT email FROM found_reports WHERE id = ?";
                $found_stmt = $conn->prepare($found_query);
                $found_stmt->bind_param("i", $match_row['found_report_id']);
                $found_stmt->execute();
                $found_result = $found_stmt->get_result();
                
                if ($found_row = $found_result->fetch_assoc()) {
                    $receiver_email = $found_row['email'];
                }
            }
        }

        // Insert contact message into database
        $sql = "INSERT INTO contact_messages (sender_name, sender_email, sender_phone, receiver_email, match_id, message, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, 'sent', NOW())";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Database error: ' . $conn->error);
        }

        $stmt->bind_param("sssisi", $name, $email, $phone, $receiver_email, $match_id, $message);

        if (!$stmt->execute()) {
            throw new Exception('Failed to send message: ' . $stmt->error);
        }

        $message_id = $conn->insert_id;

        // Log the action
        $admin_log_sql = "INSERT INTO admin_logs (action, description, ip_address, created_at) 
                         VALUES (?, ?, ?, NOW())";
        $log_stmt = $conn->prepare($admin_log_sql);
        $action = 'New Contact Message';
        $description = 'Contact message sent from ' . $name . ' to ' . $receiver_email;
        $ip = $_SERVER['REMOTE_ADDR'];
        $log_stmt->bind_param("sss", $action, $description, $ip);
        $log_stmt->execute();

        // TODO: Send email notification to receiver
        // sendEmail($receiver_email, "New Message from " . $name, $message);

        $response['success'] = true;
        $response['message'] = 'Your message has been sent successfully! The finder will receive your contact information and respond to you soon.';
        $response['message_id'] = $message_id;

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
