<?php
include('../employee/assets/config/dbconn.php');

// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log the received POST data for debugging
file_put_contents('debug.log', "POST Data: " . print_r($_POST, true) . "\n", FILE_APPEND);

if (isset($_POST['viewid'])) {
    $id = $_POST['viewid'];

    if (isset($_POST['document_status'])) {
        $status = $_POST['document_status'];

        // Update the document_status in the renewal table
        $query = "UPDATE registration SET document_status = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $status, $id);
        $result = $stmt->execute();

        if ($result) {
            if ($status == 'Rejected') {
                // Update application_status to 'Needs Correction' in the renewal table
                $resubmitQuery = "UPDATE registration SET application_status = 'Needs Correction' WHERE id = ?";
                $resubmitStmt = $conn->prepare($resubmitQuery);
                $resubmitStmt->bind_param("i", $id);
                $resubmitResult = $resubmitStmt->execute();

                if (!$resubmitResult) {
                    echo json_encode(['success' => false, 'error' => 'Failed to update application_status: ' . $resubmitStmt->error]);
                    exit;
                }
            } elseif ($status == 'Approved') {
                // Update application_status to 'Pending to Released' in the renewal table
                $approveQuery = "UPDATE registration SET application_status = 'Pending to Released' WHERE id = ?";
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
            echo json_encode(['success' => false, 'error' => 'Failed to update document status: ' . $stmt->error]);
        }
    } elseif (isset($_POST['application_status']) && $_POST['application_status'] == 'Released') {
        // When clicking the "Release" button, update application_status to "Released"
        $releaseQuery = "UPDATE registration SET application_status = 'Released' WHERE id = ?";
        $releaseStmt = $conn->prepare($releaseQuery);
        $releaseStmt->bind_param("i", $id);
        $releaseResult = $releaseStmt->execute();

        if ($releaseResult) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update application_status: ' . $releaseStmt->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid request. Missing parameters.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request. Missing parameters.']);
}

$conn->close();
