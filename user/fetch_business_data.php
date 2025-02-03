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
        application_number,  
        date_application, 
        expiration_date, 
        document_status,
        CASE
            WHEN expiration_date IS NULL OR expiration_date = '0000-00-00' THEN 'N/A'
            WHEN expiration_date < CURDATE() THEN 'Expired'
            WHEN expiration_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'Needs Renewal'
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
        application_number,   
        date_application, 
        expiration_date, 
        document_status,
        CASE
            WHEN expiration_date IS NULL OR expiration_date = '0000-00-00' THEN 'N/A'
            WHEN expiration_date < CURDATE() THEN 'Expired'
            WHEN expiration_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'Needs Renewal'
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
