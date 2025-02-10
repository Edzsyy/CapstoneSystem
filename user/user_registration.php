<?php
// Enable error reporting and logging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/home/businesspermit.unifiedlgu.com/public_html/error.log');

// Start the session
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/home/businesspermit.unifiedlgu.com/public_html/error.log');

// Include necessary files
include('../user/assets/inc/header.php');
include('../user/assets/inc/sidebar.php');
include('../user/assets/inc/navbar.php');
include('../user/assets/config/dbconn.php');

// Initialize messages
$errorMessage = '';
$successMessage = '';

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

// Generate application number
$applicationNumber = generateApplicationNumber($conn);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure required fields exist before accessing them
    $fname = mysqli_real_escape_string($conn, $_POST['fname'] ?? '');
    $mname = mysqli_real_escape_string($conn, $_POST['mname'] ?? '');
    $lname = mysqli_real_escape_string($conn, $_POST['lname'] ?? '');
    $address = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
    $zip = mysqli_real_escape_string($conn, $_POST['zip'] ?? '');
    $business_name = mysqli_real_escape_string($conn, $_POST['business_name'] ?? '');
    $phone = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $business_address = mysqli_real_escape_string($conn, $_POST['business_address'] ?? '');
    $building_name = mysqli_real_escape_string($conn, $_POST['building_name'] ?? '');
    $building_no = mysqli_real_escape_string($conn, $_POST['building_no'] ?? '');
    $street = mysqli_real_escape_string($conn, $_POST['street'] ?? '');
    $barangay = mysqli_real_escape_string($conn, $_POST['barangay'] ?? '');
    $business_type = mysqli_real_escape_string($conn, $_POST['business_type'] ?? '');
    $rent_per_month = mysqli_real_escape_string($conn, $_POST['rent_per_month'] ?? '');
    $date_application = isset($_POST['date_application']) ? mysqli_real_escape_string($conn, $_POST['date_application']) : '';

    // Handle file uploads
    $uploads = [
        'upload_dti' => $_FILES["upload_dti"] ?? null,
        'upload_store_picture' => $_FILES["upload_store_picture"] ?? null,
        'food_security_clearance' => $_FILES["food_security_clearance"] ?? null
    ];

    $uploadedFiles = [];
    foreach ($uploads as $key => $file) {
        if ($file && $file['error'] == 0) {
            $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            if (in_array($file['type'], $allowedTypes) && $file['size'] < 2000000) { // 2MB limit
                $uploadedFiles[$key] = time() . '_' . basename($file['name']);
                $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/user/assets/image/' . $uploadedFiles[$key];

                if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    $errorMessage = "Failed to upload $key.";
                    break;
                }
            } else {
                $errorMessage = "Invalid file type or size for $key.";
                break;
            }
        } else {
            $uploadedFiles[$key] = NULL; // Handle optional file uploads
        }
    }

    // Check if there are no errors before inserting into the database
    if (empty($errorMessage)) {
        $sql = "INSERT INTO registration (fname, mname, lname, address, zip, business_name, phone, email, business_address, 
                building_name, building_no, street, barangay, business_type, rent_per_month, 
                application_number, document_status, upload_dti, upload_store_picture, food_security_clearance, date_application) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            $errorMessage = "MySQL prepare failed: " . htmlspecialchars($conn->error);
        } else {
            $document_status = "Pending"; // Default document status
            $stmt->bind_param("sssssssssssssssssssss", 
                              $fname, $mname, $lname, $address, $zip, $business_name, $phone, 
                              $email, $business_address, $building_name, $building_no, $street, $barangay, 
                              $business_type, $rent_per_month, $applicationNumber, 
                              $document_status, $uploadedFiles['upload_dti'], 
                              $uploadedFiles['upload_store_picture'], $uploadedFiles['food_security_clearance'], 
                              $date_application);

            if ($stmt->execute()) {
                $successMessage = "Registration successful!";
                header("location: user_registration_list.php");
                exit();
            } else {
                $errorMessage = "Registration Failed: " . $stmt->error;
            }
        }
    }
}

?>

