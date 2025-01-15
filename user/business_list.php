<?php 
include('../user/assets/config/dbconn.php');
include('../user/assets/inc/header.php');
include('../user/assets/inc/sidebar.php');
include('../user/assets/inc/navbar.php');
?> 

<!-- Data info -->
<div class="data-card">
    <div class="card">
        <div class="card-header">
            <h4>Business List</h4>
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
include('../user/assets/inc/footer.php');
?> 

<script>
$(document).ready(function() {
    displayBusinessData('Approved'); // Initial load for approved businesses
    setInterval(() => {
        displayBusinessData($('#businessStatusTabs .active').text()); // Refresh data for the active tab
    }, 60000); // Refresh every 60 seconds
});

// Function to fetch business data based on document status
function displayBusinessData(status) {
    $.ajax({
        url: "fetch_business_data.php", // Fetch business data
        type: 'GET',
        dataType: 'json',
        success: function(data) {
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
        },
        complete: function() {
            setTimeout(() => {
                displayBusinessData(status);
            }, 60000); // Refresh every 60 seconds
        }
    });
}



// Function to show registration form (if you still need it)
function showRegistrationForm() {
    const formHTML = `
        <h4>Register New Business</h4>
        <form id="newBusinessForm">
            <input type="text" name="lname" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="text" name="business_name" placeholder="Business Name" required>
            <input type="text" name="com_address" placeholder="Commercial Address" required>
            <input type="text" name="date_application" placeholder="Date of Application" required>
            <input type="text" name="period_date" placeholder="Period of Date" required>
            <button type="submit">Submit</button>
        </form>
    `;
    $('#registrationForm').html(formHTML).show();
}

// Event listener for the registration form submission
$(document).on('submit', '#newBusinessForm', function(e) {
    e.preventDefault();
    $.ajax({
        url: 'user_registration_list_displaydata.php', // Your script to process registration
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            alert('Registration successful!');
            displayBusinessData(); // Refresh business list
            $('#registrationForm').hide(); // Hide form after submission
        },
        error: function() {
            alert('Error registering business');
        }
    });
});

// Function to renew business
function renewBusiness(id, element) {
    // Confirm renewal action
    if (confirm('Are you sure you want to renew this business?')) {
        $.ajax({
            url: 'user_renewal_list_displaydata.php', // Your script to process renewal
            type: 'POST',
            data: { id: id },
            success: function(response) {
                alert('Business renewed successfully!');
                displayBusinessData(); // Refresh business list
            },
            error: function() {
                alert('Error renewing business');
            }
        });
    }
}

// Implement functions to edit and save updates if needed
function toggleEdit(id, element) {
    // Toggle edit functionality here
}

function saveUpdate(id, element) {
    // Save update functionality here
}
</script>

</body>
</html> 
