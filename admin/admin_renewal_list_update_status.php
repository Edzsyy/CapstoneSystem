<?php
include('../admin/assets/config/dbconn.php');

// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log the received POST data for debugging
file_put_contents('debug.log', "POST Data: " . print_r($_POST, true) . "\n", FILE_APPEND);

if (isset($_POST['viewid']) && isset($_POST['document_status'])) {
    $id = $_POST['viewid'];
    $status = $_POST['document_status'];

    // Update the document_status in the renewal table
    $query = "UPDATE renewal SET document_status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $id);
    $result = $stmt->execute();

    if ($result) {
        if ($status == 'Rejected') {
            // Update application_status to 'Needs Correction' in the renewal table
            $resubmitQuery = "UPDATE renewal SET application_status = 'Needs Correction' WHERE id = ?";
            $resubmitStmt = $conn->prepare($resubmitQuery);
            $resubmitStmt->bind_param("i", $id);
            $resubmitResult = $resubmitStmt->execute();

            if (!$resubmitResult) {
                echo json_encode(['success' => false, 'error' => 'Failed to update application_status in registration: ' . $resubmitStmt->error]);
                exit;
            }
        } elseif ($status == 'Approved') {
            // Update application_status to 'Released' in the renewal table
            $approveQuery = "UPDATE renewal SET application_status = 'Released' WHERE id = ?";
            $approveStmt = $conn->prepare($approveQuery);
            $approveStmt->bind_param("i", $id);
            $approveResult = $approveStmt->execute();

            if (!$approveResult) {
                echo json_encode(['success' => false, 'error' => 'Failed to update application_status in renewal: ' . $approveStmt->error]);
                exit;
            }
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update document status: ' . $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request. Missing parameters.']);
}

$conn->close();
?>