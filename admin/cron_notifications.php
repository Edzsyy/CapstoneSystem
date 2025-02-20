<?php
// Cron job script to send notifications and expiration messages based on the expiration date

session_start();
header('Content-Type: application/json');
include('../r_and_d/config/dbconn.php');

// Fetch the AI settings (organization details, notification and expiration message templates)
$query = "SELECT organization_name, contact_number, organization_address, website_link, 
                 notify_days, notify_message, expired_message 
          FROM ai_setting LIMIT 1";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'No AI settings found']);
    exit;
}

$aiSettings = $result->fetch_assoc();

// Get the current date and the notification date (notify_days before expiration date)
$currentDate = date('Y-m-d');
$notifyDaysBeforeExpiration = $aiSettings['notify_days'];
$notificationDate = date('Y-m-d', strtotime("-$notifyDaysBeforeExpiration days"));

// Get all businesses that have expiration dates
$businessQuery = "SELECT * FROM businesses WHERE expiration_date IS NOT NULL";
$businessResult = $conn->query($businessQuery);

if ($businessResult->num_rows > 0) {
    while ($business = $businessResult->fetch_assoc()) {
        $expirationDate = $business['expiration_date'];
        $businessName = $business['business_name']; 
        $ownerEmail = $business['owner_email']; 

        // Check if today is the notification day
        if ($currentDate == $notificationDate) {
            // Send notification email if today is the notification day
            $subject = "Urgent: Your Business Permit Renewal Reminder";
            $message = str_replace(
                ['[Business Name]', '[Business Address]', '[Expiration Date]'],
                [$businessName, $business['business_address'], $expirationDate],
                $aiSettings['notify_message']
            );
            sendEmail($ownerEmail, $subject, $message);
        }

        // Check if today is the expiration day
        if ($currentDate == $expirationDate) {
            // Send expiration email if today is the expiration day
            $subject = "Final Notice: Your Business Permit Has Expired";
            $message = str_replace(
                ['[Business Name]', '[Business Address]', '[Expiration Date]'],
                [$businessName, $business['business_address'], $expirationDate],
                $aiSettings['expired_message']
            );
            sendEmail($ownerEmail, $subject, $message);
        }
    }

    echo json_encode(['success' => true, 'message' => 'Notifications and expiration messages sent successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'No businesses found']);
}

$conn->close();

// Function to send email
function sendEmail($to, $subject, $message) {
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    $headers .= 'From: ' . "no-reply@yourdomain.com" . "\r\n";

    // Use PHP's mail() function to send email
    if (mail($to, $subject, $message, $headers)) {
        echo "Email sent to $to<br>";
    } else {
        echo "Failed to send email to $to<br>";
    }
}
?>
