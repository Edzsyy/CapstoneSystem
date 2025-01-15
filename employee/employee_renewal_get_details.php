<?php
// Include your database connection
include '../employee/assets/config/dbconn.php';

if (isset($_POST['updateid'])) {
    $updateid = $_POST['updateid'];

    // Prepare the SQL query to fetch renewal details
    $query = "SELECT * FROM renewal WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $updateid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the user data
        $user = $result->fetch_assoc();

        // Convert the user data to JSON format and return it
        echo json_encode($user);
    } else {
        // If no record found, return an empty JSON object
        echo json_encode([]);
    }

    $stmt->close();
    $conn->close();
}
?>
