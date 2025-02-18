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

// Get the application_id from the POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['application_id'])) {
    $application_id = $_POST['application_id'];

    // First, try to fetch data from the registration table
    $sql = "SELECT * FROM registration WHERE application_number = ?";

    // Prepare the statement to prevent SQL injection
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $application_id); // "s" means the application_id is a string
        $stmt->execute();

        // Fetch the result
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // If a record is found in registration, fetch the data as an associative array
            $data = $result->fetch_assoc();

            // Return the data as a JSON response
            echo json_encode([
                'status' => 'success',
                'data' => $data
            ]);
        } else {
            // No data found in registration, check the renewal table
            $sql_renewal = "SELECT * FROM renewal WHERE application_number = ?";
            
            if ($stmt_renewal = $conn->prepare($sql_renewal)) {
                $stmt_renewal->bind_param("s", $application_id);
                $stmt_renewal->execute();
                $result_renewal = $stmt_renewal->get_result();
                
                if ($result_renewal->num_rows > 0) {
                    // If a record is found in renewal, fetch the data as an associative array
                    $data_renewal = $result_renewal->fetch_assoc();

                    // Return the renewal data as a JSON response
                    echo json_encode([
                        'status' => 'success',
                        'data' => $data_renewal
                    ]);
                } else {
                    // No data found in both registration and renewal tables
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'No data found for the given application ID.'
                    ]);
                }

                $stmt_renewal->close();
            } else {
                // SQL statement preparation for renewal table failed
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error preparing SQL query for renewal table.'
                ]);
            }
        }

        $stmt->close();
    } else {
        // SQL statement preparation failed for registration table
        echo json_encode([
            'status' => 'error',
            'message' => 'Error preparing SQL query for registration table.'
        ]);
    }
} else {
    // Invalid request or missing application_id
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request. Application ID is missing.'
    ]);
}

// Close the database connection
$conn->close();
?>
