<?php
session_start();
header('Content-Type: application/json');
include('../r_and_d/config/dbconn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate the required fields
    if (!$data || !isset($data['notify_days']) || !isset($data['notify_message']) || !isset($data['expired_message']) || !isset($data['organization_name']) || !isset($data['contact_number']) || !isset($data['organization_address']) || !isset($data['website_link'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
        exit;
    }

    // Sanitize and prepare the data
    $notify_days = intval($data['notify_days']);
    $notify_message = trim($data['notify_message']);
    $expired_message = trim($data['expired_message']);
    $organization_name = trim($data['organization_name']);
    $contact_number = trim($data['contact_number']);
    $organization_address = trim($data['organization_address']);
    $website_link = trim($data['website_link']);

    // Check if an existing record exists
    $checkQuery = "SELECT id FROM ai_settings LIMIT 1";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        // Update existing record with all the fields
        $updateQuery = "
            UPDATE ai_settings 
            SET 
                notify_days = ?, 
                notify_message = ?, 
                expired_message = ?, 
                organization_name = ?, 
                contact_number = ?, 
                organization_address = ?, 
                website_link = ?, 
                updated_at = NOW() 
            WHERE id = 1";
        
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("issssss", $notify_days, $notify_message, $expired_message, $organization_name, $contact_number, $organization_address, $website_link);
    } else {
        // Insert a new record with all the fields
        $insertQuery = "
            INSERT INTO ai_settings 
            (notify_days, notify_message, expired_message, organization_name, contact_number, organization_address, website_link) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("issssss", $notify_days, $notify_message, $expired_message, $organization_name, $contact_number, $organization_address, $website_link);
    }

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'AI settings saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save AI settings']);
    }

    // Close statement and connection
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>
