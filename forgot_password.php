<?php 
session_start();
include('./assets/config/dbconn.php');

// Handle the password reset request
if(isset($_POST['reset_request'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if email exists in the database
    $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Update the user record with the reset token and expiry time
        $update_sql = "UPDATE users SET reset_token='$token', reset_token_expiry='$expiry' WHERE email='$email'";
        mysqli_query($conn, $update_sql);

        // Create a reset link
        $reset_link = "http://yourwebsite.com/reset_password.php?token=" . $token;

        // Send the email
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Hi,\n\nYou requested a password reset. Please click the link below to reset your password:\n" . $reset_link . "\n\nIf you did not request this, please ignore this email.";
        $headers = "From: noreply@yourwebsite.com";

        if(mail($to, $subject, $message, $headers)) {
            $_SESSION['message'] = "A password reset link has been sent to your email.";
            header('location: login.php');
            exit(0);
        } else {
            $_SESSION['message'] = "Failed to send email. Please try again.";
        }
    } else {
        $_SESSION['message'] = "This email is not registered.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Forgot Password</title>
</head>
<body>
    <section class="container">
        <div class="form">
            <?php include('message.php'); ?>
            <div class="form-content">
                <header>Forgot Password</header>

                <form action="" method="post">
                    <div class="input-group">
                        <input type="email" name="email" id="email" placeholder="Enter your email" required>
                    </div>

                    <div class="button">
                        <input type="submit" class="btn" value="Send Reset Link" name="reset_request">
                    </div>

                    <div class="form-link">
                        <span>Remembered your password? <a href="login.php" class="signup-link">Login</a></span>
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
