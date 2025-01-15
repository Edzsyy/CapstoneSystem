<?php
session_start();
include('../user/assets/config/dbconn.php');

// Check if the user is logged in
if (!isset($_SESSION['auth']) || !$_SESSION['auth']) {
    header("Location: ../login.php");
    exit();
}

include('../user/assets/inc/header.php');
include('../user/assets/inc/sidebar.php');
include('../user/assets/inc/navbar.php');

// Get the logged-in user's details from the session
$user_id = $_SESSION['auth_user']['user_id'] ?? 0;

// Initialize user information
$full_name = $email = $phone = $address = "N/A"; 

// Get user information from the database
$query = "SELECT fname, lname, email, phone, address FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) { // Check if user exists
    $full_name = htmlspecialchars($user['fname'] . ' ' . $user['lname']); 
    $email = htmlspecialchars($user['email']); 
    $phone = htmlspecialchars($user['phone']); 
    $address = htmlspecialchars($user['address']); 
}
$result->free();
$stmt->close(); // Close the statement after use

// Get the login time from the session
$login_time = $_SESSION['auth_user']['login_time'] ?? 'Not available';

// Handle profile update
if (isset($_POST['update_profile'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Update query
    $update_query = "UPDATE users SET fname = ?, lname = ?, email = ?, phone = ?, address = ? WHERE id = ?";
    if ($update_stmt = $conn->prepare($update_query)) {
        $update_stmt->bind_param("sssssi", $fname, $lname, $email, $phone, $address, $user_id);
        
        if ($update_stmt->execute()) {
            $_SESSION['success'] = "Profile updated successfully!";
            header("Location: user_profile.php"); // Redirect to the same page to reflect changes
            exit();
        } else {
            $_SESSION['error'] = "Failed to update profile.";
        }
        $update_stmt->close(); // Close the statement after use
    }
}

// Handle password change
if (isset($_POST['update_pwd'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch current password from the database
    $query = "SELECT password FROM users WHERE id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($current_password);
        $stmt->fetch();
        
        // Free result set and close the statement
        $stmt->free_result();
        $stmt->close(); // Close the statement after use

        // Verify the old password
        if (password_verify($old_password, $current_password)) {
            if ($new_password === $confirm_password) {
                // Hash the new password
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                // Update the password in the database
                $update_password_query = "UPDATE users SET password = ? WHERE id = ?";
                if ($update_password_stmt = $conn->prepare($update_password_query)) {
                    $update_password_stmt->bind_param("si", $hashed_new_password, $user_id);
                    
                    if ($update_password_stmt->execute()) {
                        $_SESSION['success'] = "Password updated successfully!";
                    } else {
                        $_SESSION['error'] = "Failed to update password.";
                    }
                    $update_password_stmt->close(); // Close the statement after use
                }
            } else {
                $_SESSION['error'] = "New passwords do not match.";
            }
        } else {
            $_SESSION['error'] = "Old password is incorrect.";
        }
    }

    header("Location: user_profile.php"); // Redirect to the same page to reflect changes
    exit();
}
?>

<div class="data-card">
    <div class="card">
        <div class="card-header">
            <h3>User Profile</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-4 col-xl-4">
                                <div class="card-box text-center">
                                    <img src="./assets/image/profile.png" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">
                                    <h4 class="mb-0"><?php echo $full_name; ?></h4> <!-- Display Full Name -->
                                    <p class="text-muted">@System_user</p>
                                    <div class="text-left mt-3">
                                        <p class="text-muted mb-2"><strong>Full Name :</strong> <span class="ml-2"><?php echo $full_name; ?></span></p>
                                        <p class="text-muted mb-2"><strong>Email :</strong> <span class="ml-2"><?php echo $email; ?></span></p>
                                        <p class="text-muted mb-2"><strong>Phone :</strong> <span class="ml-2"><?php echo $phone; ?></span></p>
                                        <p class="text-muted mb-2"><strong>Login Time :</strong> <span class="ml-2"><?php echo $login_time; ?></span></p>
                                    </div>
                                </div> <!-- end card-box -->
                            </div> <!-- end col-->

                            <div class="col-lg-8 col-xl-8">
                                <div class="card-box">
                                    <ul class="nav nav-pills navtab-bg nav-justified">
                                        <li class="nav-item">
                                            <a href="#aboutme" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                                Update Profile
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#settings" data-toggle="tab" aria-expanded="false" class="nav-link">
                                                Change Password
                                            </a>
                                        </li>
                                    </ul>
                                    
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="aboutme">
                                            <form method="post" enctype="multipart/form-data">
                                                <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle mr-1"></i> Personal Info</h5>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="inputFirstname">First Name</label>
                                                            <input type="text" required="required" value="<?php echo htmlspecialchars($user['fname']); ?>" name="fname" class="form-control" id="inputFirstname">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="inputLastname">Last Name</label>
                                                            <input required="required" type="text" value="<?php echo htmlspecialchars($user['lname']); ?>" name="lname" class="form-control" id="inputLastname">
                                                        </div>
                                                    </div> <!-- end col -->
                                                </div> <!-- end row -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="inputEmail">Email Address</label>
                                                            <input required="required" type="email" value="<?php echo htmlspecialchars($email); ?>" class="form-control" name="email" id="inputEmail">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="inputPhone">Phone Number</label>
                                                            <input required="required" type="text" value="<?php echo htmlspecialchars($phone); ?>" class="form-control" name="phone" id="inputPhone">
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="inputAddress">Address</label>
                                                            <input type="text" value="<?php echo htmlspecialchars($address); ?>" name="address" class="form-control" id="inputAddress">
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->

                                                <div class="text-right">
                                                    <button type="submit" name="update_profile" class="btn btn-primary mt-2"><i class="mdi mdi-content-save"></i> Save</button>
                                                </div>
                                            </form>
                                        </div> <!-- end tab-pane -->

                                        <div class="tab-pane" id="settings">
                                            <form method="post">
                                                <h5 class="mb-4 text-uppercase"><i class="mdi mdi-lock mr-1"></i> Change Password</h5>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="inputOldpassword">Old Password</label>
                                                            <input type="password" class="form-control" id="inputOldpassword" name="old_password" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="inputNewpassword">New Password</label>
                                                            <input type="password" class="form-control" id="inputNewpassword" name="new_password" required>
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="inputConfirmpassword">Confirm New Password</label>
                                                            <input type="password" class="form-control" id="inputConfirmpassword" name="confirm_password" required>
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->

                                                <div class="text-right">
                                                    <button type="submit" name="update_pwd" class="btn btn-primary mt-2"><i class="mdi mdi-lock-reset"></i> Change Password</button>
                                                </div>
                                            </form>
                                        </div> <!-- end tab-pane -->
                                    </div> <!-- end tab-content -->
                                </div> <!-- end card-box -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                    </div> <!-- end container -->
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- end card-body -->
    </div> <!-- end card -->
</div> <!-- end data-card -->

<?php include('../user/assets/inc/footer.php'); ?>
