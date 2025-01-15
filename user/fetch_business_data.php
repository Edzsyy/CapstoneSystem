<?php
include('../user/assets/config/dbconn.php');

// Update the SQL query to select both registrations and renewals
$query = "
    SELECT 
        id, 
        email, 
        business_name, 
        business_address, 
        business_type, 
        period_date, 
        date_application, 
        permit_expiration, 
        document_status,
        CASE
            WHEN permit_expiration IS NULL OR permit_expiration = '0000-00-00' THEN 'N/A'
            WHEN permit_expiration < CURDATE() THEN 'Expired'
            WHEN permit_expiration BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'Needs Renewal'
            ELSE 'Active'
        END AS display_status
    FROM registration
    UNION ALL
    SELECT 
        id, 
        email, 
        business_name, 
        business_address, 
        business_type, 
        period_date, 
        date_application, 
        permit_expiration, 
        document_status,
        CASE
            WHEN permit_expiration IS NULL OR permit_expiration = '0000-00-00' THEN 'N/A'
            WHEN permit_expiration < CURDATE() THEN 'Expired'
            WHEN permit_expiration BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'Needs Renewal'
            ELSE 'Active'
        END AS display_status
    FROM renewal
";

$result = mysqli_query($conn, $query);


// Check if the query was successful
if (!$result) {
    die('Query failed: ' . mysqli_error($conn));
}

$businesses = array();
while ($row = mysqli_fetch_assoc($result)) {
    $businesses[] = $row; // Fetch data from both tables
}

// Debugging output
header('Content-Type: application/json');
echo json_encode($businesses);
?>
