<?php
include('../employee/assets/config/dbconn.php');

// Initialize counts
$totalUsers = 0;
$totalRegistrations = 0;
$totalRenewals = 0;
$totalBusinesses = 0;

try {
    // Count total users
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users");
    $stmt->execute();
    $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total']; // Use fetch to get the associative array

    // Count total registrations
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM registration");
    $stmt->execute();
    $totalRegistrations = $stmt->fetch(PDO::FETCH_ASSOC)['total']; // Use fetch to get the associative array

    // Count total renewals
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM renewal");
    $stmt->execute();
    $totalRenewals = $stmt->fetch(PDO::FETCH_ASSOC)['total']; // Use fetch to get the associative array

    // Count total businesses
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM business_list");
    $stmt->execute();
    $totalBusinesses = $stmt->fetch(PDO::FETCH_ASSOC)['total']; // Use fetch to get the associative array

    // Return counts as JSON
    echo json_encode([
        'total_users' => $totalUsers,
        'total_registrations' => $totalRegistrations,
        'total_renewals' => $totalRenewals,
        'total_businesses' => $totalBusinesses,
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
