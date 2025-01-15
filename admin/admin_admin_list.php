<?php 
include('../admin/assets/config/dbconn.php');

include('../admin/assets/inc/header.php');

include('../admin/assets/inc/sidebar.php');

include('../admin/assets/inc/navbar.php');

?> 

<!-- Modal -->
<!---add user-->
<!-- Modal for adding employee -->
<div class="modal fade" id="adduserModal" tabindex="-1" aria-labelledby="adduserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="adduserModalLabel">Add admin</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label for="inputFirstname" class="form-label">First Name:</label>
                    <input type="text" class="form-control" id="inputFirstname" placeholder="First Name">
                </div>
                <div class="mb-3">
                    <label for="inputLastname" class="form-label">Last Name:</label>
                    <input type="text" class="form-control" id="inputLastname" placeholder="Last Name">
                </div>
                <div class="mb-3">
                    <label for="inputEmail" class="form-label">Email:</label>
                    <input type="text" class="form-control" id="inputEmail" placeholder="Email">
                </div>
                <div class="mb-3">
                    <label for="inputPhone" class="form-label">Number</label>
                    <input type="text" class="form-control" id="inputPhone" placeholder="Number">
                </div>
                <div class="mb-3">
                    <label for="inputAddress" class="form-label">Address</label>
                    <input type="text" class="form-control" id="inputAddress" placeholder="Address">
                </div>
                <div class="mb-3">
                    <label for="inputPassword" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="inputPassword" placeholder="Password">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="adduser()">Add</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!---end add user-->



<!---update user-->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="updateModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label for="updateFirstname" class="form-label">Fisrt Name:</label>
                    <input type="text" class="form-control" id="updateFirstname" placeholder="First Name">
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
                    <label for="updatePhone" class="form-label">Number</label>
                    <input type="text" class="form-control" id="updatePhone" placeholder="Number">
                </div>
                <div class="mb-3">
                    <label for="updateAddress" class="form-label">Address</label>
                    <input type="text" class="form-control" id="updateAddress" placeholder="Address">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="updatedetails()">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <input type="hidden" id="hiddendata">
            </div>
        </div>
    </div>
</div>
<!---end update user-->



<!---QR code-->
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
      .result {
        background-color: green;
        color: #fff;
        padding: 20px;
      }
      .row {
        display: flex;
      }
      table {
        width: 100%;
        border-collapse: collapse;
      }
      th, td {
        padding: 10px;
        border: 1px solid #ddd;
      }
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
    // Extract application ID from QR code message
    const ApplicationIDMatch = qrCodeMessage.match(/ApplicationID:(\d{11})/);
    if (applicationIdMatch) {
        const application_id = ApplicationIDMatch[1];
        fetchDataFromServer(application_id);
    } else {
        document.getElementById('result').innerHTML = '<span class="result">QR code does not contain valid application ID</span>';
    }
}

function onScanError(errorMessage) {
    // Handle scan error
    console.error('Scan error:', errorMessage);
}

function fetchDataFromServer(application_id) {
    fetch('fetch_data.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'application_id': application_id
        })
    })
    .then(response => response.json())
    .then(data => {
        renderDataInTable(data);
    })
    .catch(error => console.error('Error:', error));
}

function renderDataInTable(data) {
    if (!data || data.length === 0) {
        document.getElementById('result').innerHTML = '<span class="result">No data found</span>';
        return;
    }

    let table = '<table>';
    table += '<tr><th>Application ID</th><th>Owner</th><th>Business Name</th><th>Business Type</th><th>Address</th><th>Status</th></tr>';

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

var html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", { fps: 10, qrbox: 250 });
html5QrcodeScanner.render(onScanSuccess, onScanError);
</script>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="adduser()">Submit</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!---end QR code-->



<div class="data-card">
    <div class="card">
        <div class="card-header">
            <h4>Employee List
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#adduserModal">
                    <i class='bx bx-plus-medical' ></i> Add admin
                </button>
                <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#qrcodeModal">
                    <i class='bx bx-qr-scan'></i>
                </button>
            </h4>
        </div>


        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div id="displayDataTable">
                        <!-- admin_employee_list_displaydata.php -->
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
            url:"admin_admin_list_displaydata.php",
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



    function adduser() {
    var fnameAdd = $('#inputFirstname').val();
    var lnameAdd = $('#inputLastname').val();
    var emailAdd = $('#inputEmail').val();
    var phoneAdd = $('#inputPhone').val();
    var addressAdd = $('#inputAddress').val();
    var passwordAdd = $('#inputPassword').val(); // Get the password input value

    $.ajax({
        url: "admin_admin_list_insert.php",
        type: 'post',
        data: {
            fnameSend: fnameAdd,
            lnameSend: lnameAdd,
            emailSend: emailAdd,
            phoneSend: phoneAdd,
            addressSend: addressAdd,
            passwordSend: passwordAdd // Include password in the data sent
        },
        success: function(data) {
            // Display a success or error message to the user
            alert(data); // This can be replaced with a more user-friendly notification system
            $('#adduserModal').modal('hide');
            displayData();
        },
        error: function(xhr, status, error) {
            console.error(error);
            alert("An error occurred while adding the employee.");
        }
    });
    }


    //delete function
    function deleteuser(deleteid)
    {
        $.ajax({
            url:"admin_admin_list_delete.php",
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

        $.post("admin_admin_list_update.php", {updateid:updateid}, function(data,status)
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

        $.post("admin_admin_list_update.php",
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
