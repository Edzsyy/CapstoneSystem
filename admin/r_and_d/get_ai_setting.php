<?php
session_start();
header('Content-Type: application/json');
include('../r_and_d/config/dbconn.php');

$query = "SELECT organization_name, contact_number, organization_address, website_link, 
                 notify_days, notify_message, expired_message 
          FROM ai_settings LIMIT 1";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['success' => true, 'data' => $row]);
} else {
    echo json_encode(['success' => false, 'message' => 'No AI settings found']);
}

$conn->close();
?>
