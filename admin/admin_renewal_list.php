<?php 
include('../admin/assets/config/dbconn.php');

include('../admin/assets/inc/header.php');

include('../admin/assets/inc/sidebar.php');

include('../admin/assets/inc/navbar.php');

?> 


<!-- QR code Scanner Modal -->
<div class="modal fade" id="qrcodeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-4 fw-bold" id="exampleModalLabel">Scan QR Code</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <script src="../admin/assets/js/html5-qrcode.min.js"></script>
                <style>
                    #qrcodeModal .result {

                        color: #007bff;
                        padding: 15px;
                        border-radius: 8px;
                        font-weight: bold;
                    }

                    #qrcodeModal .row {
                        display: flex;
                        gap: 20px;
                    }

                    #qrcodeModal .col {
                        flex: 1;
                    }

                    #qrcodeModal #reader {
                        border-radius: 10px;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                        overflow: hidden;
                    }

                    #qrcodeModal h4 {
                        font-size: 1.25rem;
                        margin-bottom: 10px;
                    }

                    #qrcodeModal #result {
                        font-size: 1rem;
                        padding: 15px;
                        background-color: #f5f5f5;
                        border-radius: 8px;
                        border: 1px solid #ddd;
                        margin-top: 10px;
                        word-wrap: break-word;
                    }

                    #qrcodeModal table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 15px;
                    }

                    #qrcodeModal th,
                    #qrcodeModal td {
                        padding: 12px;
                        text-align: left;
                    }

                    #qrcodeModal th {
                        background-color: #f1f1f1;
                        font-weight: bold;
                    }

                    #qrcodeModal td {
                        background-color: #fff;
                    }

                    #qrcodeModal .modal-footer {
                        border-top: 1px solid #ddd;
                    }

                    #qrcodeModal .btn-primary {
                        background-color: #007bff;
                        border-color: #007bff;
                    }

                    #qrcodeModal .btn-primary:hover {
                        background-color: #0056b3;
                        border-color: #0056b3;
                    }

                    #qrcodeModal .btn-secondary {
                        background-color: #6c757d;
                        border-color: #6c757d;
                    }

                    #qrcodeModal .btn-secondary:hover {
                        background-color: #5a6268;
                        border-color: #545b62;
                    }
                </style>
                <div class="row">
                    <div class="col">
                        <div id="reader" style="width: 100%; max-width: 470px;"></div>
                    </div>
                    <div class="col">
                        <h4>SCAN RESULT</h4>
                        <div id="result">Result will appear here</div>
                    </div>
                </div>

                <script type="text/javascript">
                    let application_id = null;

                    function onScanSuccess(qrCodeMessage) {
                        const ApplicationIDMatch = qrCodeMessage.match(/(APP-\d{12})/);
                        if (ApplicationIDMatch) {
                            application_id = ApplicationIDMatch[1];
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
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: new URLSearchParams({
                                    'application_id': application_id
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                // Check the status in the response
                                if (data.status === "success") {
                                    // Call renderDataInTable if data is found
                                    renderDataInTable(data);
                                } else if (data.status === "error") {
                                    // Show the error message if no data is found
                                    document.getElementById('result').innerHTML = '<span class="result">' + data.message + '</span>';
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    }

                    function renderDataInTable(data) {
                        // Check if data is valid
                        if (data.status === "success" && data.data) {
                            const application = data.data;
                            let tableHtml = `
            <table border="1">
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Application Number</td><td>${application.application_number}</td></tr>
                    <tr><td>Full Name</td><td>${application.fname} ${application.mname} ${application.lname}</td></tr>
                    <tr><td>Address</td><td>${application.address}</td></tr>
                    <tr><td>Business Address</td><td>${application.business_address}</td></tr>
                    <tr><td>Business Name</td><td>${application.business_name}</td></tr>
                    <tr><td>Business Type</td><td>${application.business_type}</td></tr>
                    <tr><td>Date of Application</td><td>${application.date_application}</td></tr>
                    <tr><td>Document Status</td><td>${application.document_status}</td></tr>
                    <tr><td>Email</td><td>${application.email}</td></tr>
                    <tr><td>Phone</td><td>${application.phone}</td></tr>
                    <tr><td>Status</td><td>${application.application_status}</td></tr>
                </tbody>
            </table> `;
                            // Insert the table into your page (e.g., a div with id 'result')
                            document.getElementById('result').innerHTML = tableHtml;
                        } else {
                            document.getElementById('result').innerHTML = '<span class="result">Invalid data received from the server.</span>';
                        }
                    }

                    // Function to handle document release
                    function releaseDocument() {
                        if (!application_id) {
                            alert('No application ID found.');
                            return;
                        }

                        // Get current date and time
                        const currentDateTime = new Date().toISOString(); // ISO format: "YYYY-MM-DDTHH:MM:SSZ"

                        fetch('release_doc.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: new URLSearchParams({
                                'application_id': application_id,
                                'status': 'released',
                                'release_date': currentDateTime,
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === "success") {
                                // Update the UI with the new document status
                                document.getElementById('result').innerHTML = `<span class="result">Document has been released.</span>`;
                            } else {
                                document.getElementById('result').innerHTML = `<span class="result">${data.message}</span>`;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            document.getElementById('result').innerHTML = `<span class="result">Failed to release document.</span>`;
                        });
                    }


                    var html5QrcodeScanner = new Html5QrcodeScanner("reader", {
                        fps: 10,
                        qrbox: 250
                    });
                    html5QrcodeScanner.render(onScanSuccess, onScanError);
                </script>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="releaseDocument()">Release Document</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End QR code Modal -->

<!-- Update Renewal Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="updateModalLabel">Update User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label for="updateFirstname" class="form-label">First Name:</label>
                    <input type="text" class="form-control" id="updateFirstname" placeholder="First Name">
                </div>
                <div class="mb-3">
                    <label for="updateMiddlename" class="form-label">Middle Name:</label>
                    <input type="text" class="form-control" id="updateMiddlename" placeholder="Middle Name">
                </div>
                <div class="mb-3">
                    <label for="updateLastname" class="form-label">Last Name:</label>
                    <input type="text" class="form-control" id="updateLastname" placeholder="Last Name">
                </div>
                <div class="mb-3">
                    <label for="updateEmail" class="form-label">Email:</label>
                    <input type="text" class="form-control" id="updateEmail" placeholder="Email">
                </div>
                <div class="mb-3">
                    <label for="updatePhone" class="form-label">Number:</label>
                    <input type="text" class="form-control" id="updatePhone" placeholder="Number">
                </div>
                <div class="mb-3">
                    <label for="updateAddress" class="form-label">Address:</label>
                    <input type="text" class="form-control" id="updateAddress" placeholder="Address">
                </div>
                <div class="mb-3">
                    <label for="updateBusinessName" class="form-label">Business Name:</label>
                    <input type="text" class="form-control" id="updateBusinessName" placeholder="Business Name">
                </div>
                <div class="mb-3">
                    <label for="updateBusinessAddress" class="form-label">Business Address:</label>
                    <input type="text" class="form-control" id="updateBusinessAddress" placeholder="Business Address">
                </div>
                <div class="mb-3">
                    <label for="updateBuildingName" class="form-label">Building Name:</label>
                    <input type="text" class="form-control" id="updateBuildingName" placeholder="Building Name">
                </div>
                <div class="mb-3">
                    <label for="updateBuildingNo" class="form-label">Building No:</label>
                    <input type="text" class="form-control" id="updateBuildingNo" placeholder="Building No">
                </div>
                <div class="mb-3">
                    <label for="updateStreet" class="form-label">Street:</label>
                    <input type="text" class="form-control" id="updateStreet" placeholder="Street">
                </div>
                <div class="mb-3">
                    <label for="updateBarangay" class="form-label">Barangay:</label>
                    <input type="text" class="form-control" id="updateBarangay" placeholder="Barangay">
                </div>
                <div class="mb-3">
                    <label for="updateBusinessType" class="form-label">Business Type:</label>
                    <input type="text" class="form-control" id="updateBusinessType" placeholder="Business Type">
                </div>
                <div class="mb-3">
                    <label for="updateRentPerMonth" class="form-label">Rent Per Month:</label>
                    <input type="text" class="form-control" id="updateRentPerMonth" placeholder="Rent Per Month">
                </div>
               
                <div class="mb-3">
                    <label for="updateDateofApplication" class="form-label">Date of Application:</label>
                    <input type="date" class="form-control" id="updateDateofApplication" placeholder="Date of Application">
                </div>
                
                <div class="mb-3">
                    <label for="updateUploadDti" class="form-label">Upload DTI:</label>
                    <input type="file" class="form-control" id="updateUploadDti">
                </div>
                <div class="mb-3">
                    <label for="updateUploadStorePicture" class="form-label">Upload Store Picture:</label>
                    <input type="file" class="form-control" id="updateUploadStorePicture">
                </div>
                <div class="mb-3">
                    <label for="updateFoodSecurityClearance" class="form-label">Food Security Clearance:</label>
                    <input type="file" class="form-control" id="updateFoodSecurityClearance">
                </div>
                <div class="mb-3">
                    <label for="updateuploadoldPermit" class="form-label">Old Permit:</label>
                    <input type="file" class="form-control" id="updateuploadoldPermit">
                </div>
                <input type="hidden" id="updateId">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="updateUser()">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Update Renewal Modal -->



<!-- View renewal Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">View Registration Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Images Section -->
                    <div class="col-md-4">
                        <div class="border p-2 mb-3">
                            <h5 class="text-center">Store Picture</h5>
                            <img id="viewStorePicture" src="default_store_picture.jpg" alt="Store Picture" class="img-fluid rounded" onclick="showImageInModal(viewStorePicture.src)">
                        </div>
                        <div class="border p-2 mb-3">
                            <h5 class="text-center">Food Security Clearance</h5>
                            <img id="viewFoodSecurityClearance" src="default_food_security.jpg" alt="Food Security Clearance" class="img-fluid rounded" onclick="showImageInModal(viewFoodSecurityClearance.src)">
                        </div>
                        <div class="border p-2 mb-3">
                            <h5 class="text-center">DTI Document</h5>
                            <img id="viewUploadDti" src="default_dti.jpg" alt="DTI Document" class="img-fluid rounded" onclick="showImageInModal(viewUploadDti.src)">
                        </div>
                        <div class="border p-2 mb-3">
                            <h5 class="text-center">Old Permit</h5>
                            <img id="viewUploadOldPermit" src="default_upload_old_permit.jpg" alt="Old Permit" class="img-fluid rounded" onclick="showImageInModal(viewUploadOldPermit.src)">
                        </div>
                    </div>
                    
                    <!-- Text Details Section -->
                    <div class="col-md-8">
                        <div class="border p-3">
                            <h5>User Details</h5>
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
                            <p><strong>Date of Application:</strong> <span id="viewDateofApplication"></span></p>
                            <p><strong>application_number:</strong> <span id="viewapplication_number"></span></p>
                
                            <div class="border p-3">
                                <h5>Status:</h5>
                                <span id="viewDocumentStatus"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="hiddendata" value="">
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="updateDocumentStatus('Approved')">Approve</button>
                <button type="button" class="btn btn-danger" onclick="updateDocumentStatus('Rejected')">Notify</button>
                <button type="button" class="btn btn-primary" id="releaseButton" onclick="releaseApplication()" disabled>Released</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- View renewal Modal end -->


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
<!-- Image View Modal end -->


<div class="data-card">
    <div class="card">
        <div class="card-header">
            <h4>Renewal List
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
                <button type="submit" class="btn btn-primary" id="generateQRCodeBtn">Generate QR Code</button>
                <button type="button" class="btn btn-success" id="sendEmailBtn">Send Email</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Send Email Modal End -->




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
            url: "admin_renewal_list_displaydata.php",
            type: 'post',
            data: { displaysend: status },
            success: function(data) {
                if (status === 'All') {
                    $('#displayDataTableAll').html(data);
                } else if (status === 'Approved') {
                    $('#displayDataTableApproved').html(data);
                } else if (status === 'Pending') {
                    $('#displayDataTablePending').html(data);
                }
            }
        });
    }

    //send email modal
    function sendEmail(userId) {
        // Fetch user details via an AJAX POST request
        $.post("admin_renewal_get_details.php", {
            updateid: userId
        }, function(data, status) {
            // Parse the returned JSON data
            var user = JSON.parse(data);

            // Populate the fields in the Send Email Modal
            $('#permitBusinessName').text(user.business_name);
            $('#permitOwnerName').text(user.fname + ' ' + user.mname + ' ' + user.lname);
            $('#permitType').text(user.business_type);
            $('#permitExpiration').text(user.period_date);

            // Auto-fill the recipient email
            $('#releaseEmail').val(user.email);
            // Store the application number for future use
            var applicationNumber = user.application_number;

            $(document).ready(function() {
                // When modal is closed, hide the QR code image
                $('#sendEmailModal').on('hidden.bs.modal', function() {
                    $('#permitQRCode').addClass('d-none'); // Hide QR code image
                    $('#permitQRCode').attr('src', ''); // Reset the image source
                });

                // Handle form submission to generate the QR code
                $('#generateQRCodeBtn').click(function() {
                    const email = $('#releaseEmail').val();

                    // Send the email to generate QR code
                    $.post('generate_qr.php', {
                        application_number: applicationNumber
                    }, function(response) {
                        if (response.success) {
                            $('#permitQRCode').attr('src', response.qr_code_base64).removeClass('d-none'); // Show the QR code
                        } else {
                            alert('Error: ' + response.message);
                        }
                    }, 'json');
                });
            });

            // Handle "Send Email" button click
            $('#sendEmailBtn').click(function() {
                const recipientEmail = $('#releaseEmail').val();
                const customMessage = $('#releaseMessage').val();
                const qrCodeBase64 = $('#permitQRCode').attr('src');
                const businessName = $('#permitBusinessName').text();
                const ownerName = $('#permitOwnerName').text();
                const permitType = $('#permitType').text();

                // Validate that required fields are filled
                if (!recipientEmail || !qrCodeBase64) {
                    alert("Please provide the required information (recipient email and QR code).");
                    return;
                }

                // Prepare the data to send to the backend
                const data = {
                    recipient_email: recipientEmail,
                    qr_code_base64: qrCodeBase64,
                    custom_message: customMessage, // Optional
                    business_name: businessName,
                    owner_name: ownerName,
                    permit_type: permitType
                };

                // Send the data to the backend using AJAX (Fetch API)
                $.ajax({
                    url: 'send_email.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function(response) {
                        if (response.success) {
                            alert("Email sent successfully!");
                            // Close the modal after successful email sending
                            $('#sendEmailModal').modal('hide');
                        } else {
                            alert("Failed to send email: " + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("Error sending email: " + error);
                    }
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
            url:"admin_renewal_list_delete.php",
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


    // Function to get user details and populate the update modal
    function getdetails(updateid) {
        $('#updateId').val(updateid);

        // Make an AJAX request to fetch the details for the selected user
        $.post("admin_renewal_get_details.php", { updateid: updateid }, function(data, status) {
            var user = JSON.parse(data);

            // Populate the form fields with the fetched user data
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
            $('#updateBusinessType').val(user.business_type); // Set the Business Type field
            $('#updateRentPerMonth').val(user.rent_per_month);
            $('#updatePeriodofDate').val(user.period_date);
            $('#updateDateofApplication').val(user.date_application);
            $('#updateReceipt').val(user.reciept);
            $('#updateOrDate').val(user.or_date);
            $('#updateAmountPaid').val(user.amount_paid);
        });

        // Show the update modal
        $('#updateModal').modal("show");
    }

    // Function to update user details
    function updateUser() {
        var updateData = {
            id: $('#updateId').val(),
            fname: $('#updateFirstname').val(),
            mname: $('#updateMiddlename').val(),
            lname: $('#updateLastname').val(),
            email: $('#updateEmail').val(),
            phone: $('#updatePhone').val(),
            address: $('#updateAddress').val(),
            business_name: $('#updateBusinessName').val(),
            business_address: $('#updateBusinessAddress').val(),
            building_name: $('#updateBuildingName').val(),
            building_no: $('#updateBuildingNo').val(),
            street: $('#updateStreet').val(),
            barangay: $('#updateBarangay').val(),
            business_type: $('#updateBusinessType').val(),
            rent_per_month: $('#updateRentPerMonth').val(),
            period_date: $('#updatePeriodofDate').val(),
            date_application: $('#updateDateofApplication').val(),
            reciept: $('#updateReceipt').val(),
            or_date: $('#updateOrDate').val(),
            amount_paid: $('#updateAmountPaid').val()
        };

        $.post("update_renewal_get_details.php", updateData, function(response) {
            alert(response);
            $('#updateModal').modal("hide");
        });
    }

    
    // view function for displaying user details including image files
    function viewDetails(viewid) {
    $.post("admin_renewal_list_view.php", { viewid: viewid }, function(data, status) {
        var user = JSON.parse(data);

        if (user.error) {
            alert(user.error);
            return;
        }

        console.log("Document Status:", user.document_status); // Debugging

        $('#hiddendata').val(viewid);

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
        $('#viewDateofApplication').text(user.date_application);
        $('#viewapplication_number').text(user.application_number);
        $('#viewDocumentStatus').text(user.document_status);

        // Enable/Show the "Released" button if status is "Approved" or "Pending Release"
        if (user.document_status === 'Approved' || user.document_status === 'Pending Release') {
            $('#releaseButton').prop('disabled', false).show(); // Enable and show the button
        } else {
            $('#releaseButton').prop('disabled', true).hide(); // Disable and hide the button
        }

        // Handle images
        const storePicture = user.store_picture_url ? '/user/assets/image/' + user.store_picture_url : 'default_store_picture.jpg';
        const foodSecurityClearance = user.food_security_clearance_url ? '/user/assets/image/' + user.food_security_clearance_url : 'default_food_security.jpg';
        const uploadDti = user.upload_dti_url ? '/user/assets/image/' + user.upload_dti_url : 'default_dti.jpg';
        const uploadOldPermit = user.upload_old_permit_url ? '/user/assets/image/' + user.upload_old_permit_url : 'default_upload_old_permit.jpg';

        $('#viewStorePicture').attr('src', storePicture);
        $('#viewFoodSecurityClearance').attr('src', foodSecurityClearance);
        $('#viewUploadDti').attr('src', uploadDti);
        $('#viewUploadOldPermit').attr('src', uploadOldPermit);

        $('#viewStorePicture').on('click', function() {
            showImageInModal(storePicture);
        });
        $('#viewFoodSecurityClearance').on('click', function() {
            showImageInModal(foodSecurityClearance);
        });
        $('#viewUploadDti').on('click', function() {
            showImageInModal(uploadDti);
        });
        $('#viewUploadOldPermit').on('click', function() {
            showImageInModal(uploadOldPermit);
        });

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
            var viewId = $('#hiddendata').val(); // Get the hidden view ID

            if (!viewId || !status) {
            alert("View ID or Document Status is missing.");
            return;
            }

            $.post("admin_renewal_list_update_status.php", 
            {
            viewid: viewId,
            document_status: status
            }, 
        function(data) {
            console.log("Response:", data);
            if (data.success) {
                $('#viewDocumentStatus').text(status);
                alert("Document status updated to " + status);
                $('#viewModal').modal('hide');
                filterData('All'); // Refresh the list

                if (status === 'Rejected') {
                    alert("Your document was rejected. Please refill the renewal update form.");
                } else if (status === 'Approved') {
                    $('#releaseButton').show(); // Show the release button only after approval
                    alert("Application has been approved. You can now release it.");
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


   // Function to release the application
        function releaseApplication() {
            var viewId = $('#hiddendata').val(); // Get the hidden view ID

            if (!viewId) {
                alert("View ID is missing.");
                return;
            }

            // Disable the button to prevent multiple clicks
            $('#releaseButton').prop('disabled', true);

            // Update only application_status to "Released"
            $.post("admin_renewal_list_update_status.php", 
            {
                viewid: viewId,
                application_status: 'Released'  // Ensure only application_status is updated
            }, 
            function(data) {
                console.log("Response:", data);
                if (data.success) {
                    $('#viewApplicationStatus').text('Released'); // Update application status in UI
                    alert("Application has been successfully released.");
                    $('#releaseButton').hide(); // Hide the button after releasing
                    $('#viewModal').modal('hide'); // Close the modal
                    filterData('All'); // Refresh the table
                } else {
                    alert("Failed to release the application: " + data.error);
                    $('#releaseButton').prop('disabled', false); // Re-enable the button
                }
            }, "json")
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX request failed: " + textStatus + ", " + errorThrown);
                alert("AJAX request failed: " + textStatus);
                $('#releaseButton').prop('disabled', false); // Re-enable the button
            });
        }
        

</script>


</body>
</html>
