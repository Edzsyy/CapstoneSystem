<?php 
session_start();
include('./assets/config/dbconn.php');

if (isset($_POST['register'])) {
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    // Password validation: Minimum 8 characters, includes letters and numbers
    if (strlen($password) >= 8 && preg_match('/[A-Za-z]/', $password) && preg_match('/[0-9]/', $password)) {
        if ($password === $cpassword) {
            // Check if email already exists
            $checkemail = $conn->prepare("SELECT email FROM users WHERE email=?");
            $checkemail->bind_param("s", $email);
            $checkemail->execute();
            $result = $checkemail->get_result();

            if ($result->num_rows > 0) {
                // Email already exists
                $_SESSION['message'] = "Already Email Exists";
                header("location: register.php");
                exit(0);
            } else {
                // Hash the password for secure storage
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert user into the database
                $sql = "INSERT INTO users (fname, lname, email, password) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $fname, $lname, $email, $hashedPassword);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "Registered Successfully";
                    header("location: login.php");
                    exit(0);
                } else {
                    $_SESSION['message'] = "Something went wrong!";
                    header("location: register.php");
                    exit(0);
                }
            }
        } else {
            $_SESSION['message'] = "Password and Confirm Password do not Match";
            header("location: register.php");
            exit(0);
        }
    } else {
        $_SESSION['message'] = "Password must be at least 8 characters long and include both letters and numbers.";
        header("location: register.php");
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
                        <input type="password" name="password" id="password" placeholder="Password" required>
                        <i class='bx bx-hide eye-icon' ></i>
                    </div>
                    <div class="input-group">
                        <input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password" required>
                        <i class='bx bx-hide eye-icon' ></i>
                    </div>

                    <div class="button">
                        <input type="submit" class="btn" value="Register" name="register">
                    </div>

                    <div class="form-link">
                        <span>Already have an account? <a href="login.php" class="signin-link">Sign In</a></span>
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