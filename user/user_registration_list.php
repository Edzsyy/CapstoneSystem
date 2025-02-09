<?php 
include('../user/assets/config/dbconn.php');
include('../user/assets/inc/header.php');
include('../user/assets/inc/sidebar.php');
include('../user/assets/inc/navbar.php');
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
                <script src="../user/assets/js/html5-qrcode.min.js"></script>
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

<!---update registration-->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
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
                    <label for="updateapplication_number" class="form-label">application_number:</label>
                    <input type="text" class="form-control" id="updateapplication_number" placeholder="application_number">
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
                <input type="hidden" id="updateId">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="updateUser()">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!---end update registration-->

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
                            <p><strong>Date of Application:</strong> <span id="viewDateofApplication"></span></p>
                            <p><strong>application_number:</strong> <span id="viewapplication_number"></span></p>
                            <p><strong>Status:</strong> <span id="viewDocumentStatus"></span></p> <!-- Added Status -->
                        </div>
                    </div>
                </div>

            <div class="modal-footer">
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
            <h4>Registration List
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#qrcodeModal">
                    <i class='bx bx-qr-scan'></i>
                </button>
            </h4>
        </div>


        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div id="displayDataTable">
                        <!-- user_registration_and_renewal_list_displaydata -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<?php 
include('../user/assets/inc/footer.php');
?> 


<script>
    $(document).ready(function()
    {
        displayData();
    });
    //display function
    function displayData()
    {
        var displayData="true";
        $.ajax
        ({
            url:"user_registration_list_displaydata.php",
            type:'post',
            data:{
                displaysend:displayData
            },
            success:function(data,status)
            {
                $('#displayDataTable').html(data);
                $('#updateModal').modal('hide');
            }
        });
    }



    // Update record function (make sure to include correct ids)
    function getdetails(updateid) {
        $('#hiddendata').val(updateid);
        
        $.post("user_registration_list_update.php", { updateid: updateid }, function(data, status) {
            var userid = JSON.parse(data);

            $('#updateFirstname').val(userid.fname);
            $('#updateLastname').val(userid.lname);
            $('#updateEmail').val(userid.email);
            $('#updatePhone').val(userid.phone);
            $('#updateAddress').val(userid.address);
            $('#updateBusinessName').val(userid.business_name);
            $('#updateCommercialAddress').val(userid.business_address);
            $('#updateDateofApplication').val(userid.date_application);
        });

        $('#updateModal').modal("show");
    }

    // Update getdetails function
    function getdetails(updateid) {
        $('#hiddendata').val(updateid);

        $.post("user_registration_list_update.php", {updateid:updateid}, function(data,status) {
            var userid = JSON.parse(data);

            $('#updateFirstname').val(userid.fname);
            $('#updateMiddlename').val(userid.mname); // Added Middle Name
            $('#updateLastname').val(userid.lname);
            $('#updateEmail').val(userid.email);
            $('#updatePhone').val(userid.phone);
            $('#updateAddress').val(userid.address);
            $('#updateBusinessName').val(userid.business_name);
            $('#updateCommercialAddress').val(userid.business_address);
            $('#updateBusinessType').val(userid.business_type); // Added Business Type
            $('#updateDateofApplication').val(userid.date_application);
            $('#updateDateApproved').val(userid.date_approved); // Added Date Approved
            $('#updateExperationDate').val(userid.expiration_date); // Added Date Expiration
        });

        $('#updateModal').modal("show");
    }

    // Update updatedetails function
    function updatedetails() {
        var updateFirstname = $('#updateFirstname').val();
        var updateMiddlename = $('#updateMiddlename').val(); // New
        var updateLastname = $('#updateLastname').val();
        var updateEmail = $('#updateEmail').val();
        var updatePhone = $('#updatePhone').val();
        var updateAddress = $('#updateAddress').val();
        var updateBusinessName = $('#updateBusinessName').val();
        var updateCommercialAddress = $('#updateCommercialAddress').val();
        var updateBusinessType = $('#updateBusinessType').val(); // New
        var updateDateofApplication = $('#updateDateofApplication').val();
        var updateDateApproved = $('#updateDateApproved').val(); // New
        var updateDateExpiration = $('#updateDateExpiration').val(); // New
        var hiddendata = $('#hiddendata').val();

        $.post("user_registration_list_update.php", {
            updateFirstname: updateFirstname,
            updateMiddlename: updateMiddlename, // New
            updateLastname: updateLastname,
            updateEmail: updateEmail,
            updatePhone: updatePhone,
            updateAddress: updateAddress,
            updateBusinessName: updateBusinessName,
            updateCommercialAddress: updateCommercialAddress,
            updateBusinessType: updateBusinessType, // New
            updateDateofApplication: updateDateofApplication,
            updateDateApproved: updateDateApproved, // New
            updateDateExpiration: updateDateExpiration, // New
            hiddendata: hiddendata
        }, function(data, status) {
            $('#updateModal').modal('hide');
            displayData();
        });
    }


    

   // View function for displaying user details, including image files
    function viewDetails(viewid) {
        $.post("user_registration_list_view.php", { viewid: viewid }, function(data, status) {
            var user = JSON.parse(data);

            if (user.error) {
                alert(user.error);
                return;
            }

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
            $('#viewDateofApplication').text(user.date_application);
            $('#viewapplication_number').text(user.application_number);
            $('#viewDocumentStatus').text(user.document_status);

            // Handle image files
            const storePicture = user.store_picture_url ? '/user/assets/image/' + user.store_picture_url : 'default_store_picture.jpg';
            const foodSecurityClearance = user.food_security_clearance_url ? '/user/assets/image/' + user.food_security_clearance_url : 'default_food_security.jpg';
            const uploadDti = user.upload_dti_url ? '/user/assets/image/' + user.upload_dti_url : 'default_dti.jpg';

            $('#viewStorePicture').attr('src', storePicture);
            $('#viewFoodSecurityClearance').attr('src', foodSecurityClearance);
            $('#viewUploadDti').attr('src', uploadDti);

            // Add click events to open the image modal for viewing full size
            $('#viewStorePicture').off('click').on('click', function() {
                showImageInModal(storePicture);
            });
            $('#viewFoodSecurityClearance').off('click').on('click', function() {
                showImageInModal(foodSecurityClearance);
            });
            $('#viewUploadDti').off('click').on('click', function() {
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

</script>


</body>
</html>