<!-- HTML Form -->
<div class="data-card">
    <div class="card">
        <div class="card-header"></div>
        <div class="card-body">
            <?php if ($errorMessage): ?>
                <div class="alert alert-danger"><?= $errorMessage; ?></div>
            <?php endif; ?>
            <?php if ($successMessage): ?>
                <div class="alert alert-success"><?= $successMessage; ?></div>
            <?php endif; ?>

            <form class="row g-3" id="validated_form" method="post" action="user_registration.php" enctype="multipart/form-data">
                <div class="top-form" style="text-align: center;">
                    <h6>Republic of the Philippines</h6>
                    <h6>San Agustin, Metropolitan Manila</h6>
                    <h6>Business Permit & Licence Office</h6>
                    <h5>APPLICATION FORM FOR REGISTRATION OF BUSINESS PERMIT</h5>
                </div>

                <div class="col-md-5">
                            <label for="date_application" class="form-label">Date of Application:</label>
                            <input type="date" class="form-control" id="date_application" name="date_application" value="<?= date('Y-m-d'); ?>" required>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-5" >
                    <label for="application_number" class="form-label">Application Number:</label>
                    <input type="text" class="form-control" id="application_number" name="application_number" value="<?= $applicationNumber; ?>" readonly>
                </div>
                <div class="col-md-4">
                    <label for="fname" class="form-label">First name:</label>
                    <input type="text" class="form-control" id="fname" name="fname" placeholder="First name" required>
                </div> 
                <div class="col-md-4">
                    <label for="mname" class="form-label">Middle name:</label>
                    <input type="text" class="form-control" id="mname" name="mname" placeholder="Middle name" required>
                </div>
                <div class="col-md-4">
                    <label for="lname" class="form-label">Last name:</label>
                    <input type="text" class="form-control" id="lname" name="lname" placeholder="Last name" required>
                </div>
                <div class="col-8">
                    <label for="address" class="form-label">Address:</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
                </div>
                <div class="col-4">
                    <label for="zip" class="form-label">Zip:</label>
                    <input type="text" class="form-control" id="zip" name="zip" placeholder="Zip" required>
                </div>
                <div class="col-md-4">
                    <label for="business_name" class="form-label">Business Name:</label>
                    <input type="text" class="form-control" id="business_name" name="business_name" placeholder="Business Name" required>
                </div>
                <div class="col-md-4">
                    <label for="phone" class="form-label">Contact #:</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Contact #" required>
                </div>
                <div class="col-md-4">
                    <label for="email" class="form-label">Email Address:</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
                </div>
                <div class="col-md-4">
                    <label for="business_address" class="form-label">Business Address:</label>
                    <input type="text" class="form-control" id="business_address" name="business_address" placeholder="Business Address" required>
                </div>
                <div class="col-md-4">
                    <label for="building_name" class="form-label">Building Name:</label>
                    <input type="text" class="form-control" id="building_name" name="building_name" placeholder="Building Name" required>
                </div>
                <div class="col-md-4">
                    <label for="building_no" class="form-label">Building No:</label>
                    <input type="text" class="form-control" id="building_no" name="building_no" placeholder="Building No" required>
                </div>
                <div class="col-md-6">
                    <label for="street" class="form-label">Street:</label>
                    <input type="text" class="form-control" id="street" name="street" placeholder="Street" required>
                </div>
                <div class="col-md-6">
                    <label for="barangay" class="form-label">Barangay:</label>
                    <input type="text" class="form-control" id="barangay" name="barangay" placeholder="Barangay" required>
                </div>
                <div class="col-md-4">
                     <label for="business_type" class="form-label">Business Type (e.g., Merchandising, Manufacturing):</label>
                    <input type="text" class="form-control" id="business_type" name="business_type" placeholder="Business Type" required>
                </div>
                <div class="col-md-4">
                    <label for="rent_per_month" class="form-label">Rent Per Month:</label>
                    <input type="text" class="form-control" id="rent_per_month" name="rent_per_month" placeholder="Rent Per Month" required>
                </div>
                <hr>
                <h5 style="text-align: center;">Upload Required Documents</h5>
                <div class="col-md-4">
                    <label for="upload_dti" class="form-label">Upload DTI:</label>
                    <input type="file" class="form-control" id="upload_dti" name="upload_dti" required>
                </div>
                <div class="col-md-4">
                    <label for="upload_store_picture" class="form-label">Store Picture:</label>
                    <input type="file" class="form-control" id="upload_store_picture" name="upload_store_picture" required>
                </div>
                <div class="col-md-4">
                    <label for="food_security_clearance" class="form-label">Food Security Clearance:</label>
                    <input type="file" class="form-control" id="food_security_clearance" name="food_security_clearance" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
// Include the footer
include('../user/assets/inc/footer.php'); 
?>

</body>
</html>