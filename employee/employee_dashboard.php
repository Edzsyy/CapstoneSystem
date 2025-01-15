<?php 
include('../employee/assets/config/dbconn.php');
include('../employee/assets/inc/header.php');
include('../employee/assets/inc/sidebar.php');
include('../employee/assets/inc/navbar.php');
?> 

<!-- Data Info -->
<div class="card-info">
    <a href="employee_user_list.php">
        <div class="card-data">
            <i class='bx bxs-user-detail icon'></i>
            <div>
                <h3 id="totalUsers">0</h3>
                <span>Total Users</span>
            </div>
        </div>
    </a>
    <a href="employee_registration_list.php">
        <div class="card-data">
            <i class='bx bxs-user-detail icon'></i>
            <div>
                <h3 id="totalRegistration">0</h3>
                <span>Total Registration</span>
            </div>
        </div>
    </a>
    <a href="employee_renewal_list.php">
        <div class="card-data">
            <i class='bx bxs-user-detail icon'></i>
            <div>
                <h3 id="totalRenewals">0</h3> <!-- Ensure the ID matches your JavaScript -->
                <span>Total Renewal</span>
            </div>
        </div>
    </a>
    <a href="business_aprove_list_data.php">
        <div class="card-data">
            <i class='bx bxs-briefcase icon'></i>
            <div>
                <h3 id="totalRegistered">0</h3> <!-- Ensure the ID matches your JavaScript -->
                <span>Total Registered</span>
            </div>
        </div>
    </a>

</div>
<!-- End Data Info -->


<!-- Business Status Tabs -->
<div class="data-card">
    <div class="card">
        <div class="card-header">
            <h4>Business Status</h4>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="businessStatusTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab" aria-controls="approved" aria-selected="true" onclick="displayBusinessData('Approved')">Approved</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab" aria-controls="rejected" aria-selected="false" onclick="displayBusinessData('Rejected')">Rejected</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="false" onclick="displayBusinessData('Pending')">Pending</button>
                </li>
            </ul>
            <div class="tab-content" id="businessStatusTabsContent">
                <div class="tab-pane fade show active" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                    <div id="displayApprovedBusinessDataTable">
                        <!-- Approved business data will be displayed here -->
                    </div>
                </div>
                <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                    <div id="displayRejectedBusinessDataTable">
                        <!-- Rejected business data will be displayed here -->
                    </div>
                </div>
                <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                    <div id="displayPendingBusinessDataTable">
                        <!-- Pending business data will be displayed here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
include('../employee/assets/inc/footer.php');
?> 

<script>
$(document).ready(function() {
    // Fetch the counts initially
    fetchCounts();

    // Set an interval to fetch counts every 30 seconds
    setInterval(fetchCounts, 30000);

    function fetchCounts() {
        $.ajax({
            url: 'employee_get_counts.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Update the displayed counts
                $('#totalUsers').text(data.total_users);
                $('#totalRegistration').text(data.total_registrations);
                $('#totalRenewals').text(data.total_renewals);
                $('#totalRegistered').text(data.total_businesses); // Update this ID to match your new count
            },
            error: function(xhr, status, error) {
                console.error("Error fetching counts:", error);
            }
        });
    }

    displayBusinessData('Approved'); // Initial load for approved businesses
    setInterval(() => {
        displayBusinessData($('#businessStatusTabs .active').text()); // Refresh data for the active tab
    }, 60000); // Refresh every 60 seconds
});


// Function to fetch business data based on document status
function displayBusinessData(status) {
    $.ajax({
        url: "data_list_table.php", // Adjust this URL to your data fetching script
        type: 'POST',
        data: { status: status },
        dataType: 'json',
        success: function(data) {
            console.log("Raw data:", data); // Check the raw data structure

            if (Array.isArray(data)) {
                let filteredData = data.filter(business => business.document_status === status); // Filter based on status
                let displayHTML = `<table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Business Name</th>
                            <th>Business Address</th>
                            <th>Business Type</th>
                            <th>Period of Date</th>
                            <th>Date of Application</th>
                            <th>Document Status</th>
                            <th>Permit Expiration</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>`;

                filteredData.forEach(business => {
                    displayHTML += `<tr>
                        <td>${business.email}</td>
                        <td>${business.business_name}</td>
                        <td>${business.business_address}</td>
                        <td>${business.business_type}</td>
                        <td>${business.period_date || 'N/A'}</td>
                        <td>${business.date_application}</td>
                        <td>${business.document_status || 'Pending'}</td>
                        <td>${business.permit_expiration || 'N/A'}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="actionMenu${business.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class='bx bx-dots-vertical-rounded'></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="actionMenu${business.id}">
                                    <a class="dropdown-item" href="view_business.php?id=${business.id}">View</a>
                                    <a class="dropdown-item" href="document_view.php?id=${business.id}">Renew</a>
                                    <a class="dropdown-item" href="document_view.php?id=${business.id}">Document View</a>
                                    <a class="dropdown-item" href="#" onclick="deleteBusiness(${business.id},)">Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>`;
                });

                displayHTML += `</tbody></table>`;

                // Update the appropriate tab content
                if (status === 'Approved') {
                    $('#displayApprovedBusinessDataTable').html(displayHTML);
                } else if (status === 'Rejected') {
                    $('#displayRejectedBusinessDataTable').html(displayHTML);
                } else {
                    $('#displayPendingBusinessDataTable').html(displayHTML);
                }
            } else {
                console.error("Expected an array but received:", data);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error fetching data:", error);
        }
    });
}

// Function to delete a business
function deleteBusiness(businessId) {
    if (confirm("Are you sure you want to delete this business?")) {
        $.ajax({
            url: "employee_registration_list_delete.php",
            url: "employee_renewal_list_delete.php", // Adjust this URL to your delete script
            type: "POST",
            data: { id: businessId },
            success: function(response) {
                alert("Business deleted successfully.");
                // Refresh the current tab's business data
                displayBusinessData($('#businessStatusTabs .active').text());
            },
            error: function(xhr, status, error) {
                console.error("Error deleting business:", error);
            }
        });
    }
}

// Update record
function getdetails(updateid) {
    $('#hiddendata').val(updateid);

    $.post("employee_registration_and_renewal_list_update.php", { updateid: updateid }, function(data, status) {
        var userid = JSON.parse(data);

        $('#updateFirstname').val(userid.fname);
        $('#updateLastname').val(userid.lname);
        $('#updateEmail').val(userid.email);
        $('#updatePhone').val(userid.phone);
        $('#updateAddress').val(userid.address);
    });

    $('#updateModal').modal("show");
}

// Update function
function updatedetails() {
    var updateFirstname = $('#updateFirstname').val();
    var updateLastname = $('#updateLastname').val();
    var updateEmail = $('#updateEmail').val();
    var updatePhone = $('#updatePhone').val();
    var updateAddress = $('#updateAddress').val();
    var hiddendata = $('#hiddendata').val();

    $.post("employee_registration_and_renewal_list_update.php", {
        updateFirstname: updateFirstname,
        updateLastname: updateLastname,
        updateEmail: updateEmail,
        updatePhone: updatePhone,
        updateAddress: updateAddress,
        hiddendata: hiddendata
    }, function(data, status) {
        alert(data);
        $('#updateModal').modal("hide");
        displayBusinessData($('#businessStatusTabs .active').text()); // Refresh the data
    });
}
</script>

</body>
</html>
