<?php 
include('../assets/config/dbconn.php');

if (isset($_POST['fnameSend']) && isset($_POST['lnameSend']) && isset($_POST['emailSend']) && 
    isset($_POST['phoneSend']) && isset($_POST['addressSend']) && isset($_POST['passwordSend']) &&
    isset($_POST['roleSend'])) {

    $fname = $_POST['fnameSend'];
    $lname = $_POST['lnameSend'];
    $email = $_POST['emailSend'];
    $phone = $_POST['phoneSend'];
    $address = $_POST['addressSend'];
    $password = password_hash($_POST['passwordSend'], PASSWORD_DEFAULT); // Hash the password
    $role = $_POST['roleSend'];

    $query = "INSERT INTO employee (fname, lname, email, phone, address, password, role_as, status, created_at) 
              VALUES ('$fname', '$lname', '$email', '$phone', '$address', '$password', '$role', 1, NOW())"; // Assuming 'status' is 1 for active

    if (mysqli_query($conn, $query)) {
        echo "Employee added successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>