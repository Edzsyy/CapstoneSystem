<?php 
include('../admin/assets/config/dbconn.php');

// Fetch data from both registration and renewal tables
$query = "
    SELECT email, business_name, business_address, business_type, period_date, date_application, id 
    FROM registration 
    UNION ALL 
    SELECT email, business_name, business_address, business_type, period_date, date_application, id 
    FROM renewal
"; 

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
                <td>' . htmlspecialchars($row['email']) . '</td>
                <td>' . htmlspecialchars($row['business_name']) . '</td>
                <td>' . htmlspecialchars($row['business_address']) . '</td>
                <td>' . htmlspecialchars($row['business_type']) . '</td>
                <td>' . htmlspecialchars($row['period_date']) . '</td>
                <td>' . htmlspecialchars($row['date_application']) . '</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            &#x2022;&#x2022;&#x2022;
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#" onclick="viewDetails(' . $row['id'] . ')">View</a></li>
                            <li><a class="dropdown-item" href="#" onclick="getdetails(' . $row['id'] . ')">Update</a></li>
                            <li><a class="dropdown-item" href="#" onclick="deleteuser(' . $row['id'] . ')">Delete</a></li>
                        </ul>
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
