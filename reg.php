<?php
include('./assets/config/dbconn.php');

if(isset($_POST['register'])) {
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']); // Get confirm password

    // Simple validation for password
    if (strlen($password) < 8) {
        $_SESSION['message'] = "Password must be at least 8 characters long.";
        header('location: register.php');
        exit(0);
    }

    if ($password !== $cpassword) {
        $_SESSION['message'] = "Passwords do not match.";
        header('location: register.php');
        exit(0);
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the hashed password into the database
    $stmt = $conn->prepare("INSERT INTO users (fname, lname, email, password, role_as) VALUES (?, ?, ?, ?, ?)");
    $role_as = 0; // Default role
    $stmt->bind_param("ssssi", $fname, $lname, $email, $hashedPassword, $role_as);
    
    if($stmt->execute()) {
        $_SESSION['message'] = "Registration Successful!";
        header('location: login.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Registration Failed!";
        header('location: register.php');
        exit(0);
    }
}
?>
