<?php 
// Enable error reporting and logging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/home/businesspermit.unifiedlgu.com/public_html/error.log');

// Start the session
session_start();

// Include the Design on this page 
include('../admin/assets/config/dbconn.php');

// Initialize an error message variable
$errorMessage = '';
$successMessage = '';
$reciept = ''; // Initialize the variable

// Function to generate a unique receipt number
function generateReceiptNumber($conn) {
    // Prefix for the receipt number
    $prefix = "REC-";
    // Get the current date in YYYYMMDD format
    $date = date("Ymd");

    // Query to get the last inserted receipt number for today
    $query = "SELECT reciept FROM registration WHERE reciept LIKE '$prefix$date%' ORDER BY id DESC LIMIT 1";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        // Extract the last number part and increment it
        $row = $result->fetch_assoc();
        $lastNumber = (int)substr($row['reciept'], -4);
        $newNumber = str_pad($lastNumber + 1, 4, "0", STR_PAD_LEFT);
    } else {
        // Start with 0001 if no receipt number exists for today
        $newNumber = "0001";
    }

    // Combine the prefix, date, and new number
    return $prefix . $date . $newNumber;
}

// Handle form submission
if (isset($_REQUEST['submit'])) {
    // Auto-generate the receipt number when the form is submitted
    $reciept = generateReceiptNumber($conn);

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
    $date_application = mysqli_real_escape_string($conn, $_POST['date_application']);
    $or_date = mysqli_real_escape_string($conn, $_POST['or_date']);
    $amount_paid = !empty($_POST['amount_paid']) ? mysqli_real_escape_string($conn, $_POST['amount_paid']) : NULL;

    // Handle file uploads with validation
    $uploads = [
        'upload_dti' => $_FILES["upload_dti"],
        'upload_store_picture' => $_FILES["upload_store_picture"],
        'food_security_clearance' => $_FILES["food_security_clearance"]
    ];

    $uploadedFiles = [];
    foreach ($uploads as $key => $file) {
        // Check file type and size
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        if (in_array($file['type'], $allowedTypes) && $file['size'] < 2000000) { // 2MB limit
            $uploadedFiles[$key] = time() . $file['name'];
            $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/employee/assets/image/' . $uploadedFiles[$key];

            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $errorMessage = "Failed to upload $key.";
                break;
            }
        } else {
            $errorMessage = "Invalid file type or size for $key. Please upload a JPEG, PNG, or PDF file under 2MB.";
            break;
        }
    }

    // Proceed with database insertion if no error occurred
    if (empty($errorMessage)) {
        $sql = "INSERT INTO registration (fname, mname, lname, address, zip, business_name, phone, email, business_address, 
                building_name, building_no, street, barangay, business_type, rent_per_month, period_date, 
                date_application, reciept, or_date, amount_paid, upload_dti, upload_store_picture, food_security_clearance) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            $errorMessage = "MySQL prepare failed: " . htmlspecialchars($conn->error);
        } else {
            $stmt->bind_param("sssssssssssssssssssssss", $fname, $mname, $lname, $address, $zip, $business_name, $phone, 
                              $email, $business_address, $building_name, $building_no, $street, $barangay, $business_type, 
                              $rent_per_month, $period_date, $date_application, $reciept, $or_date, $amount_paid, 
                              $uploadedFiles['upload_dti'], $uploadedFiles['upload_store_picture'], $uploadedFiles['food_security_clearance']);

            if ($stmt->execute()) {
                $successMessage = "Registration successful!";
                header("location: employee_registration_list.php");
                exit(0);
            } else {
                $errorMessage = "Registration Failed: " . $stmt->error;
            }
        }
    }
}
?>






