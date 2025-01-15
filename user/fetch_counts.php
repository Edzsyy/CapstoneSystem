<?php
include('../user/assets/config/dbconn.php');

// Initialize counts
$totalRegistration = 0;
$totalRenewal = 0;
$totalEmployees = 0;
$totalUsers = 0;

// Fetch total registrations
$result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM registration"); // Update table name accordingly
if ($row = mysqli_fetch_assoc($result)) {
    $totalRegistration = $row['count'];
}

// Fetch total renewals
$result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM renewal"); // Update table name accordingly
if ($row = mysqli_fetch_assoc($result)) {
    $totalRenewal = $row['count'];
}

// Fetch total employees
$result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM employee"); // Update table name accordingly
if ($row = mysqli_fetch_assoc($result)) {
    $totalEmployees = $row['count'];
}

// Fetch total users
$result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM users"); // Update table name accordingly
if ($row = mysqli_fetch_assoc($result)) {
    $totalUsers = $row['count'];
}

// Return JSON response
echo json_encode([
    'totalRegistration' => $totalRegistration,
    'totalRenewal' => $totalRenewal,
    'totalEmployees' => $totalEmployees,
    'totalUsers' => $totalUsers
]);
?>
