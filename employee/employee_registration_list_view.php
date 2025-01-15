<?php
include('../employee/assets/config/dbconn.php');

// Check if viewid is set and is a valid integer
if (isset($_POST['viewid']) && filter_var($_POST['viewid'], FILTER_VALIDATE_INT)) {
    $viewid = $_POST['viewid'];

    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM registration WHERE id = ?");
    $stmt->bind_param("i", $viewid); // 'i' denotes the type of the parameter (integer)
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Handle NULL image paths by providing default values
            $storePicture = !empty($user['upload_store_picture']) ? htmlspecialchars($user['upload_store_picture']) : 'default_store_picture.jpg';
            $foodSecurityClearance = !empty($user['food_security_clearance']) ? htmlspecialchars($user['food_security_clearance']) : 'default_food_security.jpg';
            $uploadDti = !empty($user['upload_dti']) ? htmlspecialchars($user['upload_dti']) : 'default_dti.jpg';

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
                'period_date' => htmlspecialchars($user['period_date']),
                'date_application' => htmlspecialchars($user['date_application']),
                'receipt' => htmlspecialchars($user['reciept']), // Correct this in the database as well
                'or_date' => htmlspecialchars($user['or_date']),
                'amount_paid' => htmlspecialchars($user['amount_paid']),
                'store_picture_url' => $storePicture,
                'food_security_clearance_url' => $foodSecurityClearance,
                'upload_dti_url' => $uploadDti,
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
