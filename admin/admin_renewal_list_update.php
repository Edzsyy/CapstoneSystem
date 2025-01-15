<?php
include('../admin/assets/config/dbconn.php'); // Include your database connection

if (isset($_POST['updateid'])) {
    $updateId = $_POST['updateid'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $address = $_POST['address'];
    $zip = $_POST['zip'];
    $business_name = $_POST['business_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $com_address = $_POST['com_address'];
    $building_name = $_POST['building_name'];
    $building_no = $_POST['building_no'];
    $street = $_POST['street'];
    $barangay = $_POST['barangay'];
    $product = $_POST['product'];
    $registered_name = $_POST['registered_name'];
    $rent_per_month = $_POST['rent_per_month'];
    $period_date = $_POST['period_date'];
    $date_application = $_POST['date_application'];
    $reciept = $_POST['reciept'];
    $or_date = $_POST['or_date'];
    $amount_paid = $_POST['amount_paid'];
    $picture = $_POST['picture'];

    // Prepare the SQL statement to update the user record
    $stmt = $conn->prepare("UPDATE renewal SET 
        fname = ?, mname = ?, lname = ?, address = ?, zip = ?, 
        business_name = ?, phone = ?, email = ?, com_address = ?, 
        building_name = ?, building_no = ?, street = ?, barangay = ?, 
        product = ?, registered_name = ?, rent_per_month = ?, 
        period_date = ?, date_application = ?, reciept = ?, 
        or_date = ?, amount_paid = ?, picture = ? 
        WHERE id = ?");

    // Bind parameters
    $stmt->bind_param("ssssssssssssssssssssi", 
        $fname, $mname, $lname, $address, $zip, 
        $business_name, $phone, $email, $com_address, 
        $building_name, $building_no, $street, $barangay, 
        $product, $registered_name, $rent_per_month, 
        $period_date, $date_application, $reciept, 
        $or_date, $amount_paid, $picture, $updateId
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]); // Return error message
    }

    $stmt->close();
}
?>