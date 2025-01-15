<?php
include('../user/assets/config/dbconn.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch existing business data
    $query = "SELECT * FROM businesses WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $business = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get updated business details from the form
    $name = $_POST['name'];
    $address = $_POST['address'];
    $type = $_POST['type'];
    // Other fields can be added as needed

    // Update the business details in the database
    $updateQuery = "UPDATE businesses SET name = ?, address = ?, type = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("sssi", $name, $address, $type, $id);
    if ($updateStmt->execute()) {
        header("Location: business_list.php"); // Redirect to the business list page
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Business</title>
    <link rel="stylesheet" href="path/to/your/css/styles.css">
</head>
<body>

<div class="container">
    <h2>Update Business</h2>
    <form action="" method="POST">
        <div class="form-group">
            <label for="name">Business Name:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($business['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="address">Business Address:</label>
            <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($business['address']); ?>" required>
        </div>
        <div class="form-group">
            <label for="type">Business Type:</label>
            <input type="text" name="type" id="type" value="<?php echo htmlspecialchars($business['type']); ?>" required>
        </div>
        <!-- Add more fields as necessary -->

        <button type="submit">Update Business</button>
    </form>
</div>

</body>
</html>
