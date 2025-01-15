<?php
include('../assets/config/dbconn.php');

if (isset($_POST['fnameSend']) && isset($_POST['lnameSend']) && isset($_POST['emailSend']) && isset($_POST['passwordSend'])) {
    $fname = mysqli_real_escape_string($conn, $_POST['fnameSend']);
    $lname = mysqli_real_escape_string($conn, $_POST['lnameSend']);
    $email = mysqli_real_escape_string($conn, $_POST['emailSend']);
    $password = mysqli_real_escape_string($conn, $_POST['passwordSend']);

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new admin into the database
    $stmt = $conn->prepare("INSERT INTO admin (fname, lname, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fname, $lname, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "Admin added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>