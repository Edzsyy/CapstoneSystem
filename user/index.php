<?php 
include('../user/assets/config/dbconn.php');
include('../user/assets/inc/header.php');
include('../user/assets/inc/sidebar.php');
include('../user/assets/inc/navbar.php');
?> 

<!--data info-->
<div class="card-info" id="cardInfo">
    <a href="../user/user_registration_list.php">
        <div class="card-data">
            <i class='bx bxs-user-detail icon'></i>
            <div>
                <h3 id="totalRegistration">0</h3>
                <span>Total Registration</span>
            </div>
        </div>
    </a>
    <a href="../user/user_renewal_list.php">
        <div class="card-data">
            <i class='bx bxs-user-detail icon'></i>
            <div>
                <h3 id="totalRenewal">0</h3>
                <span>Total Renewal</span>
            </div>
        </div>
    </a>
    <a href="../employee/employee_employee_list.php">
        <div class="card-data">
            <i class='bx bxs-user-detail icon'></i>
            <div>
                <h3 id="totalEmployees">0</h3>
                <span>Total Employees</span>
            </div>
        </div>
    </a>
    <a href="../user/user_registration_list.php">
        <div class="card-data">
            <i class='bx bxs-user-detail icon'></i>
            <div>
                <h3 id="totalUsers">0</h3>
                <span>Total Users</span>
            </div>
        </div>
    </a>
</div>
<!--end data info-->

<div class="data-card">
    <div class="card">
        <div class="card-header">
            <h4>Status Ducuments</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div id="displayDataTable">
                        <!-- User data will be displayed here -->
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
        fetchCounts(); // Initial load for counts
        displayData(); // Initial load for user data
        longPolling(); // Start long polling for user data
    });

    // Function to fetch counts for registrations, renewals, employees, and users
    function fetchCounts() {
        $.ajax({
            url: "fetch_counts.php", // Fetch counts data
            type: 'GET',
            success: function(data) {
                const counts = JSON.parse(data);
                $('#totalRegistration').text(counts.totalRegistration);
                $('#totalRenewal').text(counts.totalRenewal);
                $('#totalEmployees').text(counts.totalEmployees);
                $('#totalUsers').text(counts.totalUsers);
            },
            complete: function() {
                setTimeout(fetchCounts, 60000); // Refresh counts every 60 seconds
            }
        });
    }

    // Function to fetch user data
    function displayData() {
        $.ajax({
            url: "fetch_user_data.php", // Fetch updated user data
            type: 'GET',
            success: function(data) {
                // Update the display based on returned data
                let displayHTML = "";
                data.forEach(user => {
                    displayHTML += `<div class="user-card">
                        <h4>${user.fname} ${user.lname}</h4>
                        <p>Email: ${user.email}</p>
                        <p>Phone: ${user.phone}</p>
                        <p>Address: ${user.address}</p>
                    </div>`;
                });
                $('#displayDataTable').html(displayHTML);
            },
            complete: function() {
                // Set up the next polling for user data
                setTimeout(displayData, 5000); // Poll every 5 seconds
            }
        });
    }

    //delete function
    function deleteuser(deleteid) {
        $.ajax({
            url: "user_registration_and_renewal_list_delete.php",
            type: 'post',
            data: {
                deletesend: deleteid
            },
            success: function(data, status) {
                displayData(); // Refresh data after deletion
            }
        });
    }

    //update record
    function getdetails(updateid) {
        $('#hiddendata').val(updateid);
        $.post("user_registration_and_renewal_list_update.php", {updateid: updateid}, function(data, status) {
            var userid = JSON.parse(data);
            $('#updateFirstname').val(userid.fname);
            $('#updateLastname').val(userid.lname);
            $('#updateEmail').val(userid.email);
            $('#updatePhone').val(userid.phone);
            $('#updateAddress').val(userid.address);
        });
        $('#updateModal').modal("show");
    }

    //update function
    function updatedetails() {
        var updateFirstname = $('#updateFirstname').val();
        var updateLastname = $('#updateLastname').val();
        var updateEmail = $('#updateEmail').val();
        var updatePhone = $('#updatePhone').val();
        var updateAddress = $('#updateAddress').val();
        var hiddendata = $('#hiddendata').val();

        $.post("user_registration_and_renewal_list_update.php",
        {
            updateFirstname: updateFirstname,
            updateLastname: updateLastname,
            updateEmail: updateEmail,
            updatePhone: updatePhone,
            updateAddress: updateAddress,
            hiddendata: hiddendata
        },
        function(data, status) {
            $('#updateModal').modal('hide');
            displayData(); // Refresh data after update
        });
    }
</script>

</body>
</html>
