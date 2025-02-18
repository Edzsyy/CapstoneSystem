<?php
session_start();
header('Content-Type: application/json');
include('../r_and_d/config/dbconn.php');

$query = "SELECT notify_days, ai_message FROM ai_setting LIMIT 1";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['success' => true, 'data' => $row]);
} else {
    echo json_encode(['success' => false, 'message' => 'No AI settings found']);
}

$conn->close();
?>
