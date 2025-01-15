<?php
// test.php
session_start();
include('./assets/config/dbconn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    // Perform any database actions here...
    echo "Data received: " . $name;
} else {
    echo "This script only handles POST requests.";
}
?>
