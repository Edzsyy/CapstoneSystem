<?php
include('../employee/assets/config/dbconn.php');

// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log the received POST data for debugging
file_put_contents('debug.log', "POST Data: " . print_r($_POST, true) . "\n", FILE_APPEND);

if (isset($_POST['viewid']) && isset($_POST['document_status'])) {
    $id = $_POST['viewid'];
    $status = $_POST['document_status'];

    // Update the status in the database
    $query = "UPDATE renewal SET document_status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $id);
    $result = $stmt->execute();

    if ($result) {
        if ($status == 'Rejected') {
            $resubmitQuery = "UPDATE renewal SET needs_resubmission = 1 WHERE id = ?";
            $resubmitStmt = $conn->prepare($resubmitQuery);
            $resubmitStmt->bind_param("i", $id);
            $resubmitResult = $resubmitStmt->execute();

            if (!$resubmitResult) {
                echo json_encode(['success' => false, 'error' => 'Failed to update resubmission flag: ' . $resubmitStmt->error]);
                exit;
            }
        }
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update status: ' . $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request. Missing parameters.']);
}

$conn->close();
?>
