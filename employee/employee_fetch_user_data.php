<?php
include('../user/assets/config/dbconn.php');

if (isset($_POST['application_id'])) {
    $application_id = $_POST['application_id'];

    $query = "SELECT * FROM registration WHERE application_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data); // Respond with JSON
    } else {
        echo json_encode([]); // Respond with empty array if no data found
    }

    $stmt->close();
}
?>
