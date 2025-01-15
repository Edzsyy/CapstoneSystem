<?php
include '../admin/assets/config/dbconn.php'; // Include your database connection file

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $business_name = $_POST['business_name'];
    $business_address = $_POST['business_address'];
    $building_name = $_POST['building_name'];
    $building_no = $_POST['building_no'];
    $street = $_POST['street'];
    $barangay = $_POST['barangay'];
    $business_type = $_POST['business_type'];
    $rent_per_month = $_POST['rent_per_month'];
    $period_date = $_POST['period_date'];
    $date_application = $_POST['date_application'];
    $receipt = $_POST['receipt'];
    $or_date = $_POST['or_date'];
    $amount_paid = $_POST['amount_paid'];

    // Handle file uploads
    $upload_dti = $_FILES['upload_dti']['name'];
    $upload_store_picture = $_FILES['upload_store_picture']['name'];
    $food_security_clearance = $_FILES['food_security_clearance']['name'];

    // Move uploaded files to a designated folder
    move_uploaded_file($_FILES['upload_dti']['tmp_name'], "/user/assets/image/" . $upload_dti);
    move_uploaded_file($_FILES['upload_store_picture']['tmp_name'], "/user/assets/image/" . $upload_store_picture);
    move_uploaded_file($_FILES['food_security_clearance']['tmp_name'], "/user/assets/image/" . $food_security_clearance);

    $query = "UPDATE registration SET 
              fname='$fname',
              mname='$mname',
              lname='$lname',
              email='$email',
              phone='$phone',
              address='$address',
              business_name='$business_name',
              business_address='$business_address',
              building_name='$building_name',
              building_no='$building_no',
              street='$street',
              barangay='$barangay',
              business_type='$business_type',
              rent_per_month='$rent_per_month',
              period_date='$period_date',
              date_application='$date_application',
              receipt='$receipt',
              or_date='$or_date',
              amount_paid='$amount_paid',
              upload_dti='$upload_dti',
              upload_store_picture='$upload_store_picture',
              food_security_clearance='$food_security_clearance'
              WHERE id='$id'";

    if (mysqli_query($conn, $query)) {
        echo "User updated successfully.";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>
