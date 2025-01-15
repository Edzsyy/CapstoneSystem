<?php 
include('../admin/assets/config/dbconn.php');
include('../admin/assets/inc/header.php');
include('../admin/assets/inc/sidebar.php');
include('../admin/assets/inc/navbar.php');
?> 

<!-- Data info for Registered Business -->
<div class="data-card">
    <div class="card">
        <div class="card-header">
            <h4>Registered Business List (Approved Only)</h4>
        </div>
        <div class="card-body">
            <div id="displayRegisteredBusinessDataTable">
                <!-- Registered business data will be displayed here -->
            </div>
        </div>
    </div>
</div>

<?php 
include('../employee/assets/inc/footer.php');
?> 

<script>
$(document).ready(function() {
    displayRegisteredBusinessData(); // Initial load for registered businesses
    setInterval(() => {
        displayRegisteredBusinessData(); // Refresh every 60 seconds
    }, 60000); 
});

// Function to fetch registered business data and filter for Approved status
function displayRegisteredBusinessData() {
    $.ajax({
        url: "admin_approve_data_list_table.php", 
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // Filter data for only businesses with 'Approved' status
            let approvedBusinesses = data.filter(business => business.document_status === 'Approved');

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

            approvedBusinesses.forEach(business => {
                displayHTML += `<tr>
                    <td>${business.email}</td>
                    <td>${business.business_name}</td>
                    <td>${business.business_address}</td>
                    <td>${business.business_type}</td>
                    <td>${business.period_date || 'N/A'}</td>
                    <td>${business.date_application}</td>
                    <td>${business.document_status}</td>
                    <td>${business.permit_expiration || 'N/A'}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="actionMenu${business.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class='bx bx-dots-vertical-rounded'></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="actionMenu${business.id}">
                                <a class="dropdown-item" href="view_registered_business.php?id=${business.id}">View</a>
                                <a class="dropdown-item" href="renew_registered_business.php?id=${business.id}">Renew</a>
                                <a class="dropdown-item" href="document_view.php?id=${business.id}">Document View</a>
                                <a class="dropdown-item" href="document_view.php?id=${business.id}">Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>`;
            });

            displayHTML += `</tbody></table>`;

            // Update the content
            $('#displayRegisteredBusinessDataTable').html(displayHTML);
        },
        complete: function() {
            setTimeout(() => {
                displayRegisteredBusinessData(); // Refresh every 60 seconds
            }, 60000);
        }
    });
}
</script>

</body>
</html>
