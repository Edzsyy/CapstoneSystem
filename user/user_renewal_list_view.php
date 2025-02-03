<?php
include('../user/assets/config/dbconn.php');

// Check if viewid is set and is a valid integer
if (isset($_POST['viewid']) && filter_var($_POST['viewid'], FILTER_VALIDATE_INT)) {
    $viewid = $_POST['viewid'];

    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM renewal WHERE id = ?");
    $stmt->bind_param("i", $viewid); // 'i' denotes the type of the parameter (integer)
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Return JSON response with user data and image URLs
            echo json_encode([
                'fname' => htmlspecialchars($user['fname']),
                'mname' => htmlspecialchars($user['mname']),
                'lname' => htmlspecialchars($user['lname']),
                'email' => htmlspecialchars($user['email']),
                'phone' => htmlspecialchars($user['phone']),
                'address' => htmlspecialchars($user['address']),
                'zipcode' => htmlspecialchars($user['zip']),
                'business_name' => htmlspecialchars($user['business_name']),
                'business_address' => htmlspecialchars($user['business_address']),
                'building_name' => htmlspecialchars($user['building_name']),
                'building_no' => htmlspecialchars($user['building_no']),
                'street' => htmlspecialchars($user['street']),
                'barangay' => htmlspecialchars($user['barangay']),
                'business_type' => htmlspecialchars($user['business_type']),
                'rent_per_month' => htmlspecialchars($user['rent_per_month']),
                'date_application' => htmlspecialchars($user['date_application']),
                'application_number' => htmlspecialchars($user['application_number']),
                'store_picture_url' => htmlspecialchars($user['upload_store_picture']), // Image path
                'food_security_clearance_url' => htmlspecialchars($user['food_security_clearance']), // Image path
                'upload_dti_url' => htmlspecialchars($user['upload_dti']), // Image path
                'upload_old_permit_url' => htmlspecialchars($user['upload_old_permit']),
                 // Image path
                'document_status' => htmlspecialchars($user['document_status']) // Include document status
            ]);
        } else {
            echo json_encode(['error' => 'No user found for the given ID.']);
        }
    } else {
        // Handle SQL execution error
        echo json_encode(['error' => 'Database query failed.']);
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(['error' => 'No valid view ID provided.']);
}

// Close the database connection
$conn->close();
?>
