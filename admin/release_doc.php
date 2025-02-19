<?php
// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include('../admin/assets/config/dbconn.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the application_id, status, and release_date from the POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['application_id'], $_POST['status'], $_POST['release_date'])) {
    $application_id = $_POST['application_id'];
    $status = $_POST['status'];
    $release_date = $_POST['release_date']; // This should already be in ISO format (e.g., '2025-02-19T12:34:56')

    // First, try to fetch data from the registration table
    $sql = "SELECT * FROM registration WHERE application_number = ?";

    // Prepare the statement to prevent SQL injection
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $application_id); // "s" means the application_id is a string
        $stmt->execute();

        // Fetch the result
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If a record is found in registration, proceed to update the status
            $stmt_update = $conn->prepare("UPDATE registration SET application_status = ?, release_date = ? WHERE application_number = ?");
            $stmt_update->bind_param('sss', $status, $release_date, $application_id);

            // Execute the update query
            if ($stmt_update->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Document released successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update the document status in registration table.']);
            }

            $stmt_update->close();
        } else {
            // No data found in registration, check the renewal table
            $sql_renewal = "SELECT * FROM renewal WHERE application_number = ?";

            if ($stmt_renewal = $conn->prepare($sql_renewal)) {
                $stmt_renewal->bind_param("s", $application_id);
                $stmt_renewal->execute();
                $result_renewal = $stmt_renewal->get_result();

                if ($result_renewal->num_rows > 0) {
                    // If a record is found in renewal, proceed to update the status
                    $stmt_update_renewal = $conn->prepare("UPDATE renewal SET application_status = ?, release_date = ? WHERE application_number = ?");
                    $stmt_update_renewal->bind_param('sss', $status, $release_date, $application_id);

                    // Execute the update query
                    if ($stmt_update_renewal->execute()) {
                        echo json_encode(['status' => 'success', 'message' => 'Document released successfully in renewal table.']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to update the document status in renewal table.']);
                    }

                    $stmt_update_renewal->close();
                } else {
                    // No data found in both registration and renewal tables
                    echo json_encode(['status' => 'error', 'message' => 'Application ID not found in both registration and renewal tables.']);
                }

                $stmt_renewal->close();
            } else {
                // SQL statement preparation for renewal table failed
                echo json_encode(['status' => 'error', 'message' => 'Error preparing SQL query for renewal table.']);
            }
        }

        $stmt->close();
    } else {
        // SQL statement preparation failed for registration table
        echo json_encode(['status' => 'error', 'message' => 'Error preparing SQL query for registration table.']);
    }
} else {
    // Invalid request or missing application_id
    echo json_encode(['status' => 'error', 'message' => 'Invalid request. Application ID, status, or release date is missing.']);
}

// Close the database connection
$conn->close();
?>
