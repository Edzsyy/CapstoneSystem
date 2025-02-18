<?php
session_start();
header('Content-Type: application/json');
include('../r_and_d/config/dbconn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || !isset($data['notify_days']) || !isset($data['ai_message'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
        exit;
    }

    $notify_days = intval($data['notify_days']);
    $ai_message = trim($data['ai_message']);

    // Check if an existing record exists
    $checkQuery = "SELECT id FROM ai_setting LIMIT 1";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        // Update existing record
        $updateQuery = "UPDATE ai_setting SET notify_days = ?, ai_message = ?, updated_at = NOW() WHERE id = 1";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("is", $notify_days, $ai_message);
    } else {
        // Insert new record
        $insertQuery = "INSERT INTO ai_setting (notify_days, ai_message) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("is", $notify_days, $ai_message);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'AI settings saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save AI settings']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>
