<?php 
include('../user/assets/config/dbconn.php');

// Fetch data for the first table
$query = "SELECT email, business_name, business_address, business_type, period_date, date_application, id FROM registration"; 
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo '<table class="table table-striped">';
    echo '<thead>
            <tr>
                <th>Email</th>
                <th>Business Name</th>
                <th>Business Address</th>
                <th>Business Type</th>
                <th>Period Date</th>
                <th>Date of Application</th>
                <th>Actions</th>
            </tr>
          </thead>';
    echo '<tbody>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>
                <td>' . $row['email'] . '</td>
                <td>' . $row['business_name'] . '</td>
                <td>' . $row['business_address'] . '</td>
                <td>' . $row['business_type'] . '</td>
                <td>' . $row['period_date'] . '</td>
                <td>' . $row['date_application'] . '</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            &#x2022;&#x2022;&#x2022;
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#" onclick="viewDetails(' . $row['id'] . ')">View</a></li>
                            <li><a class="dropdown-item" href="#" onclick="getdetails(' . $row['id'] . ')">Update</a></li>
                    </div>
                </td>
              </tr>';
    }

    echo '</tbody>';
    echo '</table>';
} else {
    echo '<p>No records found.</p>';
}

mysqli_close($conn);
?>
