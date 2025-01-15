<?php 
include('../employee/assets/config/dbconn.php');

if (isset($_POST['deletesend'])) {
    // Sanitize input
    $unique = intval($_POST['deletesend']); // Ensure it's an integer

    // Prepare the SQL statement to prevent SQL injection
    $sql = "DELETE FROM registration WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameter
        $stmt->bind_param("i", $unique);
        
        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Delete operation failed."]);
        }
        
        // Close the statement
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "SQL preparation failed."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No ID provided."]);
}

// Close the database connection
$conn->close();
?>