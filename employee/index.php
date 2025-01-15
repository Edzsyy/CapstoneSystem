<?php 
include('../employee/assets/config/dbconn.php');

include('../employee/assets/inc/header.php');

include('../employee/assets/inc/sidebar.php');

include('../employee/assets/inc/navbar.php');

?> 
   
<!--data info-->
 <div class="card-info">
    <a href="employee_registration_list.php">
        <div class="card-data">
            <i class='bx bxs-user-detail icon'></i>
            <div>
                <h3>1</h3>
                <span>Total Registration</span>
            </div>
        </div>
    </a>
    <a href="employee_renewal_list.php">
        <div class="card-data">
            <i class='bx bxs-user-detail icon'></i>
            <div>
                <h3>1</h3>
                <span>Total Renewal</span>
            </div>
        </div>
    </a>
    <a href="employee_employee_list.php">
        <div class="card-data">
            <i class='bx bxs-user-detail icon'></i>
            <div>
                <h3>2</h3>
                <span>Total Employees</span>
            </div>
        </div>
    </a>
    <a href="employee_user_list.php">
        <div class="card-data">
            <i class='bx bxs-user-detail icon'></i>
            <div>
                <h3>3</h3>
                <span>Total Users</span>
            </div>
        </div>
    </a>
</div>
<!--end data info-->


<div class="data-card">
    <div class="card">
        <div class="card-header">
            <h4>Registration List</h4>
        </div>


        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div id="displayDataTable">
                        <!-- employee_dashboard_list_displaydata -->
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
        fetchCounts(); // Initial load for counts
        displayData(); // Initial load for user data
        longPolling(); // Start long polling for user data
    });

    // Function to fetch counts for registrations, renewals, employees, and users
    function fetchCounts() {
        $.ajax({
            url: "employee_fetch_counts.php", // Fetch counts data
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
            url: "employee_fetch_user_data.php", // Fetch updated user data
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
            url:"employee_dashboard_list_displaydata.php",
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




    //delete function
    function deleteuser(deleteid)
    {
        $.ajax({
            url:"employee_registration_and_renewal_list_delete.php",
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


    //update record
    function getdetails(updateid)
    {
        $('#hiddendata').val(updateid);

        $.post("employee_registration_and_renewal_list_update.php", {updateid:updateid}, function(data,status)
        {
            var userid =JSON.parse(data);

            $('#updateFirstname').val(userid.fname);
            $('#updateLastname').val(userid.lname);
            $('#updateEmail').val(userid.email);
            $('#updatePhone').val(userid.phone);
            $('#updateAddress').val(userid.address);
        });

        $('#updateModal').modal("show");
    }

    //update function
    function updatedetails()
    {
        var updateFirstname = $('#updateFirstname').val();
        var updateLastname = $('#updateLastname').val();
        var updateEmail = $('#updateEmail').val();
        var updatePhone = $('#updatePhone').val();
        var updateAddress = $('#updateAddress').val();
        var hiddendata = $('#hiddendata').val();

        $.post("employee_registration_and_renewal_list_update.php",
        {
            updateFirstname:updateFirstname,
            updateLastname:updateLastname,
            updateEmail:updateEmail,
            updatePhone:updatePhone,
            updateAddress:updateAddress,
            hiddendata:hiddendata
        },
        function(data,status)
        {
            $('#updateModal').modal('hide');
            displayData();
        });
    }
    

</script>

</body>
</html>
