<?php 
include('../employee/assets/config/dbconn.php');

if (isset($_POST['fnameSend']) && isset($_POST['lnameSend']) && isset($_POST['emailSend']) && isset($_POST['passwordSend']) && isset($_POST['phoneSend']) && isset($_POST['addressSend'])) {
    // Get the input data
    $fname = $_POST['fnameSend'];
    $lname = $_POST['lnameSend'];
    $email = $_POST['emailSend'];
    $password = $_POST['passwordSend'];
    $phone = $_POST['phoneSend'];
    $address = $_POST['addressSend'];
    $roleAs = $_POST['roleAs'];
    $status = $_POST['status'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into the database
    $sql = "INSERT INTO users (fname, lname, email, password, phone, address, role_as, status) VALUES ('$fname', '$lname', '$email', '$hashedPassword', '$phone', '$address', '$roleAs', '$status')";

    // Execute the query and check for success
    if (mysqli_query($conn, $sql)) {
        echo "User added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
