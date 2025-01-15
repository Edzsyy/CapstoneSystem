<?php 
    include('../employee/assets/config/dbconn.php');
    $pic_uploaded = 0;

    if(isset($_REQUEST['submit']))
    {

        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $mname = mysqli_real_escape_string($conn, $_POST['mname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $zip = mysqli_real_escape_string($conn, $_POST['zip']);
        $business_name = mysqli_real_escape_string($conn, $_POST['business_name']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $com_address = mysqli_real_escape_string($conn, $_POST['com_address']);
        $building_name = mysqli_real_escape_string($conn, $_POST['building_name']);
        $building_no = mysqli_real_escape_string($conn, $_POST['building_no']);
        $street = mysqli_real_escape_string($conn, $_POST['street']);
        $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);
        $product = mysqli_real_escape_string($conn, $_POST['product']);
        $registered_name = mysqli_real_escape_string($conn, $_POST['registered_name']);
        $rent_per_month = mysqli_real_escape_string($conn, $_POST['rent_per_month']);
        $period_date = mysqli_real_escape_string($conn, $_POST['period_date']);
        $date_application = mysqli_real_escape_string($conn, $_POST['date_application']);
        $or_date = mysqli_real_escape_string($conn, $_POST['or_date']);
        $amount_paid = mysqli_real_escape_string($conn, $_POST['amount_paid']);

        

        $picture = time().$_FILES["picture"]['name'];
        if(move_uploaded_file($_FILES['picture']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'../employee/assets/image/'.$picture))
        {
            $target_file = $_SERVER['DOCUMENT_ROOT'].'../employee/assets/image/'.$picture;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $picturename = basename($_FILES['picture']['name']);
            $photo = time().$picturename;

            if($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png")
            {?>
                <script>
                    alert("Please upload photo having extension .jpg/.jpeg/.png");
                </script>
            <?php
            }
            else if($_FILES["picture"]["size"] > 2000000)
            {?>
                <script>
                    alert("Your photo exceed the size of 2 MB");
                </script>
            <?php
            }
            else
            {
                $pic_uploaded = 1;
            }

        }
        

        if($pic_uploaded == 1)
        { 

        $sql = "INSERT INTO renewal (fname, mname, lname, address, zip, business_name, phone, email, com_address, building_name, 
        building_no, street, barangay, product, registered_name, rent_per_month, period_date, date_application, or_date,
        amount_paid, picture) VALUES ('$fname', '$mname', '$lname', '$address', '$zip', '$business_name', '$phone', '$email', '$com_address', '$building_name',
        '$building_no', '$street', '$barangay', '$product', '$registered_name', '$rent_per_month', '$period_date', '$date_application', 
        '$or_date', '$amount_paid', '$picture')";

        $result = mysqli_query($conn, $sql);
        if($result)
            {
                
                header("location: employee_renewal_list.php");
                exit(0);
            }
            else
            {
                
                header("location: employee_renewal.php");
                exit(0);
            }

        
        }
    }


?> 







