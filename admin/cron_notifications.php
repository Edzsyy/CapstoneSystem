<?php
// Cron job script to send notifications and expiration messages based on the expiration date
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
session_start();
header('Content-Type: application/json');
include('../r_and_d/config/dbconn.php');

// Set timezone
date_default_timezone_set('Asia/Manila');

// Fetch the AI settings (organization details, notification and expiration message templates)
$query = "SELECT organization_name, contact_number, organization_address, website_link, 
                 notify_days, notify_message, expired_message 
          FROM ai_setting LIMIT 1";
$result = $conn->query($query);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Error fetching AI settings: ' . $conn->error]);
    exit;
}

if ($result->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'No AI settings found']);
    exit;
}

$aiSettings = $result->fetch_assoc();

// Get the current date and the notification date (notify_days before expiration date)
$currentDate = date('Y-m-d');
$notifyDaysBeforeExpiration = $aiSettings['notify_days'];
$notificationDate = date('Y-m-d', strtotime("-$notifyDaysBeforeExpiration days"));

// Fetch all businesses that have expiration dates from registration and renewal tables
$businessQuery = "
    SELECT r.business_id, r.business_name, r.business_address, r.owner_email, r.fname, r.mname, r.lname, r.expiration_date AS registration_expiration, 
           n.expiration_date AS renewal_expiration
    FROM registration r
    LEFT JOIN renewal n ON r.business_id = n.business_id
    WHERE r.expiration_date IS NOT NULL OR n.expiration_date IS NOT NULL
";

$businessResult = $conn->query($businessQuery);

if (!$businessResult) {
    echo json_encode(['success' => false, 'message' => 'Error fetching businesses from registration and renewal tables: ' . $conn->error]);
    exit;
}

if ($businessResult->num_rows > 0) {
    while ($business = $businessResult->fetch_assoc()) {
        // Choose the expiration date based on the registration and renewal tables
        $expirationDate = $business['renewal_expiration'] ?: $business['registration_expiration'];
        $businessName = $business['business_name'];
        $ownerEmail = $business['owner_email'];

        // Combine first name, middle name, and last name to create the full owner name
        $ownerName = ucwords(strtolower(trim($business['fname'] . ' ' . $business['mname'] . ' ' . $business['lname'])));

        // If middle name is empty, remove the extra space
        if (empty($business['mname'])) {
            $ownerName = trim($business['fname'] . ' ' . $business['lname']);
        }

        // Check if today is the notification day
        if ($currentDate == $notificationDate) {
            // Send notification email if today is the notification day
            $subject = "Urgent: Your Business Permit Renewal Reminder";
            // Replace placeholders with actual business data
            $message = str_replace(
                ['[Business Owner Name]', '[Business Name]', '[Business Address]', '[Expiration Date]'],
                [$ownerName, $businessName, $business['business_address'], $expirationDate],
                $aiSettings['notify_message']
            );
            sendEmail($ownerEmail, $subject, $message);
        }

        // Check if today is the expiration day
        if ($currentDate == $expirationDate) {
            // Send expiration email if today is the expiration day
            $subject = "Final Notice: Your Business Permit Has Expired";
            $gracePeriod = 7; // Example grace period, you can adjust it

            // Replace placeholders with actual business data
            $message = str_replace(
                ['[Business Owner Name]', '[Business Name]', '[Business Address]', '[Expiration Date]', '[Number]'],
                [$ownerName, $businessName, $business['business_address'], $expirationDate, $gracePeriod],
                $aiSettings['expired_message']
            );

            sendEmail($ownerEmail, $subject, $message);
        }
    }

    echo json_encode(['success' => true, 'message' => 'Notifications and expiration messages sent successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'No businesses found in registration or renewal tables']);
}



$conn->close();

// Function to send email using PHPMailer
function sendEmail($to, $subject, $message)
{

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Set the SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'unifiedlgu@gmail.com';  // SMTP username
        $mail->Password = 'kbyt zdmk khsd pcvt';  // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('no-reply@unifiedlgu.com', 'LGU E-SERVICES');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        // Send the email
        $mail->send();
        echo "Email sent to $to<br>";
    } catch (Exception $e) {
        echo "Failed to send email to $to. Error: {$mail->ErrorInfo}<br>";
    }
}
