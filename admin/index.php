<?php 
session_start();
include('../admin/assets/config/dbconn.php');

include('../admin/assets/inc/header.php');

include('../admin/assets/inc/sidebar.php');

include('../admin/assets/inc/navbar.php');

?> 
   
<!--data info-->
 <div class="card-info">
    <a href="#">
        <div class="card-data">
            <i class='bx bxs-collection icon' ></i>
            <div>
                <h3>12345</h3>
                <span>Total Collection</span>
            </div>
        </div>
    </a>
    <a href="#">
        <div class="card-data">
            <i class='bx bxs-user-plus icon' ></i>
            <div>
                <h3>67890</h3>
                <span>Total Registration</span>
            </div>
        </div>
    </a>
    <a href="#">
        <div class="card-data">
            <i class='bx bxs-user-check icon' ></i>
            <div>
                <h3>02468</h3>
                <span>Total Renewal</span>
            </div>
        </div>
    </a>
    <a href="#">
        <div class="card-data">
            <i class='bx bxs-business icon' ></i>
            <div>
                <h3>36912</h3>
                <span>Total Business Categories</span>
            </div>
        </div>
    </a>
</div>
<!--end data info-->


<div class="data-card">
    <div class="card">
        <div class="card-header">
            <h4>Registration and Renewal List</h4>
        </div>


        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div id="displayDataTable">
                        <!-- admin_dashboard_list_displaydata -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<?php 
include('../admin/assets/inc/footer.php');
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
            url:"admin_dashboard_list_displaydata.php",
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
            url:"admin_registration_and_renewal_list_delete.php",
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

        $.post("admin_registration_and_renewal_list_update.php", {updateid:updateid}, function(data,status)
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

        $.post("admin_registration_and_renewal_list_update.php",
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
