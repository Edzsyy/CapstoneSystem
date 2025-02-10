<?php
// Start output buffering
ob_start();

// Enable error reporting and logging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/home/businesspermit.unifiedlgu.com/public_html/error.log');

// Start the session
session_start();

// Include necessary files
include('../admin/assets/inc/header.php');
include('../admin/assets/inc/sidebar.php');
include('../admin/assets/inc/navbar.php');
include('../admin/assets/config/dbconn.php');

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
                header("location: admin_renewal_list.php");
                exit(0);
            } else {
                $errorMessage = "Registration Failed: " . $stmt->error;
            }
        }
    }
            // End output buffering
ob_end_flush();
}
?>

<div class="data-card">
    <div class="card">
        <div class="card-header"></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <form class="row g-3" id="validated_form" method="post" action="admin_renewal.php" enctype="multipart/form-data">
                        <div class="top-form" style="text-align: center;">
                            <h6>Republic of the Philippines</h6>
                            <h6>San Agustin, Metropolitan Manila</h6>
                            <h6>Business Permit & Licence Office</h6>
                            <h5>APPLICATION FORM FOR RENEWAL OF BUSINESS PERMIT</h5>
                        </div>
                        <div class="col-md-5">
                            <label for="date_application" class="form-label">Date of Application:</label>
                            <input type="date" class="form-control" id="date_application" name="date_application" value="<?= date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-2"></div>
                        <div class="col-md-5">
                            <label for="application_number" class="form-label">Application Number:</label>
                            <input type="text" class="form-control" id="application_number" name="application_number" placeholder="Application Number" value="<?php echo $applicationNumber; ?>" readonly>
                        </div>
                        <hr>
                        <div class="col-md-4">
                            <label for="fname" class="form-label">First name:</label>
                            <input type="text" class="form-control" id="fname" name="fname" placeholder="First name" required>
                        </div>
                        <div class="col-md-4">
                            <label for="mname" class="form-label">Middle name:</label>
                            <input type="text" class="form-control" id="mname" name="mname" placeholder="Middle name">
                        </div>
                        <div class="col-md-4">
                            <label for="lname" class="form-label">Last name:</label>
                            <input type="text" class="form-control" id="lname" name="lname" placeholder="Last name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="address" class="form-label">Address:</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
                        </div>
                        <div class="col-md-3">
                            <label for="zip" class="form-label">Zip Code:</label>
                            <input type="text" class="form-control" id="zip" name="zip" placeholder="Zip Code" required>
                        </div>
                        <div class="col-md-3">
                            <label for="business_name" class="form-label">Business Name:</label>
                            <input type="text" class="form-control" id="business_name" name="business_name" placeholder="Business Name" required>
                        </div>
                        <div class="col-md-3">
                            <label for="phone" class="form-label">Contact Number:</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Contact Number" required>
                        </div>
                        <div class="col-md-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="business_address" class="form-label">Business Address:</label>
                            <input type="text" class="form-control" id="business_address" name="business_address" placeholder="Business Address" required>
                        </div>
                        <div class="col-md-4">
                            <label for="building_name" class="form-label">Building Name:</label>
                            <input type="text" class="form-control" id="building_name" name="building_name" placeholder="Building Name">
                        </div>
                        <div class="col-md-4">
                            <label for="building_no" class="form-label">Building No:</label>
                            <input type="text" class="form-control" id="building_no" name="building_no" placeholder="Building No">
                        </div>
                        <div class="col-md-4">
                            <label for="street" class="form-label">Street:</label>
                            <input type="text" class="form-control" id="street" name="street" placeholder="Street">
                        </div>
                        <div class="col-md-4">
                            <label for="barangay" class="form-label">Barangay:</label>
                            <input type="text" class="form-control" id="barangay" name="barangay" placeholder="Barangay">
                        </div>
                        <div class="col-md-4">
                            <label for="business_type" class="form-label">Business Type(Merchandising, Manufacturing, Service, etc..):</label>
                            <input type="text" class="form-control" id="business_type" name="business_type" placeholder="Business Type" required>
                        </div>
                        <div class="col-md-3">
                            <label for="rent_per_month" class="form-label">Rent Per Month:</label>
                            <input type="text" class="form-control" id="rent_per_month" name="rent_per_month" placeholder="Rent Per Month" required>
                        </div>
                        <hr>
                        <h5 style="text-align: center;">Upload Required Documents</h5>
                        <div class="col-md-6">
                            <label for="dti" class="form-label">Upload DTI:</label>
                            <input type="file" class="form-control" id="dti" name="dti" required>
                        </div>
                        <div class="col-md-6">
                            <label for="store_picture" class="form-label">Upload Store Picture:</label>
                            <input type="file" class="form-control" id="store_picture" name="store_picture" required>
                        </div>
                        <div class="col-md-6">
                            <label for="food_security_clearance" class="form-label">Upload Food Security Clearance:</label>
                            <input type="file" class="form-control" id="food_security_clearance" name="food_security_clearance" required>
                        </div>
                        <div class="col-md-6">
                            <label for="old_permit" class="form-label">Upload Old Permit:</label>
                            <input type="file" class="form-control" id="old_permit" name="old_permit" required>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" name="submit" type="submit">Submit</button>
                        </div>
                        <div class="col-12">
                            <?php if (!empty($errorMessage)) : ?>
                                <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
                            <?php endif; ?>
                            <?php if (!empty($successMessage)) : ?>
                                <div class="alert alert-success"><?php echo $successMessage; ?></div>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer and scripts
include('../admin/assets/inc/footer.php');
?>

</body>
</html>