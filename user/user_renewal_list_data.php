<?php
// Enable error reporting and logging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/home/businesspermit.unifiedlgu.com/public_html/error.log');

// Start the session
session_start();

// Include necessary files
include('../user/assets/inc/header.php');
include('../user/assets/inc/sidebar.php');
include('../user/assets/inc/navbar.php');
include('../user/assets/config/dbconn.php');

// Initialize error and success message variables
$errorMessage = '';
$successMessage = '';
$applicationNumber = ''; // Initialize application number to avoid undefined variable warning

// Function to generate a unique application number
function generateApplicationNumber($conn) {
    $prefix = "APP-";
    $date = date("Ymd");

    // Query to get the last inserted application number for today
    $query = "SELECT application_number FROM renewal WHERE application_number LIKE '$prefix$date%' ORDER BY id DESC LIMIT 1";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastNumber = (int)substr($row['application_number'], -4);
        $newNumber = str_pad($lastNumber + 1, 4, "0", STR_PAD_LEFT);
    } else {
        $newNumber = "0001"; // Start with 0001 if no application number exists
    }

    return $prefix . $date . $newNumber;
}

// Check if the form is submitted
if (isset($_REQUEST['submit'])) {
    // Auto-generate the application number
    $applicationNumber = generateApplicationNumber($conn);

    // Escape user inputs for security
    $formData = [
        'fname' => mysqli_real_escape_string($conn, $_POST['fname']),
        'mname' => mysqli_real_escape_string($conn, $_POST['mname']),
        'lname' => mysqli_real_escape_string($conn, $_POST['lname']),
        'address' => mysqli_real_escape_string($conn, $_POST['address']),
        'zip' => mysqli_real_escape_string($conn, $_POST['zip']),
        'business_name' => mysqli_real_escape_string($conn, $_POST['business_name']),
        'phone' => mysqli_real_escape_string($conn, $_POST['phone']),
        'email' => mysqli_real_escape_string($conn, $_POST['email']),
        'business_address' => mysqli_real_escape_string($conn, $_POST['business_address']),
        'building_name' => mysqli_real_escape_string($conn, $_POST['building_name']),
        'building_no' => mysqli_real_escape_string($conn, $_POST['building_no']),
        'street' => mysqli_real_escape_string($conn, $_POST['street']),
        'barangay' => mysqli_real_escape_string($conn, $_POST['barangay']),
        'business_type' => mysqli_real_escape_string($conn, $_POST['business_type']),
        'rent_per_month' => mysqli_real_escape_string($conn, $_POST['rent_per_month']),
        'date_application' => mysqli_real_escape_string($conn, $_POST['date_application']),
    ];

    // Handle file uploads with validation
    $uploads = [
        'upload_dti' => $_FILES["dti"],
        'upload_store_picture' => $_FILES["store_picture"],
        'food_security_clearance' => $_FILES["food_security_clearance"],
        'upload_old_permit' => $_FILES["old_permit"]
    ];

    $uploadedFiles = [];
    foreach ($uploads as $key => $file) {
        if (isset($file) && $file['error'] == 0) {
            $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            if (in_array($file['type'], $allowedTypes) && $file['size'] < 2000000) {
                $uploadedFiles[$key] = time() . '_' . basename($file['name']);
                $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/user/assets/image/' . $uploadedFiles[$key];

                if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    $errorMessage = "Failed to upload $key.";
                    break;
                }
            } else {
                $errorMessage = "Invalid file type or size for $key. Please upload a JPEG, PNG, or PDF file under 2MB.";
                break;
            }
        } else {
            $errorMessage = "File for $key is missing or an error occurred.";
            break;
        }
    }

    // Proceed with database insertion if no error occurred
    if (empty($errorMessage)) {
        $sql = "INSERT INTO renewal (fname, mname, lname, address, zip, business_name, phone, email, business_address, 
                building_name, building_no, street, barangay, business_type, rent_per_month, 
                date_application, application_number, upload_dti, upload_store_picture, 
                food_security_clearance, upload_old_permit) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            $errorMessage = "MySQL prepare failed: " . htmlspecialchars($conn->error);
        } else {
            // Collect uploaded file names or NULL if not set
            $upload_dti = $uploadedFiles['upload_dti'] ?? NULL;
            $upload_store_picture = $uploadedFiles['upload_store_picture'] ?? NULL;
            $food_security_clearance = $uploadedFiles['food_security_clearance'] ?? NULL;
            $upload_old_permit = $uploadedFiles['upload_old_permit'] ?? NULL;

            // Bind parameters
            $stmt->bind_param("sssssssssssssssssssss", 
                $formData['fname'], $formData['mname'], $formData['lname'], $formData['address'], $formData['zip'], 
                $formData['business_name'], $formData['phone'], $formData['email'], $formData['business_address'], 
                $formData['building_name'], $formData['building_no'], $formData['street'], $formData['barangay'], 
                $formData['business_type'], $formData['rent_per_month'], 
                $formData['date_application'], $applicationNumber, $upload_dti, $upload_store_picture, 
                $food_security_clearance, $upload_old_permit);

            if ($stmt->execute()) {
                $successMessage = "Renewal registration successful!";
                header("location: user_renewal_list.php");
                exit(0);
            } else {
                $errorMessage = "Registration Failed: " . $stmt->error;
            }
        }
    }
}
?>