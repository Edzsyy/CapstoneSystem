<?php 
include('../admin/assets/config/dbconn.php');

include('../admin/assets/inc/header.php');

include('../admin/assets/inc/sidebar.php');

include('../admin/assets/inc/navbar.php');

?> 


<div class="data-card">
    <div class="card">
        <div class="card-header">
            
        </div>


        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <!-- Start Content-->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-4 col-xl-4">
                                <div class="card-box text-center">
                                    <img src="./assets/image/profile.png" class="rounded-circle avatar-lg img-thumbnail"
                                        alt="profile-image">

                                    <h4 class="mb-0">System Administrator</h4>
                                    <p class="text-muted">@System_Administrator</p>
                                    <div class="text-left mt-3">
                                        <p class="text-muted mb-2"><strong>Full Name :</strong> <span class="ml-2">System Administrator</span></p>
                                        <p class="text-muted mb-2"><strong>Email :</strong> <span class="ml-2 ">admin@mail.com</span></p>
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
                                                            <input type="text" required="required" value="System" name="fname" class="form-control" id="inputFirstname" >

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="inputPassword">Last Name</label>
                                                            <input required="required" type="text" value="Administrator" name="lname" class="form-control"  id="inputPassword">
                                                        </div>
                                                    </div> <!-- end col -->
                                                </div> <!-- end row -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="inputEmail">Email Address</label>
                                                            <input required="required" type="email" value="admin@mail.com" class="form-control" name="email" id="inputEmail">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="inputProfile">Profile Picture</label>
                                                            <input type="file" name="profile-pic" class="form-control btn btn-secondary" id="inputProfile" placeholder="admin@mail.com">
                                                        </div>
                                                    </div>
                                                    
                                                </div> <!-- end row -->

                                            
                                                <div class="text-right">
                                                    <button type="submit" name="update_profile" class="btn btn-primary mt-2"><i class="mdi mdi-content-save"></i> Save</button>
                                                </div>
                                            </form>
                                        </div> <!-- end tab-pane -->
                                        <!-- end about me section content -->

                                    

                                        <div class="tab-pane" id="settings">
                                            <form method="post">
                                                <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle mr-1"></i> Personal Info</h5>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="inputOldpassword">Old Password</label>
                                                            <input type="password" class="form-control" id="inputOldpassword" placeholder="Enter Old Password">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="inputNewpassword">New Password</label>
                                                            <input type="password" class="form-control" name="ad_pwd" id="inputNewpassword" placeholder="Enter New Password">
                                                        </div>
                                                    </div> <!-- end col -->
                                                </div> <!-- end row -->

                                                <div class="form-group">
                                                    <label for="inputConfirmpassword">Confirm Password</label>
                                                    <input type="password" class="form-control" id="inputConfirmpassword" placeholder="Confirm New Password">
                                                </div>

                                                <div class="text-right">
                                                    <button type="submit" name="update_pwd" class="btn btn-success waves-effect waves-light mt-2"><i class="mdi mdi-content-save"></i> Update Password</button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- end settings content-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end Start Content-->
                </div>
            </div>
        </div>
    </div>
</div>


<?php 
include('../admin/assets/inc/footer.php');
?> 


</body>
</html>
