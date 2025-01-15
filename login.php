<?php 
session_start();
include('./assets/config/dbconn.php');

// Function to redirect based on user role
function redirectToRole($role_as) {
    switch ($role_as) {
        case '2': // Admin
            return './admin/index.php';
        case '1': // Employee
            return './employee/index.php';
        case '0': // User
            return './user/index.php';
        default:
            return 'login.php'; // Fallback
    }
}

// CSRF Token generation and validation
function generateCsrfToken() {
    return bin2hex(random_bytes(32));
}

function validateCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Generate CSRF token if it doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = generateCsrfToken();
}

// Function to sanitize inputs
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

if (isset($_POST['login'])) {
    // Validate CSRF token
    if (!validateCsrfToken($_POST['csrf_token'])) {
        $_SESSION['message'] = "Invalid CSRF token.";
        header('location: login.php');
        exit(0);
    }

    // Sanitize email and password input
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Invalid email format.";
        header('location: login.php');
        exit(0);
    }

    // Array of tables to check
    $tables = [
        'users' => 'SELECT id, fname, lname, email, password, role_as FROM users WHERE email = ?',
        'employee' => 'SELECT id, fname, lname, email, password, role_as FROM employee WHERE email = ?',
        'admin' => 'SELECT id, fname, lname, email, password, role_as FROM admin WHERE email = ?'
    ];

    foreach ($tables as $table => $query) {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $user_fname, $user_lname, $user_email, $hashedPassword, $role_as);
            $stmt->fetch();

            // Password verification
            if (password_verify($password, $hashedPassword)) {
                session_regenerate_id(true); // Regenerate session ID to prevent session fixation attacks
                $_SESSION['auth'] = true;
                $_SESSION['auth_role'] = $role_as;
                $_SESSION['auth_user'] = [
                    'user_id' => $user_id,
                    'user_name' => sanitizeInput($user_fname) . ' ' . sanitizeInput($user_lname),
                    'user_email' => sanitizeInput($user_email),
                ];

                // Redirect based on role
                header('location: ' . redirectToRole($role_as));
                exit(0);
            } else {
                // Invalid password
                $_SESSION['message'] = "Invalid login credentials.";
                header('location: login.php');
                exit(0);
            }
        }
    }

    // If not found in any tables
    $_SESSION['message'] = "Invalid login credentials.";
    header('location: login.php');
    exit(0);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Login</title>
</head>
<body>
    <section class="container">
        <div class="form">
            <?php include('message.php'); ?>
            <div class="form-content">
                <header>Login</header>

                <form action="" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <div class="input-group">
                        <input type="email" name="email" id="email" placeholder="Email" required>
                    </div>

                    <div class="input-group">
                        <input type="password" name="password" id="password" placeholder="Password" required>
                        <i class='bx bx-hide eye-icon'></i>
                    </div>

                    <div class="form-link">
                        <a href="/forgot_password.php" class="forgot-password">Forgot Password?</a>
                    </div>

                    <div class="button">
                        <input type="submit" class="btn" value="Login" name="login">
                    </div>

                    <div class="form-link">
                        <span>Don't have an account? <a href="register.php" class="signup-link">Sign Up</a></span>
                    </div>
                </form>
            </div>

            <div class="line"></div>

            <div class="media-option">
                <a href="https://www.facebook.com/" class="facebook-link">
                    <i class='bx bxl-facebook facebook-icon'></i>
                </a>
                <a href="https://myaccount.google.com/" class="google-link">
                    <i class='bx bxl-google google-icon'></i>
                </a>
            </div>
        </div>
    </section>

    <script src="./assets/js/script.js"></script>
</body>
</html>
