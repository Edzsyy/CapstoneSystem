<?php
// Enable error reporting and logging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Disable error display on the frontend
ini_set('log_errors', 1);  // Enable logging of errors
ini_set('error_log', '/home/businesspermit.unifiedlgu.com/public_html/error.log'); // Log file location

// Start the session
session_start();

// Include the database connection file
include('./assets/config/dbconn.php');

// Function to generate a CSRF token
function generateCsrfToken() {
    return bin2hex(random_bytes(32));
}

// Function to validate CSRF token
function validateCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Generate CSRF token if not already set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = generateCsrfToken();
}

// Function to sanitize input data
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Function to validate email format
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to validate phone number format
function isValidPhoneNumber($phone) {
    return preg_match('/^[0-9]{10,15}$/', $phone);
}

if (isset($_POST['register'])) {
    // Validate CSRF token
    if (!validateCsrfToken($_POST['csrf_token'])) {
        $_SESSION['message'] = "Invalid CSRF token.";
        header('location: register.php');
        exit(0);
    }

    // Capture and sanitize input fields
    $phone = sanitizeInput($_POST['phone']);
    $fname = sanitizeInput($_POST['fname']);
    $lname = sanitizeInput($_POST['lname']);
    $email = sanitizeInput($_POST['email']);
    $address = sanitizeInput($_POST['address']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    // Validate email format
    if (!isValidEmail($email)) {
        $_SESSION['message'] = "Invalid email format.";
        header('location: register.php');
        exit(0);
    }

    // Validate phone number format
    if (!isValidPhoneNumber($phone)) {
        $_SESSION['message'] = "Invalid phone number format.";
        header('location: register.php');
        exit(0);
    }

    // Check for password length
    if (strlen($password) < 8) {
        $_SESSION['message'] = "Password must be at least 8 characters long.";
        header('location: register.php');
        exit(0);
    }

    // Check if passwords match
    if ($password !== $cpassword) {
        $_SESSION['message'] = "Passwords do not match.";
        header('location: register.php');
        exit(0);
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check for existing email in the database
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['message'] = "Email already exists.";
        header('location: register.php');
        exit(0);
    }

    // Insert the sanitized and hashed data into the database
    $stmt = $conn->prepare("INSERT INTO users (fname, lname, email, phone, address, password, role_as) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $role_as = 0; // Default role for regular users
    $stmt->bind_param("ssssssi", $fname, $lname, $email, $phone, $address, $hashedPassword, $role_as);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Registration Successful!";
        header('location: login.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Registration Failed: " . $stmt->error; // Log the specific error
        header('location: register.php');
        exit(0);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-----bootstrap----->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-----style----->
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Register</title>
</head>
<body>
    <section class="container">
        <div class="form">
            <?php include('message.php'); ?>
            <div class="form-content">
                <header>Register</header>

                    <form action="" method="post">
                    <!-- Hidden input for CSRF token -->
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <div class="input-group">
                        <input type="text" name="fname" id="fname" placeholder="First Name" required>
                    </div>

                    <div class="input-group">
                        <input type="text" name="lname" id="lname" placeholder="Last Name" required>
                    </div>

                    <div class="input-group">
                        <input type="email" name="email" id="email" placeholder="Email" required>
                    </div>

                    <div class="input-group">
                        <input type="text" name="phone" id="phone" placeholder="Phone" required>
                    </div>

                    <div class="input-group">
                        <input type="text" name="address" id="address" placeholder="Address" required>
                    </div>

                    <div class="input-group">
                        <input type="password" name="password" id="password" placeholder="Password" required>
                    </div>
                    
                    <div class="input-group">
                        <input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password" required>
                    </div>

                    <div class="button">
                        <input type="submit" class="btn" value="Register" name="register">
                    </div>
                </form>
            </div>

            <div class="line"></div>

            <div class="media-option">
                <a href="https://www.facebook.com/" class="facebook-link">
                    <i class='bx bxl-facebook facebook-icon' ></i>
                </a>
                <a href="https://myaccount.google.com/" class="google-link">
                    <i class='bx bxl-google google-icon' ></i>
                </a>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/js/script.js"></script>
</body>
</html>