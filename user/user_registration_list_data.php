<?php
// Enable error reporting and logging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/home/businesspermit.unifiedlgu.com/public_html/error.log');

// Start the session
session_start();

// Include the Design on this page
include('../user/assets/inc/header.php');
include('../user/assets/inc/sidebar.php');
include('../user/assets/inc/navbar.php');
include('../user/assets/config/dbconn.php');

// Initialize an error message variable
$errorMessage = '';
$successMessage = '';
$applicationNumber = ''; // Initialize the variable

// Function to generate a unique application number
function generateApplicationNumber($conn) {
    $prefix = "APP-";
    $date = date("Ymd");
    $query = "SELECT application_number FROM registration WHERE application_number LIKE '$prefix$date%' ORDER BY id DESC LIMIT 1";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastNumber = (int)substr($row['application_number'], -4);
        $newNumber = str_pad($lastNumber + 1, 4, "0", STR_PAD_LEFT);
    } else {
        $newNumber = "0001";
    }

    return $prefix . $date . $newNumber;
}

// Auto-generate the application number when the page loads
$applicationNumber = generateApplicationNumber($conn);

// Handle form submission
if (isset($_REQUEST['submit'])) {
    // Escape user inputs for security
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $mname = mysqli_real_escape_string($conn, $_POST['mname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $zip = mysqli_real_escape_string($conn, $_POST['zip']);
    $business_name = mysqli_real_escape_string($conn, $_POST['business_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $business_address = mysqli_real_escape_string($conn, $_POST['business_address']);
    $building_name = mysqli_real_escape_string($conn, $_POST['building_name']);
    $building_no = mysqli_real_escape_string($conn, $_POST['building_no']);
    $street = mysqli_real_escape_string($conn, $_POST['street']);
    $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);
    $business_type = mysqli_real_escape_string($conn, $_POST['business_type']);
    $rent_per_month = mysqli_real_escape_string($conn, $_POST['rent_per_month']);
    $period_date = !empty($_POST['period_date']) ? mysqli_real_escape_string($conn, $_POST['period_date']) : NULL;
    $date_application = mysqli_real_escape_string($conn, $_POST['date_of_application']);

    // Handle file uploads
    $uploads = [
        'upload_dti' => $_FILES["upload_dti"],
        'upload_store_picture' => $_FILES["upload_store_picture"],
        'food_security_clearance' => $_FILES["food_security_clearance"]
    ];

    $uploadedFiles = [];
    foreach ($uploads as $key => $file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        if (in_array($file['type'], $allowedTypes) && $file['size'] < 2000000) { // 2MB limit
            $uploadedFiles[$key] = time() . $file['name'];
            $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/user/assets/image/' . $uploadedFiles[$key];

            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $errorMessage = "Failed to upload $key.";
                break;
            }
        } else {
            $errorMessage = "Invalid file type or size for $key.";
            break;
        }
    }

    if (empty($errorMessage)) {
        $sql = "INSERT INTO registration (fname, mname, lname, address, zip, business_name, phone, email, business_address, 
                building_name, building_no, street, barangay, business_type, rent_per_month, period_date, 
                application_number, document_status, upload_dti, upload_store_picture, food_security_clearance, date_application) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            $errorMessage = "MySQL prepare failed: " . htmlspecialchars($conn->error);
        } else {
            $document_status = "Pending"; // Default document status
            $stmt->bind_param("ssssssssssssssssssssss", $fname, $mname, $lname, $address, $zip, $business_name, $phone, 
                              $email, $business_address, $building_name, $building_no, $street, $barangay, $business_type, 
                              $rent_per_month, $period_date, $applicationNumber, $document_status, 
                              $uploadedFiles['upload_dti'], $uploadedFiles['upload_store_picture'], $uploadedFiles['food_security_clearance'], $date_application);

            if ($stmt->execute()) {
                $successMessage = "Registration successful!";
                header("location: user_registration_list.php");
                exit(0);
            } else {
                $errorMessage = "Registration Failed: " . $stmt->error;
            }
        }
    }
}
?>
