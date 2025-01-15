<?php 
include('../admin/assets/config/dbconn.php');

include('../admin/assets/inc/header.php');

include('../admin/assets/inc/sidebar.php');

include('../admin/assets/inc/navbar.php');

?> 



<!-- QR code Modal -->
<div class="modal fade" id="qrcodeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">QR Code</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <script src="../admin/assets/js/html5-qrcode.min.js"></script>
                <style>
                    .result { background-color: green; color: #fff; padding: 20px; }
                    .row { display: flex; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { padding: 10px; border: 1px solid #ddd; }
                </style>
                <div class="row">
                    <div class="col">
                        <div style="width: 470px;" id="reader"></div>
                    </div>
                    <div class="col" style="padding: 20px;">
                        <h4>SCAN RESULT</h4>
                        <div id="result">Result Here</div>
                    </div>
                </div>
                <script type="text/javascript">
                    function onScanSuccess(qrCodeMessage) {
                        const ApplicationIDMatch = qrCodeMessage.match(/ApplicationID:(\d{11})/);
                        if (ApplicationIDMatch) {
                            const application_id = ApplicationIDMatch[1];
                            fetchDataFromServer(application_id);
                        } else {
                            document.getElementById('result').innerHTML = '<span class="result">QR code does not contain a valid application ID</span>';
                        }
                    }
                    function onScanError(errorMessage) {
                        console.error('Scan error:', errorMessage);
                    }
                    function fetchDataFromServer(application_id) {
                        fetch('fetch_data.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: new URLSearchParams({ 'application_id': application_id })
                        })
                        .then(response => response.json())
                        .then(data => renderDataInTable(data))
                        .catch(error => console.error('Error:', error));
                    }
                    function renderDataInTable(data) {
                        if (!data || data.length === 0) {
                            document.getElementById('result').innerHTML = '<span class="result">No data found</span>';
                            return;
                        }
                        let table = '<table><tr><th>Application ID</th><th>Owner</th><th>Business Name</th><th>Business Type</th><th>Address</th><th>Status</th></tr>';
                        data.forEach(row => {
                            table += `<tr>
                                <td>${row.application_id}</td>
                                <td>${row.owner_name}</td>
                                <td>${row.business_name}</td>
                                <td>${row.business_type}</td>
                                <td>${row.address}</td>
                                <td>${row.status}</td>
                            </tr>`;
                        });
                        table += '</table>';
                        document.getElementById('result').innerHTML = table;
                    }
                    var html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
                    html5QrcodeScanner.render(onScanSuccess, onScanError);
                </script>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="qrcode()">Submit</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End QR code Modal -->

<!-- Update Registration Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="updateId">
                <div class="mb-3">
                    <label for="updateFirstname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="updateFirstname">
                </div>
                <div class="mb-3">
                    <label for="updateMiddlename" class="form-label">Middle Name</label>
                    <input type="text" class="form-control" id="updateMiddlename">
                </div>
                <div class="mb-3">
                    <label for="updateLastname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="updateLastname">
                </div>
                <div class="mb-3">
                    <label for="updateEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="updateEmail">
                </div>
                <div class="mb-3">
                    <label for="updatePhone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="updatePhone">
                </div>
                <div class="mb-3">
                    <label for="updateAddress" class="form-label">Address</label>
                    <input type="text" class="form-control" id="updateAddress">
                </div>
                <div class="mb-3">
                    <label for="updateBusinessName" class="form-label">Business Name</label>
                    <input type="text" class="form-control" id="updateBusinessName">
                </div>
                <div class="mb-3">
                    <label for="updateBusinessAddress" class="form-label">Business Address</label>
                    <input type="text" class="form-control" id="updateBusinessAddress">
                </div>
                <div class="mb-3">
                    <label for="updateBuildingName" class="form-label">Building Name</label>
                    <input type="text" class="form-control" id="updateBuildingName">
                </div>
                <div class="mb-3">
                    <label for="updateBuildingNo" class="form-label">Building No</label>
                    <input type="text" class="form-control" id="updateBuildingNo">
                </div>
                <div class="mb-3">
                    <label for="updateStreet" class="form-label">Street</label>
                    <input type="text" class="form-control" id="updateStreet">
                </div>
                <div class="mb-3">
                    <label for="updateBarangay" class="form-label">Barangay</label>
                    <input type="text" class="form-control" id="updateBarangay">
                </div>
                <div class="mb-3">
                    <label for="updateBusinessType" class="form-label">Business Type</label>
                    <input type="text" class="form-control" id="updateBusinessType">
                </div>
                <div class="mb-3">
                    <label for="updateRentPerMonth" class="form-label">Rent Per Month</label>
                    <input type="text" class="form-control" id="updateRentPerMonth">
                </div>
                <div class="mb-3">
                    <label for="updatePeriodofDate" class="form-label">Period of Date</label>
                    <input type="text" class="form-control" id="updatePeriodofDate">
                </div>
                <div class="mb-3">
                    <label for="updateDateofApplication" class="form-label">Date of Application</label>
                    <input type="date" class="form-control" id="updateDateofApplication">
                </div>
                <div class="mb-3">
                    <label for="updateReceipt" class="form-label">Receipt</label>
                    <input type="text" class="form-control" id="updateReceipt">
                </div>
                <div class="mb-3">
                    <label for="updateOrDate" class="form-label">OR Date</label>
                    <input type="date" class="form-control" id="updateOrDate">
                </div>
                <div class="mb-3">
                    <label for="updateAmountPaid" class="form-label">Amount Paid</label>
                    <input type="text" class="form-control" id="updateAmountPaid">
                </div>
                <!-- File Upload Inputs -->
                <div class="mb-3">
                    <label for="updateUploadDti" class="form-label">Upload DTI</label>
                    <input type="file" class="form-control" id="updateUploadDti" accept=".pdf,.jpg,.jpeg,.png">
                </div>
                <div class="mb-3">
                    <label for="updateUploadStorePicture" class="form-label">Upload Store Picture</label>
                    <input type="file" class="form-control" id="updateUploadStorePicture" accept=".jpg,.jpeg,.png">
                </div>
                <div class="mb-3">
                    <label for="updateFoodSecurityClearance" class="form-label">Food Security Clearance</label>
                    <input type="file" class="form-control" id="updateFoodSecurityClearance" accept=".pdf,.jpg,.jpeg,.png">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateUser()">Update</button>
            </div>
        </div>
    </div>
</div>
<!-- End Update Registration Modal -->

<!-- View Registration Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">View Registration Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center">
                                <h5>Store Picture</h5>
                                <img id="viewStorePicture" src="default_store_picture.jpg" alt="Store Picture" class="img-fluid rounded">
                            </div>
                            <div class="text-center">
                                <h5>Food Security Clearance</h5>
                                <img id="viewFoodSecurityClearance" src="default_food_security.jpg" alt="Food Security Clearance" class="img-fluid rounded">
                            </div>
                            <div class="text-center">
                                <h5>DTI Document</h5>
                                <img id="viewUploadDti" src="default_dti.jpg" alt="DTI Document" class="img-fluid rounded">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p><strong>First Name:</strong> <span id="viewFirstname"></span></p>
                            <p><strong>Middle Name:</strong> <span id="viewMiddlename"></span></p>
                            <p><strong>Last Name:</strong> <span id="viewLastname"></span></p>
                            <p><strong>Email:</strong> <span id="viewEmail"></span></p>
                            <p><strong>Phone:</strong> <span id="viewPhone"></span></p>
                            <p><strong>Address:</strong> <span id="viewAddress"></span></p>
                            <p><strong>Zip Code:</strong> <span id="viewZip"></span></p>
                            <p><strong>Business Name:</strong> <span id="viewBusinessName"></span></p>
                            <p><strong>Business Address:</strong> <span id="viewBusinessAddress"></span></p>
                            <p><strong>Building Name:</strong> <span id="viewBuildingName"></span></p>
                            <p><strong>Building No:</strong> <span id="viewBuildingNo"></span></p>
                            <p><strong>Street:</strong> <span id="viewStreet"></span></p>
                            <p><strong>Barangay:</strong> <span id="viewBarangay"></span></p>
                            <p><strong>Business Type:</strong> <span id="viewBusinessType"></span></p>
                            <p><strong>Rent per Month:</strong> <span id="viewRentPerMonth"></span></p>
                            <p><strong>Period Date:</strong> <span id="viewPeriodDate"></span></p>
                            <p><strong>Date of Application:</strong> <span id="viewDateofApplication"></span></p>
                            <p><strong>Receipt:</strong> <span id="viewReceipt"></span></p>
                            <p><strong>OR Date:</strong> <span id="viewOrDate"></span></p>
                            <p><strong>Amount Paid:</strong> <span id="viewAmountPaid"></span></p>
                            <p><strong>Status:</strong> <span id="viewDocumentStatus"></span></p> <!-- Added Status -->
                        </div>
                    </div>
                </div>
                <input type="hidden" id="hiddendata" value="">
                <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="updateDocumentStatus('Approved')">Approve</button>
                <button type="button" class="btn btn-danger" onclick="updateDocumentStatus('Rejected')">Reject</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- View Registration Modal end  -->

<!-- Image View Modal -->
<div class="modal fade" id="imageViewModal" tabindex="-1" aria-labelledby="imageViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageViewModalLabel">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="imagePreview" src="" alt="Image Preview" class="img-fluid">
            </div>
        </div>
    </div>
</div>





<div class="data-card">
    <div class="card">
        <div class="card-header">
            <h4>Regsitration List
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#qrcodeModal">
                    <i class='bx bx-qr-scan'></i>
                </button>
            </h4>
        </div>

        <div class="card-body">
            <!-- Tabs for filtering -->
            <ul class="nav nav-tabs" id="statusTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#all" onclick="filterData('All')">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#approved" onclick="filterData('Approved')">Approved</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#pending" onclick="filterData('Pending')">Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#rejected" onclick="filterData('Rejected')">Rejected</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all">
                    <div id="displayDataTableAll"></div>
                </div>
                <div class="tab-pane fade" id="approved">
                    <div id="displayDataTableApproved"></div>
                </div>
                <div class="tab-pane fade" id="pending">
                    <div id="displayDataTablePending"></div>
                </div>
                <div class="tab-pane fade" id="rejected">
                    <div id="displayDataTableRejected"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Send Email Modal -->
<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendEmailModalLabel">Send Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="text-center">
                            <h5>Permit Details</h5>
                            <p><strong>Business Name:</strong> <span id="permitBusinessName"></span></p>
                            <p><strong>Owner:</strong> <span id="permitOwnerName"></span></p>
                            <p><strong>Permit Type:</strong> <span id="permitType"></span></p>
                            <p><strong>Expiration Date:</strong> <span id="permitExpiration"></span></p>
                        </div>
                        <div class="text-center mt-4">
                            <h5>Generated QR Code</h5>
                            <img id="permitQRCode" class="d-none" alt="Generated QR Code" style="max-width: 300px; margin-top: 20px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="releaseEmail" class="form-label"><strong>Recipient Email</strong></label>
                        <input type="email" id="releaseEmail" class="form-control" placeholder="Enter recipient email" required>
                        
                        <label for="releaseMessage" class="form-label mt-3"><strong>Custom Message</strong></label>
                        <textarea id="releaseMessage" class="form-control" rows="4" placeholder="Enter a custom message (optional)"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="generateQRCodeBtn">Generate QR Code</button>
                <button type="button" class="btn btn-success" id="sendEmailBtn">Send Email</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Send Email Modal End -->"






<?php 
include('../admin/assets/inc/footer.php');
?> 


<script>
        $(document).ready(function() {
        filterData('All'); // Load all data by default
    });

    // Filter function
    function filterData(status) {
        $.ajax({
            url: "admin_registration_list_displaydata.php",
            type: 'post',
            data: { displaysend: status },
            success: function(data) {
                if (status === 'All') {
                    $('#displayDataTableAll').html(data);
                } else if (status === 'Approved') {
                    $('#displayDataTableApproved').html(data);
                } else if (status === 'Pending') {
                    $('#displayDataTablePending').html(data);
                } else if (status === 'Rejected') {
                    $('#displayDataTableRejected').html(data);
                }
            }
        });
    }

    function sendEmail(userId) {
    // Fetch user details via an AJAX POST request
    $.post("admin_registration_get_details.php", { updateid: userId }, function (data, status) {
        // Parse the returned JSON data
        var user = JSON.parse(data);

        // Populate the fields in the Send Email Modal
        $('#permitBusinessName').text(user.business_name);
        $('#permitOwnerName').text(user.fname + ' ' + user.mname + ' ' + user.lname);
        $('#permitType').text(user.business_type);
        $('#permitExpiration').text(user.period_date);

        // Auto-fill the recipient email
        $('#releaseEmail').val(user.email);

        // Ensure only one click event is bound to the Generate QR Code button
        $('#generateQRCodeBtn').off('click').on('click', function () {
            // Send an AJAX request to the backend
    $.post('send_qr_email.php', { email: user.email }, function (response) {
        if (response.success) {
            // Display the QR Code using the Base64 URI
            $('#permitQRCode')
                .attr('src', response.qr_code_base64)
                .removeClass('d-none'); // Show the image
            alert('QR Code generated successfully!');
        } else {
            alert('Failed to generate QR Code: ' + response.message);
        }
    }, 'json').fail(function () {
        alert('An error occurred while generating the QR Code.');
    });
});

        // Show the Send Email Modal
        $('#sendEmailModal').modal("show");
    });
}







    //delete function
    function deleteuser(deleteid)
    {
        $.ajax({
            url:"admin_registration_list_delete.php",
            type:'post',
            data:{
                deletesend:deleteid
            },
            success:function(data,status){
                //console.log(status);
                displayData();
            }
        });
    }

 
    $(document).ready(function() {
    displayData();
});

// Function to display user data
function displayData() {
    var displayData = "true";
    $.ajax({
        url: "admin_registration_list_displaydata.php",
        type: 'post',
        data: { displaysend: displayData },
        success: function(data, status) {
            $('#displayDataTable').html(data);
        }
    });
}

    // Function to get user details and populate the update modal
    function getdetails(updateid) {
        $.post("admin_registration_get_details.php", { updateid: updateid }, function(data, status) {
            var user = JSON.parse(data);
            $('#updateId').val(user.id);
            $('#updateFirstname').val(user.fname);
            $('#updateMiddlename').val(user.mname);
            $('#updateLastname').val(user.lname);
            $('#updateEmail').val(user.email);
            $('#updatePhone').val(user.phone);
            $('#updateAddress').val(user.address);
            $('#updateBusinessName').val(user.business_name);
            $('#updateBusinessAddress').val(user.business_address);
            $('#updateBuildingName').val(user.building_name);
            $('#updateBuildingNo').val(user.building_no);
            $('#updateStreet').val(user.street);
            $('#updateBarangay').val(user.barangay);
            $('#updateBusinessType').val(user.business_type);
            $('#updateRentPerMonth').val(user.rent_per_month);
            $('#updatePeriodofDate').val(user.period_date);
            $('#updateDateofApplication').val(user.date_application);
            $('#updateReceipt').val(user.receipt);
            $('#updateOrDate').val(user.or_date);
            $('#updateAmountPaid').val(user.amount_paid);
            // Optionally set values for the uploaded files
            $('#updateModal').modal("show");
        });
    }

    // Function to update user details
    function updateUser() {
        var updateData = new FormData();
        updateData.append('id', $('#updateId').val());
        updateData.append('fname', $('#updateFirstname').val());
        updateData.append('mname', $('#updateMiddlename').val());
        updateData.append('lname', $('#updateLastname').val());
        updateData.append('email', $('#updateEmail').val());
        updateData.append('phone', $('#updatePhone').val());
        updateData.append('address', $('#updateAddress').val());
        updateData.append('business_name', $('#updateBusinessName').val());
        updateData.append('business_address', $('#updateBusinessAddress').val());
        updateData.append('building_name', $('#updateBuildingName').val());
        updateData.append('building_no', $('#updateBuildingNo').val());
        updateData.append('street', $('#updateStreet').val());
        updateData.append('barangay', $('#updateBarangay').val());
        updateData.append('business_type', $('#updateBusinessType').val());
        updateData.append('rent_per_month', $('#updateRentPerMonth').val());
        updateData.append('period_date', $('#updatePeriodofDate').val());
        updateData.append('date_application', $('#updateDateofApplication').val());
        updateData.append('receipt', $('#updateReceipt').val());
        updateData.append('or_date', $('#updateOrDate').val());
        updateData.append('amount_paid', $('#updateAmountPaid').val());
        updateData.append('upload_dti', $('#updateUploadDti')[0].files[0]);
        updateData.append('upload_store_picture', $('#updateUploadStorePicture')[0].files[0]);
        updateData.append('food_security_clearance', $('#updateFoodSecurityClearance')[0].files[0]);

        $.ajax({
            url: "admin_registration_list_update.php",
            type: 'post',
            data: updateData,
            contentType: false,
            processData: false,
            success: function(response) {
                alert(response);
                displayData();
                $('#updateModal').modal("hide");
            }
        });
    }


    
// view function for displaying user details including image files
function viewDetails(viewid) {
        $.post("admin_registration_list_view.php", { viewid: viewid }, function(data, status) {
            var user = JSON.parse(data);

            if (user.error) {
                alert(user.error);
                return;
            }


            // Set the hidden input value to the viewId
             $('#hiddendata').val(viewid)

            // Populate the modal fields with the fetched data
            $('#viewFirstname').text(user.fname);
            $('#viewMiddlename').text(user.mname);
            $('#viewLastname').text(user.lname);
            $('#viewEmail').text(user.email);
            $('#viewPhone').text(user.phone);
            $('#viewAddress').text(user.address);
            $('#viewZip').text(user.zipcode);
            $('#viewBusinessName').text(user.business_name);
            $('#viewBusinessAddress').text(user.business_address);
            $('#viewBuildingName').text(user.building_name);
            $('#viewBuildingNo').text(user.building_no);
            $('#viewStreet').text(user.street);
            $('#viewBarangay').text(user.barangay);
            $('#viewBusinessType').text(user.business_type);
            $('#viewRentPerMonth').text(user.rent_per_month);
            $('#viewPeriodDate').text(user.period_date);
            $('#viewDateofApplication').text(user.date_application);
            $('#viewReceipt').text(user.reciept);
            $('#viewOrDate').text(user.or_date);
            $('#viewAmountPaid').text(user.amount_paid);
            $('#viewDocumentStatus').text(user.document_status);

            // Handle image files
            const storePicture = user.store_picture_url ? '/user/assets/image/' + user.store_picture_url : 'default_store_picture.jpg';
            const foodSecurityClearance = user.food_security_clearance_url ? '/user/assets/image/' + user.food_security_clearance_url : 'default_food_security.jpg';
            const uploadDti = user.upload_dti_url ? '/user/assets/image/' + user.upload_dti_url : 'default_dti.jpg';

            $('#viewStorePicture').attr('src', storePicture);
            $('#viewFoodSecurityClearance').attr('src', foodSecurityClearance);
            $('#viewUploadDti').attr('src', uploadDti);

            // Add click events to open the image modal for viewing full size
            $('#viewStorePicture').on('click', function() {
                showImageInModal(storePicture);
            });
            $('#viewFoodSecurityClearance').on('click', function() {
                showImageInModal(foodSecurityClearance);
            });
            $('#viewUploadDti').on('click', function() {
                showImageInModal(uploadDti);
            });

            // Show the modal
            $('#viewModal').modal('show');
        });
    }

    // Function to show the image in a larger modal
    function showImageInModal(imageUrl) {
        $('#imagePreview').attr('src', imageUrl);
        $('#imageViewModal').modal('show');
    }

    // Function to update the document status
    function updateDocumentStatus(status) {
            var viewId = $('#hiddendata').val(); // Make sure this input exists and has a value

            // Debugging logs
            console.log("View ID:", viewId);
            console.log("Document Status:", status);

            // Check if the viewId and status are not empty
            if (!viewId || !status) {
                alert("View ID or Document Status is missing.");
                return;
            }

            $.post("admin_registration_list_update_status.php", 
            {
                viewid: viewId,
                document_status: status
            }, 
            //approve button
            function(data) {
    console.log("Response:", data);
    if (data.success) {
        $('#viewDocumentStatus').text(status);
        alert("Document status updated to " + status + " and QR code has been sent to the user's email.");
        $('#viewModal').modal('hide');
        displayData(); // Refresh the list

        // Additional action for sending email if status is 'Approved'
        if (status === 'Approved') {
            $.ajax({
                url: 'send_qr_email.php', // PHP script for QR generation and email
                type: 'POST',
                data: {
                    email: data.email, // Applicant's email from the backend response
                },
                success: function(response) {
                    console.log("QR Code email sent successfully:", response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Failed to send QR Code email:", textStatus, errorThrown);
                }
            });
        }

        if (status === 'Rejected') {
            alert("Your document was rejected. Please refill the renewal update form.");
            window.location.href = "./admin_registration_list.php";
        }
    } else {
        alert("Failed to update the document status: " + data.error);
    }
}, "json")
.fail(function(jqXHR, textStatus, errorThrown) {
    console.error("AJAX request failed: " + textStatus + ", " + errorThrown);
    alert("AJAX request failed: " + textStatus);
});

        }

    

</script>


</body>
</html>
