<?php
require_once '../vendor/autoload.php'; // Include Composer's autoloader

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

// Set response header
header('Content-Type: application/json');

try {
    // Retrieve data from the POST request
    if (!isset($_POST['email'])) {
        throw new Exception('Invalid input. Missing email.');
    }

    $email = $_POST['email'];

    // Generate the QR code using the Builder
    $builder = new Builder(
        writer: new PngWriter(),
        writerOptions: [],
        validateResult: false,
        data: "User's email: $email",
        encoding: new Encoding('UTF-8'),
        errorCorrectionLevel: ErrorCorrectionLevel::High,
        size: 300,
        margin: 10,
        roundBlockSizeMode: RoundBlockSizeMode::Margin,
        labelText: 'Business Permit QR Code',
        labelFont: new OpenSans(20),
        labelAlignment: LabelAlignment::Center
    );

    $result = $builder->build();
    // Check if the result is valid before continuing
    if (!$result) {
        throw new Exception('Failed to generate QR code.');
    }

    // Generate a Base64 string to send to the frontend
    $qrBase64 = $result->getDataUri();

    // Respond with success and the QR code data URI
    echo json_encode([
        'success' => true,
        'message' => 'QR Code generated successfully!',
        'qr_code_base64' => $qrBase64
    ]);
} catch (Exception $e) {
    // Handle exceptions and respond with an error
    echo json_encode([
        'success' => false,
        'message' => 'QR Code generation failed: ' . $e->getMessage()
    ]);
    exit;
}

    
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
    // Send Email with QR Code
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'unifiedlgu@gmail.com'; // Your email address
        $mail->Password = 'unified123'; // Your email password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        //sender
        $mail->setFrom('unifiedlgu@gmail.com', 'Business Permit System');
        $mail->addAddress($email);

        //email content
        $mail->isHTML(true);
        $mail->Subject = 'Your Business Permit QR Code';
        $mail->Body = '<p>Your application has been approved. Please use this QR code for claiming your permit.</p>';
        $mail->addAttachment($qr_file_path); // Attach the generated QR code

        $mail->send();
        echo json_encode(['success' => true, 'message' => 'QR Code generated and email sent successfully.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Email sending failed: ' . $e->getMessage()]);
    }

?>
