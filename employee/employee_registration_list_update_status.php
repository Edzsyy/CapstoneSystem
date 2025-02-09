<?php
include('../employee/assets/config/dbconn.php');

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log the received POST data for debugging
file_put_contents('debug.log', "POST Data: " . print_r($_POST, true) . "\n", FILE_APPEND);

if (isset($_POST['viewid']) && isset($_POST['document_status'])) {
    $id = $_POST['viewid'];
    $status = $_POST['document_status'];

    // Ensure the ID exists before updating
    $checkQuery = "SELECT id FROM registration WHERE id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows === 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid ID, no record found.']);
        exit;
    }

    // Update document_status
    $query = "UPDATE registration SET document_status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $id);
    $result = $stmt->execute();

    if ($result) {
        if ($status == 'Rejected') {
            $resubmitQuery = "UPDATE registration SET application_status = 'Needs Correction' WHERE id = ?";
            $resubmitStmt = $conn->prepare($resubmitQuery);
            $resubmitStmt->bind_param("i", $id);
            $resubmitResult = $resubmitStmt->execute();

            if (!$resubmitResult) {
                echo json_encode(['success' => false, 'error' => 'Failed to update application_status: ' . $resubmitStmt->error]);
                exit;
            }
        } elseif ($status == 'Approved') {
            $approveQuery = "UPDATE registration SET application_status = 'Released' WHERE id = ?";
            $approveStmt = $conn->prepare($approveQuery);
            $approveStmt->bind_param("i", $id);
            $approveResult = $approveStmt->execute();

            if (!$approveResult) {
                echo json_encode(['success' => false, 'error' => 'Failed to update application_status: ' . $approveStmt->error]);
                exit;
            }
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database update failed: ' . $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request. Missing parameters.']);
}

$conn->close();
?>
