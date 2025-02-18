<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php'; // Ensure the autoloader is included
header('Content-Type: application/json');


// Ensure the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Extract data
    $recipientEmail = $data['recipient_email'] ?? null;
    $qrCodeBase64 = $data['qr_code_base64'] ?? null;
    $customMessage = $data['custom_message'] ?? null;
    $businessName = $data['business_name'] ?? null;
    $ownerName = $data['owner_name'] ?? null;
    $permitType = $data['permit_type'] ?? null;

    // Validate required data
    if (!$recipientEmail || !$qrCodeBase64) {
        echo json_encode(['success' => false, 'message' => 'Recipient email and QR code are required.']);
        exit;
    }

    // Prepare the email content
    $subject = "Business Permit QR Code for $businessName";
    $message = "<h3>Permit Details:</h3>";
    $message .= "<p><strong>Business Name:</strong> $businessName</p>";
    $message .= "<p><strong>Owner:</strong> $ownerName</p>";
    $message .= "<p><strong>Permit Type:</strong> $permitType</p>";

    if ($customMessage) {
        $message .= "<h3>Custom Message:</h3><p>$customMessage</p>";
    }

    // Create a PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to Gmail (use your provider if different)
        $mail->SMTPAuth = true;
        $mail->Username = 'unifiedlgu@gmail.com'; // Your email address
        $mail->Password = 'kbyt zdmk khsd pcvt'; // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        

        // Recipients
        $mail->setFrom('no-reply@unifiedlgu.com', 'si Mayor to'); // Sender's email and name
        $mail->addAddress($recipientEmail); // Add recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Remove any extra data from the base64 string (e.g., "data:image/png;base64,")
        $qrCodeBase64 = preg_replace('/^data:image\/png;base64,/', '', $qrCodeBase64);
        $decodedQRCode = base64_decode($qrCodeBase64);

        // Check if the QR code has been decoded properly
        if ($decodedQRCode === false) {
            echo json_encode(['success' => false, 'message' => 'Failed to decode the QR code.']);
            exit;
        }

        // Add the decoded image as an attachment
        $mail->addStringAttachment($decodedQRCode, 'qr_code.png', 'base64', 'image/png');

        // Send the email
        $mail->send();
        echo json_encode(['success' => true, 'message' => 'Email sent successfully.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to send email: ' . $mail->ErrorInfo]);
    }
}


?>
